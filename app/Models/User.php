<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Allowed roles: 'admin', 'patient', 'nurse', 'doctor'
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // Returns true if the user is an admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Returns true if the user is a patient
    public function isPatient(): bool
    {
        return $this->role === 'patient';
    }

    // Returns true if the user is a nurse
    public function isNurse(): bool
    {
        return $this->role === 'nurse';
    }

    // Returns true if the user is a doctor
    public function isDoctor(): bool
    {
        return $this->role === 'doctor';
    }

    // One user (patient) can have many appointments
    public function appointments()
    {
        return $this->hasMany(\App\Models\Appointment::class);
    }
}