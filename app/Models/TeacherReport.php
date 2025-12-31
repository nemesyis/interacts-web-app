<?php
// ===============================================
// File: app/Models/TeacherReport.php
// ===============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherReport extends Model
{
    protected $table = 'tb_teacher_report';
    protected $primaryKey = 'report_id';

    protected $fillable = [
        'appointment_id',
        'teacher_id',
        'report_title',
        'report_content',
    ];

    /**
     * Get the appointment
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }

    /**
     * Get the teacher
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'user_id');
    }
}