<?php
// ===============================================
// File: app/Models/ProjectSubmission.php
// ===============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectSubmission extends Model
{
    protected $table = 'tb_project_submission';
    protected $primaryKey = 'submission_id';
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'student_id',
        'file_url',
        'file_name',
        'file_size',
        'submission_note',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    /**
     * Get the project
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    /**
     * Get the student
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'user_id');
    }
}