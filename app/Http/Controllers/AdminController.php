<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classroom;
use App\Models\TeacherInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_classrooms' => Classroom::count(),
            'pending_invitations' => TeacherInvitation::where('status', 'pending')->count(),
        ];

        $recentInvitations = TeacherInvitation::with('admin')
            ->latest()
            ->take(5)
            ->get();

        $recentClassrooms = Classroom::with(['teacher', 'admin'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentInvitations', 'recentClassrooms'));
    }

    /**
     * Show teacher invitation form
     */
    public function showInviteForm()
    {
        $invitations = TeacherInvitation::with('admin')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.invite-teacher', compact('invitations'));
    }

    /**
     * Send teacher invitation
     */
    public function inviteTeacher(Request $request)
    {
        $request->validate([
            'teacher_email' => 'required|email|unique:tb_user,email|unique:tb_teacher_invitation,teacher_email',
            'teacher_full_name' => 'required|string|max:200',
            'teacher_username' => 'required|string|max:100|unique:tb_user,username|unique:tb_teacher_invitation,teacher_username|alpha_dash',
        ]);

        // Generate invitation token
        $invitationToken = Str::random(64);

        // Create invitation (NO temp password needed)
        $invitation = TeacherInvitation::create([
            'invited_by_admin_id' => auth()->id(),
            'teacher_email' => $request->teacher_email,
            'teacher_full_name' => $request->teacher_full_name,
            'teacher_username' => $request->teacher_username,
            'temp_password_hash' => '', // Not used anymore
            'invitation_token' => $invitationToken,
            'status' => 'pending',
            'expires_at' => Carbon::now()->addDays(7),
        ]);

        // Send email
        try {
            Mail::send('emails.teacher-invitation', [
                'full_name' => $request->teacher_full_name,
                'username' => $request->teacher_username,
                'invitation_token' => $invitationToken,
                'accept_url' => route('teacher.accept', $invitationToken),
            ], function ($message) use ($request) {
                $message->to($request->teacher_email)
                    ->subject('Teacher Invitation - Interacts Platform');
            });

            $message = 'Invitation sent successfully to ' . $request->teacher_email;
        } catch (\Throwable $e) {
            dd($e->getMessage()) . route('teacher.accept', $invitationToken);
        }

        return redirect()->route('admin.invite.teacher')
            ->with('success', $message)
            ->with('invitation_link', route('teacher.accept', $invitationToken));
    }

    /**
     * Resend teacher invitation
     */
    public function resendInvitation($id)
    {
        $invitation = TeacherInvitation::findOrFail($id);

        if ($invitation->status === 'accepted') {
            return redirect()->route('admin.invite.teacher')
                ->with('error', 'This invitation has already been accepted.');
        }

        // Generate new temporary password
        $tempPassword = Str::random(12);

        // Update invitation
        $invitation->update([
            'temp_password_hash' => Hash::make($tempPassword),
            'status' => 'resent',
            'expires_at' => Carbon::now()->addDays(7),
            'resent_at' => now(),
        ]);

        // Try to send email
        try {
            \Log::info('Sending invitation email to ' . $request->teacher_email);
            Mail::send('emails.teacher-invitation', [
                'full_name' => $invitation->teacher_full_name,
                'username' => $invitation->teacher_username,
                'password' => $tempPassword,
                'login_url' => route('login'),
            ], function ($message) use ($invitation) {
                $message->to($invitation->teacher_email)
                    ->subject('Teacher Invitation Resent - Interacts Platform');
            });

            $message = 'Invitation resent successfully!';
        } catch (\Exception $e) {
            $message = 'Invitation updated! Please provide these credentials manually:';
            session()->flash('temp_password', $tempPassword);
        }

        return redirect()->route('admin.invite.teacher')
            ->with('success', $message)
            ->with('invitation_details', [
                'username' => $invitation->teacher_username,
                'password' => $tempPassword,
                'email' => $invitation->teacher_email,
            ]);
    }

    /**
     * Show all classrooms
     */
    public function classrooms(Request $request)
    {
        $search = $request->input('search');
        
        $classrooms = Classroom::with(['teacher', 'admin', 'enrollments'])
            ->when($search, function ($query) use ($search) {
                $query->where('classroom_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('teacher', function ($q) use ($search) {
                        $q->where('full_name', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('enrollments.student', function ($q) use ($search) {
                        $q->where('full_name', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('admin.classrooms.index', compact('classrooms', 'search'));
    }

    /**
     * Show create classroom form
     */
    public function createClassroom()
    {
        $teachers = User::where('role', 'teacher')
            ->where('account_status', 'active')
            ->orderBy('full_name')
            ->get();

        return view('admin.classrooms.create', compact('teachers'));
    }

    /**
     * Store new classroom
     */
    public function storeClassroom(Request $request)
    {
        $request->validate([
            'classroom_name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'teacher_id' => 'required|exists:tb_user,user_id',
        ]);

        // Verify teacher exists and is active
        $teacher = User::where('user_id', $request->teacher_id)
            ->where('role', 'teacher')
            ->where('account_status', 'active')
            ->firstOrFail();

        // Generate unique access token
        $accessToken = strtoupper(Str::random(8));
        
        // Make sure token is unique
        while (Classroom::where('access_token', $accessToken)->exists()) {
            $accessToken = strtoupper(Str::random(8));
        }

        $classroom = Classroom::create([
            'created_by_admin_id' => auth()->id(),
            'teacher_id' => $request->teacher_id,
            'classroom_name' => $request->classroom_name,
            'description' => $request->description,
            'access_token' => $accessToken,
            'token_is_active' => false,
        ]);

        return redirect()->route('admin.classrooms')
            ->with('success', 'Classroom created successfully! Access token: ' . $accessToken);
    }

    /**
     * Show edit classroom form
     */
    public function editClassroom($id)
    {
        $classroom = Classroom::with(['teacher', 'enrollments'])->findOrFail($id);
        
        $teachers = User::where('role', 'teacher')
            ->where('account_status', 'active')
            ->orderBy('full_name')
            ->get();

        return view('admin.classrooms.edit', compact('classroom', 'teachers'));
    }

    /**
     * Update classroom
     */
    public function updateClassroom(Request $request, $id)
    {
        $classroom = Classroom::findOrFail($id);

        $request->validate([
            'classroom_name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'teacher_id' => 'required|exists:tb_user,user_id',
        ]);

        // Verify teacher
        $teacher = User::where('user_id', $request->teacher_id)
            ->where('role', 'teacher')
            ->where('account_status', 'active')
            ->firstOrFail();

        $classroom->update([
            'classroom_name' => $request->classroom_name,
            'description' => $request->description,
            'teacher_id' => $request->teacher_id,
        ]);

        return redirect()->route('admin.classrooms')
            ->with('success', 'Classroom updated successfully!');
    }

    /**
     * Toggle classroom token activation
     */
    public function toggleToken($id)
    {
        $classroom = Classroom::findOrFail($id);
        
        $classroom->update([
            'token_is_active' => !$classroom->token_is_active,
        ]);

        $status = $classroom->token_is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Classroom token has been {$status}!");
    }
}