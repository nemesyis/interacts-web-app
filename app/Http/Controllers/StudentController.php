<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Enrollment;
use App\Models\Appointment;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Project;
use App\Models\ProjectSubmission;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Show student dashboard
     */
    public function dashboard()
    {
        $student = auth()->user();
        
        // Get enrolled classrooms
        $enrollments = Enrollment::where('student_id', $student->user_id)
            ->where('status', 'active')
            ->with(['classroom.teacher', 'classroom.appointments'])
            ->latest()
            ->get();

        $stats = [
            'total_classrooms' => $enrollments->count(),
            'total_appointments' => Appointment::whereIn('classroom_id', $enrollments->pluck('classroom_id'))->count(),
            'completed_quizzes' => QuizAttempt::where('student_id', $student->user_id)->whereNotNull('submitted_at')->count(),
            'submitted_projects' => ProjectSubmission::where('student_id', $student->user_id)->count(),
        ];

        // Get upcoming open appointments
        $upcomingAppointments = Appointment::whereIn('classroom_id', $enrollments->pluck('classroom_id'))
            ->where('is_open', true)
            ->where('scheduled_date', '>=', today())
            ->orderBy('scheduled_date')
            ->orderBy('scheduled_time')
            ->with('classroom')
            ->take(5)
            ->get();

        return view('student.dashboard', compact('enrollments', 'stats', 'upcomingAppointments'));
    }

    /**
     * Show join classroom form
     */
    public function showJoinForm()
    {
        return view('student.join');
    }

    /**
     * Join classroom with token
     */
    public function joinClassroom(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string',
        ]);

        $classroom = Classroom::where('access_token', strtoupper($request->access_token))
            ->first();

        if (!$classroom) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['access_token' => 'Invalid access token.']);
        }

        if (!$classroom->token_is_active) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['access_token' => 'This classroom token is not active. Please contact your teacher.']);
        }

        // Check if already enrolled
        $existing = Enrollment::where('classroom_id', $classroom->classroom_id)
            ->where('student_id', auth()->id())
            ->first();

        if ($existing) {
            if ($existing->status === 'active') {
                return redirect()->route('student.classroom.view', $classroom->classroom_id)
                    ->with('info', 'You are already enrolled in this classroom.');
            } else {
                // Reactivate enrollment
                $existing->update(['status' => 'active']);
                return redirect()->route('student.classroom.view', $classroom->classroom_id)
                    ->with('success', 'Successfully rejoined the classroom!');
            }
        }

        // Create new enrollment
        Enrollment::create([
            'classroom_id' => $classroom->classroom_id,
            'student_id' => auth()->id(),
            'status' => 'active',
        ]);

        return redirect()->route('student.classroom.view', $classroom->classroom_id)
            ->with('success', 'Successfully joined the classroom!');
    }

    /**
     * View classroom details
     */
    public function viewClassroom($id)
    {
        $enrollment = Enrollment::where('classroom_id', $id)
            ->where('student_id', auth()->id())
            ->where('status', 'active')
            ->firstOrFail();

        $classroom = Classroom::with('teacher')
            ->findOrFail($id);

        $appointments = Appointment::where('classroom_id', $id)
            ->orderBy('appointment_number')
            ->get();

        return view('student.classroom', compact('classroom', 'appointments', 'enrollment'));
    }

    /**
     * View appointment details
     */
    public function viewAppointment($id)
    {
        $appointment = Appointment::with(['classroom.teacher', 'materials', 'quiz', 'report'])
            ->findOrFail($id);

        // Verify student is enrolled
        $enrollment = Enrollment::where('classroom_id', $appointment->classroom_id)
            ->where('student_id', auth()->id())
            ->where('status', 'active')
            ->firstOrFail();

        // Get projects
        $projects = Project::where('appointment_id', $id)
            ->where('is_active', true)
            ->get();

        // Get student's quiz attempt
        $quizAttempt = null;
        if ($appointment->quiz) {
            $quizAttempt = QuizAttempt::where('quiz_id', $appointment->quiz->quiz_id)
                ->where('student_id', auth()->id())
                ->first();
        }

        // Get student's project submissions
        $mySubmissions = ProjectSubmission::whereIn('project_id', $projects->pluck('project_id'))
            ->where('student_id', auth()->id())
            ->get()
            ->keyBy('project_id');

        // Record attendance
        $this->recordAttendance($id);

        return view('student.appointment', compact('appointment', 'projects', 'quizAttempt', 'mySubmissions'));
    }

    /**
     * Record attendance when student views appointment
     */
    private function recordAttendance($appointmentId)
    {
        $existing = Attendance::where('appointment_id', $appointmentId)
            ->where('student_id', auth()->id())
            ->whereDate('check_in_time', today())
            ->first();

        if (!$existing) {
            Attendance::create([
                'appointment_id' => $appointmentId,
                'student_id' => auth()->id(),
            ]);
        }
    }

    /**
     * Show quiz
     */
    public function takeQuiz($id)
    {
        $quiz = Quiz::with(['appointment.classroom', 'questions'])
            ->findOrFail($id);

        // Verify student is enrolled
        $enrollment = Enrollment::where('classroom_id', $quiz->appointment->classroom_id)
            ->where('student_id', auth()->id())
            ->where('status', 'active')
            ->firstOrFail();

        // Check if appointment is open
        if (!$quiz->appointment->is_open) {
            return redirect()->back()
                ->with('error', 'This appointment is currently closed.');
        }

        // Check if already taken
        $existingAttempt = QuizAttempt::where('quiz_id', $id)
            ->where('student_id', auth()->id())
            ->first();

        if ($existingAttempt) {
            return redirect()->route('student.appointment.view', $quiz->appointment_id)
                ->with('info', 'You have already completed this quiz.');
        }

        return view('student.quiz.take', compact('quiz'));
    }

    /**
     * Submit quiz
     */
    public function submitQuiz(Request $request, $id)
    {
        $quiz = Quiz::with(['questions', 'appointment'])->findOrFail($id);

        // Verify enrollment and appointment is open
        $enrollment = Enrollment::where('classroom_id', $quiz->appointment->classroom_id)
            ->where('student_id', auth()->id())
            ->where('status', 'active')
            ->firstOrFail();

        if (!$quiz->appointment->is_open) {
            return redirect()->back()
                ->with('error', 'This appointment is currently closed.');
        }

        // Check if already submitted
        $existingAttempt = QuizAttempt::where('quiz_id', $id)
            ->where('student_id', auth()->id())
            ->first();

        if ($existingAttempt) {
            return redirect()->route('student.appointment.view', $quiz->appointment_id)
                ->with('error', 'You have already submitted this quiz.');
        }

        // Calculate score
        $totalPoints = 0;
        $earnedPoints = 0;

        foreach ($quiz->questions as $question) {
            $totalPoints += $question->points;
            $studentAnswer = $request->input('answer_' . $question->question_id);

            if ($studentAnswer && strtolower(trim($studentAnswer)) === strtolower(trim($question->correct_answer))) {
                $earnedPoints += $question->points;
            }
        }

        $passed = $quiz->passing_score ? ($earnedPoints >= $quiz->passing_score) : true;

        // Create attempt record
        QuizAttempt::create([
            'quiz_id' => $id,
            'student_id' => auth()->id(),
            'score' => $earnedPoints,
            'total_points' => $totalPoints,
            'passed' => $passed,
            'submitted_at' => now(),
        ]);

        return redirect()->route('student.appointment.view', $quiz->appointment_id)
            ->with('success', 'Quiz submitted successfully! Your score: ' . $earnedPoints . '/' . $totalPoints);
    }

    /**
     * Submit project
     */
    public function submitProject(Request $request, $id)
    {
        $project = Project::with('appointment')->findOrFail($id);

        // Verify enrollment
        $enrollment = Enrollment::where('classroom_id', $project->appointment->classroom_id)
            ->where('student_id', auth()->id())
            ->where('status', 'active')
            ->firstOrFail();

        // Check if appointment is open
        if (!$project->appointment->is_open) {
            return redirect()->back()
                ->with('error', 'This appointment is currently closed.');
        }

        $request->validate([
            'file' => 'required|file|max:102400', // 100MB max
            'submission_note' => 'nullable|string',
        ]);

        // Check if already submitted
        $existing = ProjectSubmission::where('project_id', $id)
            ->where('student_id', auth()->id())
            ->first();

        if ($existing) {
            // Delete old file
            $oldPath = public_path($existing->file_url);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
            $existing->delete();
        }

        // Upload file
        $file = $request->file('file');
        $filename = time() . '_' . auth()->id() . '_' . $file->getClientOriginalName();
        $file->move(public_path('projects'), $filename);

        // Create submission
        ProjectSubmission::create([
            'project_id' => $id,
            'student_id' => auth()->id(),
            'file_url' => '/projects/' . $filename,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'submission_note' => $request->submission_note,
        ]);

        return redirect()->back()
            ->with('success', 'Project submitted successfully!');
    }

    /**
     * View report
     */
    public function viewReport($id)
    {
        $appointment = Appointment::with(['classroom.teacher', 'report'])
            ->findOrFail($id);

        // Verify enrollment
        $enrollment = Enrollment::where('classroom_id', $appointment->classroom_id)
            ->where('student_id', auth()->id())
            ->where('status', 'active')
            ->firstOrFail();

        if (!$appointment->report) {
            return redirect()->back()
                ->with('error', 'No report available for this appointment yet.');
        }

        // Get all student data for this appointment
        $students = DB::table('tb_enrollment')
            ->join('tb_user', 'tb_enrollment.student_id', '=', 'tb_user.user_id')
            ->where('tb_enrollment.classroom_id', $appointment->classroom_id)
            ->where('tb_enrollment.status', 'active')
            ->select('tb_user.*')
            ->get();

        // Get quiz attempts
        $quizAttempts = [];
        if ($appointment->quiz) {
            $quizAttempts = DB::table('tb_quiz_attempt')
                ->where('quiz_id', $appointment->quiz->quiz_id)
                ->get()
                ->keyBy('student_id');
        }

        // Get project submissions
        $projectSubmissions = DB::table('tb_project_submission')
            ->join('tb_project', 'tb_project_submission.project_id', '=', 'tb_project.project_id')
            ->where('tb_project.appointment_id', $id)
            ->select('tb_project_submission.*')
            ->get()
            ->groupBy('student_id');

        // Get attendance
        $attendance = DB::table('tb_attendance')
            ->where('appointment_id', $id)
            ->get()
            ->keyBy('student_id');

        return view('student.report', compact('appointment', 'students', 'quizAttempts', 'projectSubmissions', 'attendance'));
    }
}