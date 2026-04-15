<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'type',
        'status',
        'notes',
        'doctor_notes',
        'reminder_sent',
        'created_by',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'reminder_sent' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::created(function (Appointment $appointment): void {
            $appointment->scheduleReminderNotification();
        });
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeDisplayAttribute(): string
    {
        return match($this->type) {
            'first_visit' => 'First Visit',
            'follow_up' => 'Follow-Up',
            'routine_checkup' => 'Routine Checkup',
            'emergency' => 'Emergency',
            default => ucfirst($this->type),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    public function scheduleReminderNotification(): void
    {
        $this->loadMissing(['patient.user', 'doctor.user']);

        if ($this->reminder_sent) {
            return;
        }

        if (!in_array($this->status, ['pending', 'confirmed'], true)) {
            return;
        }

        if (!$this->patient?->user?->phone || !$this->appointment_date) {
            return;
        }

        if ($this->appointment_date->isPast()) {
            return;
        }

        $reminderTime = $this->appointment_date->copy()->subDay()->setTime(8, 0, 0);
        $scheduledAt = $reminderTime->lessThanOrEqualTo(now()) ? now() : $reminderTime;

        SmsNotification::createAppointmentReminder($this, $scheduledAt);

        // Mark as queued so we don't create duplicate reminder notifications.
        $this->forceFill(['reminder_sent' => true])->saveQuietly();
    }
}
