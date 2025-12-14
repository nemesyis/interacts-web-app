<?php

// ===============================================
// File: app/Models/TeacherInvitation.php
// ===============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherInvitation extends Model
{
    protected $table = 'tb_teacher_invitation';
    protected $primaryKey = 'invitation_id';

    protected $fillable = [
        'invited_by_admin_id',
        'teacher_email',
        'teacher_full_name',
        'teacher_username',
        'temp_password_hash',
        'invitation_token',
        'status',
        'expires_at',
        'accepted_at',
        'resent_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
        'resent_at' => 'datetime',
    ];

    /**
     * Get the admin who sent the invitation
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'invited_by_admin_id', 'user_id');
    }

    /**
     * Check if invitation is expired
     */
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if invitation is pending
     */
    public function isPending()
    {
        return $this->status === 'pending' || $this->status === 'resent';
    }
}