<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tb_user';
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'email',
        'username',
        'password_hash',
        'full_name',
        'profile_picture',
        'role',
        'account_status',
        'must_change_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login' => 'datetime',
            'must_change_password' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Override the password column name
     */
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    public function setPasswordAttribute($value)
    {
    $this->attributes['password_hash'] = $value;
    }

    /**
     * Get the password for authentication
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Get the column name for the "password"
     */
    public function getAuthPasswordName()
    {
        return 'password_hash';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is teacher
     */
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    /**
     * Check if user is student
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Check if account is active
     */
    public function isActive(): bool
    {
        return $this->account_status === 'active';
    }

    /**
     * Relationships
     */
    
    // Classrooms created by admin
    public function createdClassrooms()
    {
        return $this->hasMany(Classroom::class, 'created_by_admin_id');
    }

    // Classrooms assigned to teacher
    public function teachingClassrooms()
    {
        return $this->hasMany(Classroom::class, 'teacher_id');
    }

    // Student enrollments
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    // Quiz attempts
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class, 'student_id');
    }

    // Project submissions
    public function projectSubmissions()
    {
        return $this->hasMany(ProjectSubmission::class, 'student_id');
    }

    // Attendance records
    public function attendanceRecords()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }
}