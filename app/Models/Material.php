<?php

// ===============================================
// File: app/Models/Material.php
// ===============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'tb_material';
    protected $primaryKey = 'material_id';

    protected $fillable = [
        'appointment_id',
        'material_title',
        'material_type',
        'file_url',
        'description',
        'order_number',
    ];

    /**
     * Get the appointment
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }
}