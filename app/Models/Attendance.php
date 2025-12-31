<?php
// ===============================================
// File: app/Models/Attendance.php
// ===============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'tb_attendance';
    protected $primaryKey = 'attendance_id';

    protected $fillable = [
        'appointment_id',
        'student_id',
        'check_in_time',
        'check_out_time',
        'duration_minutes',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    /**
     * Get the appointment
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }

    /**
     * Get the student
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'user_id');
    }
}