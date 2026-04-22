<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'doctor_name',
        'appointment_date',
        'appointment_time',
        'reason',
        'status',
        'notes',
        'actioned_by_nurse_id', // ID of the nurse who confirmed or cancelled
        'actioned_at',          // Timestamp of when the nurse actioned it
    ];

    protected $casts = [
        'actioned_at' => 'datetime', // Cast to Carbon so ->format() works in Blade
    ];

    // Each appointment belongs to one patient (the user who booked it)
    public function patient()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Each appointment may have been actioned by one nurse
    // Returns null if no nurse has actioned this appointment yet
    public function nurse()
    {
        return $this->belongsTo(User::class, 'actioned_by_nurse_id');
    }
}