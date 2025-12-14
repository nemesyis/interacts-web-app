<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Student Registration (public)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Password Reset Routes (we'll create these later)
Route::get('/password/reset', function () {
    return view('auth.passwords.email');
})->name('password.request');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Teacher invitation
    Route::get('/invite-teacher', [AdminController::class, 'showInviteForm'])->name('invite.teacher');
    Route::post('/invite-teacher', [AdminController::class, 'inviteTeacher'])->name('invite.teacher.submit');
    Route::post('/resend-invitation/{id}', [AdminController::class, 'resendInvitation'])->name('invitation.resend');
    
    // Classroom management
    Route::get('/classrooms', [AdminController::class, 'classrooms'])->name('classrooms');
    Route::get('/classrooms/create', [AdminController::class, 'createClassroom'])->name('classrooms.create');
    Route::post('/classrooms', [AdminController::class, 'storeClassroom'])->name('classrooms.store');
    Route::get('/classrooms/{id}/edit', [AdminController::class, 'editClassroom'])->name('classrooms.edit');
    Route::put('/classrooms/{id}', [AdminController::class, 'updateClassroom'])->name('classrooms.update');
    Route::post('/classrooms/{id}/toggle-token', [AdminController::class, 'toggleToken'])->name('classrooms.toggle.token');
});

/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
    
    // Appointments
    Route::get('/classrooms/{id}/appointments', [TeacherController::class, 'appointments'])->name('appointments');
    Route::get('/classrooms/{id}/appointments/create', [TeacherController::class, 'createAppointment'])->name('appointments.create');
    Route::post('/classrooms/{id}/appointments', [TeacherController::class, 'storeAppointment'])->name('appointments.store');
    
    // Materials
    Route::get('/appointments/{id}/materials', [TeacherController::class, 'materials'])->name('materials');
    Route::post('/appointments/{id}/materials', [TeacherController::class, 'storeMaterial'])->name('materials.store');
    
    // Quizzes
    Route::get('/appointments/{id}/quiz', [TeacherController::class, 'createQuiz'])->name('quiz.create');
    Route::post('/appointments/{id}/quiz', [TeacherController::class, 'storeQuiz'])->name('quiz.store');
    
    // Reports
    Route::get('/appointments/{id}/report', [TeacherController::class, 'viewReport'])->name('report.view');
    Route::post('/appointments/{id}/report', [TeacherController::class, 'createReport'])->name('report.create');
    Route::put('/appointments/{id}/report', [TeacherController::class, 'updateReport'])->name('report.update');
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    
    // Join classroom with token
    Route::get('/join', [StudentController::class, 'showJoinForm'])->name('join');
    Route::post('/join', [StudentController::class, 'joinClassroom'])->name('join.submit');
    
    // View classrooms and appointments
    Route::get('/classrooms/{id}', [StudentController::class, 'viewClassroom'])->name('classroom.view');
    Route::get('/appointments/{id}', [StudentController::class, 'viewAppointment'])->name('appointment.view');
    
    // Take quiz
    Route::get('/quiz/{id}', [StudentController::class, 'takeQuiz'])->name('quiz.take');
    Route::post('/quiz/{id}/submit', [StudentController::class, 'submitQuiz'])->name('quiz.submit');
    
    // Submit project
    Route::post('/project/{id}/submit', [StudentController::class, 'submitProject'])->name('project.submit');
    
    // View report
    Route::get('/appointments/{id}/report', [StudentController::class, 'viewReport'])->name('report.view');
});

/*
|--------------------------------------------------------------------------
| Password Change Route (all authenticated users)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/password/change', function () {
        return view('auth.passwords.change');
    })->name('password.change');
    
    Route::post('/password/change', [LoginController::class, 'changePassword'])->name('password.change.submit');
});