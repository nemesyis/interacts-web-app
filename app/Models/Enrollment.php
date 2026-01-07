<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $table = 'tb_enrollment';
    protected $primaryKey = 'enrollment_id';
    public $timestamps = false;

    protected $fillable = [
        'classroom_id',
        'student_id',
        'status',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
    ];

    /**
     * Get the classroom
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'classroom_id');
    }

    /**
     * Get the student
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'user_id');
    }
}

?>