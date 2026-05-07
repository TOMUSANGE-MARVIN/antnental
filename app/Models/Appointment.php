<?php

namespace App\Models;

use App\Services\SmsService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    public const BOOKING_REASONS = [
        'routine_antenatal_checkup' => 'Routine antenatal check-up',
        'first_trimester_screening' => 'First trimester screening',
        'high_risk_pregnancy_review' => 'High-risk pregnancy review',
        'ultrasound_scan_review' => 'Ultrasound scan review',
        'pregnancy_symptom_concern' => 'Pregnancy symptom concern',
        'nutrition_and_supplement_counselling' => 'Nutrition and supplement counselling',
        'birth_plan_counselling' => 'Birth plan counselling',
        'other' => 'Other',
    ];

    public const REASON_SPECIALIZATION_KEYWORDS = [
        'routine_antenatal_checkup' => ['obstetric', 'gyne', 'maternal', 'antenatal'],
        'first_trimester_screening' => ['obstetric', 'gyne', 'maternal', 'fetal', 'antenatal'],
        'high_risk_pregnancy_review' => ['maternal', 'fetal', 'obstetric', 'high-risk'],
        'ultrasound_scan_review' => ['obstetric', 'fetal', 'ultrasound', 'maternal'],
        'pregnancy_symptom_concern' => ['obstetric', 'gyne', 'maternal', 'antenatal'],
        'nutrition_and_supplement_counselling' => ['obstetric', 'gyne', 'maternal', 'nutrition'],
        'birth_plan_counselling' => ['obstetric', 'maternal', 'delivery', 'labour'],
    ];

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'type',
        'visit_reason',
        'other_reason',
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

        static::updated(function (Appointment $appointment): void {
            if (
                $appointment->wasChanged('doctor_id')
                && blank($appointment->getOriginal('doctor_id'))
                && filled($appointment->doctor_id)
            ) {
                $appointment->scheduleReminderNotification();
            }
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

    public function getVisitReasonDisplayAttribute(): string
    {
        return self::BOOKING_REASONS[$this->visit_reason] ?? ucfirst(str_replace('_', ' ', (string) $this->visit_reason));
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

        if (!$this->doctor?->user?->name) {
            return;
        }

        if ($this->appointment_date->isPast()) {
            return;
        }

        $now = now();
        $reminderTime = $this->appointment_date->copy()->subDay()->setTime(8, 0, 0);
        $scheduledAt = $reminderTime->lessThanOrEqualTo($now) ? $now : $reminderTime;

        $notification = SmsNotification::createAppointmentReminder($this, $scheduledAt);

        if ($scheduledAt->lessThanOrEqualTo($now)) {
            app(SmsService::class)->processSmsNotification($notification);
        }

        // Mark as queued so we don't create duplicate reminder notifications.
        $this->forceFill(['reminder_sent' => true])->saveQuietly();
    }
}
