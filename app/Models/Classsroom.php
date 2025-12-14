<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $table = 'tb_classroom';
    protected $primaryKey = 'classroom_id';

    protected $fillable = [
        'created_by_admin_id',
        'teacher_id',
        'classroom_name',
        'description',
        'access_token',
        'token_is_active',
    ];

    protected $casts = [
        'token_is_active' => 'boolean',
    ];

    /**
     * Get the admin who created the classroom
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'created_by_admin_id', 'user_id');
    }

    /**
     * Get the assigned teacher
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'user_id');
    }

    /**
     * Get all enrollments
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'classroom_id', 'classroom_id');
    }

    /**
     * Get all appointments
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'classroom_id', 'classroom_id');
    }

    /**
     * Get enrolled students count
     */
    public function getStudentCountAttribute()
    {
        return $this->enrollments()->where('status', 'active')->count();
    }
}

?>