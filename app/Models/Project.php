<?php
// ===============================================
// File: app/Models/Project.php
// ===============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'tb_project';
    protected $primaryKey = 'project_id';

    protected $fillable = [
        'appointment_id',
        'project_title',
        'description',
        'due_date',
        'is_active',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the appointment
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }

    /**
     * Get submissions
     */
    public function submissions()
    {
        return $this->hasMany(ProjectSubmission::class, 'project_id', 'project_id');
    }
}