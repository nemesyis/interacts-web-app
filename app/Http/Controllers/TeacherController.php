<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Appointment;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\TeacherReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Project;
use App\Models\ProjectSubmission;

class TeacherController extends Controller
{
    /**
     * Show teacher dashboard
     */
    public function dashboard()
    {
        $teacher = auth()->user();
        
        // Get teacher's classrooms
        $classrooms = Classroom::where('teacher_id', $teacher->user_id)
            ->withCount(['enrollments' => function($query) {
                $query->where('status', 'active');
            }])
            ->with('enrollments')
            ->latest()
            ->get();

        // Get upcoming appointments
        $upcomingAppointments = Appointment::whereIn('classroom_id', $classrooms->pluck('classroom_id'))
            ->where('scheduled_date', '>=', today())
            ->orderBy('scheduled_date')
            ->orderBy('scheduled_time')
            ->with('classroom')
            ->take(5)
            ->get();

        $stats = [
            'total_classrooms' => $classrooms->count(),
            'total_students' => $classrooms->sum->enrollments_count,
            'total_appointments' => Appointment::whereIn('classroom_id', $classrooms->pluck('classroom_id'))->count(),
            'open_appointments' => Appointment::whereIn('classroom_id', $classrooms->pluck('classroom_id'))
                ->where('is_open', true)->count(),
        ];

        return view('teacher.dashboard', compact('classrooms', 'upcomingAppointments', 'stats'));
    }

    /**
     * Show appointments for a classroom
     */
    public function appointments($id)
    {
        $classroom = Classroom::where('classroom_id', $id)
            ->where('teacher_id', auth()->id())
            ->firstOrFail();

        $appointments = Appointment::where('classroom_id', $id)
            ->withCount(['materials', 'quiz' => function($query) {
                $query->select(\DB::raw('count(*)'));
            }])
            ->orderBy('appointment_number')
            ->orderBy('scheduled_date')
            ->get();

        return view('teacher.appointments.index', compact('classroom', 'appointments'));
    }

    /**
     * Show create appointment form
     */
    public function createAppointment($id)
    {
        $classroom = Classroom::where('classroom_id', $id)
            ->where('teacher_id', auth()->id())
            ->firstOrFail();

        // Get next appointment number
        $nextNumber = Appointment::where('classroom_id', $id)->max('appointment_number') + 1;

        return view('teacher.appointments.create', compact('classroom', 'nextNumber'));
    }

    /**
     * Store new appointment
     */
    public function storeAppointment(Request $request, $id)
    {
        $classroom = Classroom::where('classroom_id', $id)
            ->where('teacher_id', auth()->id())
            ->firstOrFail();

        $request->validate([
            'appointment_title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required',
            'duration_minutes' => 'required|integer|min:15|max:480',
        ]);

        $appointment = Appointment::create([
            'classroom_id' => $id,
            'appointment_title' => $request->appointment_title,
            'description' => $request->description,
            'scheduled_date' => $request->scheduled_date,
            'scheduled_time' => $request->scheduled_time,
            'duration_minutes' => $request->duration_minutes,
            'appointment_number' => Appointment::where('classroom_id', $id)->max('appointment_number') + 1,
            'is_open' => false,
        ]);

        return redirect()->route('teacher.appointments', $id)
            ->with('success', 'Appointment created successfully!');
    }

    /**
     * Toggle appointment open/close status
     */
    public function toggleAppointment($id)
    {
        $appointment = Appointment::whereHas('classroom', function($query) {
            $query->where('teacher_id', auth()->id());
        })->findOrFail($id);

        $appointment->update([
            'is_open' => !$appointment->is_open,
        ]);

        $status = $appointment->is_open ? 'opened' : 'closed';

        return redirect()->back()
            ->with('success', "Appointment has been {$status}!");
    }

    /**
     * Show materials for an appointment
     */
    public function materials($id)
    {
        $appointment = Appointment::whereHas('classroom', function($query) {
            $query->where('teacher_id', auth()->id());
        })->with('classroom')->findOrFail($id);

        $materials = Material::where('appointment_id', $id)
            ->orderBy('order_number')
            ->get();

        return view('teacher.materials.index', compact('appointment', 'materials'));
    }

    /**
     * Store new material
     */
    public function storeMaterial(Request $request, $id)
    {
        $appointment = Appointment::whereHas('classroom', function($query) {
            $query->where('teacher_id', auth()->id());
        })->findOrFail($id);

        $request->validate([
            'material_title' => 'required|string|max:200',
            'material_type' => 'required|in:video,pdf,slides,document,link',
            'description' => 'nullable|string',
            'file' => $request->material_type != 'link' ? 'required|file|max:51200' : 'nullable',
            'file_url' => $request->material_type == 'link' ? 'required|url' : 'nullable',
        ], [
            'file.max' => 'File size cannot exceed 50MB',
        ]);

        $fileUrl = null;

        if ($request->material_type == 'link') {
            $fileUrl = $request->file_url;
        } else {
            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time() . '_' . $file->getClientOriginalName();

                $file->move(public_path('materials'), $filename);

                $fileUrl = '/materials/' . $filename;
                }

            }
        

        $nextOrder = Material::where('appointment_id', $id)->max('order_number') + 1;

        Material::create([
            'appointment_id' => $id,
            'material_title' => $request->material_title,
            'material_type' => $request->material_type,
            'file_url' => $fileUrl,
            'description' => $request->description,
            'order_number' => $nextOrder,
        ]);

        return redirect()->route('teacher.materials', $id)
            ->with('success', 'Material uploaded successfully!');
    }

    /**
     * Delete material
     */
    public function deleteMaterial($id)
    {
        $material = Material::whereHas('appointment.classroom', function($query) {
            $query->where('teacher_id', auth()->id());
        })->findOrFail($id);

        // Delete file if not a link
        if ($material->material_type != 'link' && $material->file_url) {
            $filePath = public_path($material->file_url);
            if (file_exists($filePath)) {
                unlink($filePath);
                }
            }

        $appointmentId = $material->appointment_id;
        $material->delete();

        return redirect()->route('teacher.materials', $appointmentId)
            ->with('success', 'Material deleted successfully!');
    }

    /**
     * Show create quiz form
     */
    public function createQuiz($id)
    {
        $appointment = Appointment::whereHas('classroom', function($query) {
            $query->where('teacher_id', auth()->id());
        })->with('classroom')->findOrFail($id);

        // Check if quiz already exists
        $existingQuiz = Quiz::where('appointment_id', $id)->first();
        if ($existingQuiz) {
            return redirect()->route('teacher.appointments', $appointment->classroom_id)
                ->with('error', 'This appointment already has a quiz. You can edit it from the appointments list.');
        }

        return view('teacher.quiz.create', compact('appointment'));
    }

    /**
     * Store quiz (we'll implement this fully later with questions)
     */
    public function storeQuiz(Request $request, $id)
    {
        // This will be implemented when we build the quiz system
        return redirect()->back()->with('info', 'Quiz creation coming soon!');
    }

    /**
     * View report for appointment
     */
    public function viewReport($id)
    {
        $appointment = Appointment::whereHas('classroom', function($query) {
            $query->where('teacher_id', auth()->id());
        })->with(['classroom', 'report'])->findOrFail($id);

        // Get student data
        $students = \DB::table('tb_enrollment')
            ->join('tb_user', 'tb_enrollment.student_id', '=', 'tb_user.user_id')
            ->where('tb_enrollment.classroom_id', $appointment->classroom_id)
            ->where('tb_enrollment.status', 'active')
            ->select('tb_user.*')
            ->get();

        // Get quiz attempts
        $quizAttempts = [];
        if ($appointment->quiz) {
            $quizAttempts = \DB::table('tb_quiz_attempt')
                ->where('quiz_id', $appointment->quiz->quiz_id)
                ->get()
                ->keyBy('student_id');
        }

        // Get project submissions
        $projectSubmissions = \DB::table('tb_project_submission')
            ->join('tb_project', 'tb_project_submission.project_id', '=', 'tb_project.project_id')
            ->where('tb_project.appointment_id', $id)
            ->select('tb_project_submission.*')
            ->get()
            ->groupBy('student_id');

        // Get attendance
        $attendance = \DB::table('tb_attendance')
            ->where('appointment_id', $id)
            ->get()
            ->keyBy('student_id');

        return view('teacher.report.view', compact('appointment', 'students', 'quizAttempts', 'projectSubmissions', 'attendance'));
    }

    /**
     * Create or update report
     */
    public function createReport(Request $request, $id)
    {
        $appointment = Appointment::whereHas('classroom', function ($query) {
        $query->where('teacher_id', auth()->id());
        })->findOrFail($id);

        $request->validate([
            'report_title' => 'required|string|max:200',
            'report_content' => 'required|string',
        ]);

        TeacherReport::updateOrCreate(
            ['appointment_id' => $id],
            [
                'teacher_id' => auth()->id(),
                'report_title' => $request->report_title,
                'report_content' => $request->report_content,
            ]
        );

        return redirect()->route('teacher.report.view', $id)
            ->with('success', 'Report saved successfully!');
    }

    /**
     * Update report
     */
    public function updateReport(Request $request, $id)
    {
        return $this->createReport($request, $id);
    }

    /**
     * Show projects for an appointment
     */
    public function projects($id)
    {
        $appointment = Appointment::whereHas('classroom', function($query) {
            $query->where('teacher_id', auth()->id());
        })->with('classroom')->findOrFail($id);

        $projects = Project::where('appointment_id', $id)
            ->withCount('submissions')
            ->orderBy('created_at')
            ->get();

        return view('teacher.projects.index', compact('appointment', 'projects'));
    }

    /**
     * Store new project
     */
    public function storeProject(Request $request, $id)
    {
        $appointment = Appointment::whereHas('classroom', function($query) {
            $query->where('teacher_id', auth()->id());
        })->findOrFail($id);

        $request->validate([
            'project_title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        Project::create([
            'appointment_id' => $id,
            'project_title' => $request->project_title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'is_active' => true,
        ]);

        return redirect()->route('teacher.projects', $id)
            ->with('success', 'Project created successfully!');
    }

    /**
     * Delete project
     */
    public function deleteProject($id)
    {
        $project = Project::whereHas('appointment.classroom', function($query) {
            $query->where('teacher_id', auth()->id());
        })->findOrFail($id);

        // Delete all submission files
        foreach ($project->submissions as $submission) {
            $filePath = public_path($submission->file_url);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $appointmentId = $project->appointment_id;
        $project->delete();

        return redirect()->route('teacher.projects', $appointmentId)
            ->with('success', 'Project and all submissions deleted successfully!');
    }
}