<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'tb_appointment';
    protected $primaryKey = 'appointment_id';

    protected $fillable = [
        'classroom_id',
        'appointment_title',
        'description',
        'scheduled_date',
        'scheduled_time',
        'duration_minutes',
        'is_open',
        'appointment_number',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'is_open' => 'boolean',
    ];

    /**
     * Get the classroom
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'classroom_id');
    }

    /**
     * Get all materials
     */
    public function materials()
    {
        return $this->hasMany(Material::class, 'appointment_id', 'appointment_id');
    }

    /**
     * Get quiz
     */
    public function quiz()
    {
        return $this->hasOne(Quiz::class, 'appointment_id', 'appointment_id');
    }

    /**
     * Get report
     */
    public function report()
    {
        return $this->hasOne(TeacherReport::class, 'appointment_id', 'appointment_id');
    }
}

?>