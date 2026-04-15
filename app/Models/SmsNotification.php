<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SmsNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'message',
        'type',
        'status',
        'external_id',
        'response_data',
        'error_message',
        'retry_count',
        'scheduled_at',
        'sent_at',
        'delivered_at',
        'notifiable_id',
        'notifiable_type'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'response_data' => 'array'
    ];

    /**
     * Get the notifiable model (appointment, delivery, etc.)
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    /**
     * Scope for pending notifications
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for failed notifications
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for notifications ready to send
     */
    public function scopeReadyToSend($query)
    {
        return $query->where('status', 'pending')
                    ->where(function ($q) {
                        $q->whereNull('scheduled_at')
                          ->orWhere('scheduled_at', '<=', now());
                    });
    }

    /**
     * Scope for retryable failed notifications
     */
    public function scopeRetryable($query)
    {
        return $query->where('status', 'failed')
                    ->where('retry_count', '<', 3);
    }

    /**
     * Mark as sent
     */
    public function markAsSent($externalId = null, $responseData = null)
    {
        $this->update([
            'status' => 'sent',
            'external_id' => $externalId,
            'response_data' => $responseData,
            'sent_at' => now()
        ]);
    }

    /**
     * Mark as failed
     */
    public function markAsFailed($errorMessage = null, $responseData = null)
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
            'response_data' => $responseData,
            'retry_count' => $this->retry_count + 1
        ]);
    }

    /**
     * Mark as delivered
     */
    public function markAsDelivered()
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now()
        ]);
    }

    /**
     * Format phone number for Uganda
     */
    public static function formatUgandaPhone($phone)
    {
        // Remove spaces, hyphens, and plus signs
        $phone = preg_replace('/[\s\-\+]/', '', $phone);
        
        // Convert to international format
        if (strlen($phone) === 9 && substr($phone, 0, 1) === '7') {
            return '+256' . $phone;
        } elseif (strlen($phone) === 10 && substr($phone, 0, 1) === '0') {
            return '+256' . substr($phone, 1);
        } elseif (strlen($phone) === 13 && substr($phone, 0, 4) === '256') {
            return '+' . $phone;
        } elseif (strlen($phone) === 12 && substr($phone, 0, 3) === '256') {
            return '+' . $phone;
        }
        
        return $phone; // Return as-is if format not recognized
    }

    /**
     * Create appointment reminder notification
     */
    public static function createAppointmentReminder($appointment, $scheduledAt = null)
    {
        $phone = self::formatUgandaPhone($appointment->patient->user->phone);
        $message = self::generateAppointmentReminderMessage($appointment);

        return self::create([
            'phone_number' => $phone,
            'message' => $message,
            'type' => 'appointment_reminder',
            'scheduled_at' => $scheduledAt,
            'notifiable_id' => $appointment->id,
            'notifiable_type' => get_class($appointment)
        ]);
    }

    /**
     * Generate appointment reminder message
     */
    private static function generateAppointmentReminderMessage($appointment)
    {
        $patientName = $appointment->patient->user->name;
        $doctorName = $appointment->doctor->user->name;
        $appointmentDate = $appointment->appointment_date;
        $appointmentTime = Carbon::parse($appointment->appointment_time)->format('g:i A');
        
        return "Dear {$patientName}, this is a reminder of your antenatal appointment with {$doctorName} on {$appointmentDate} at {$appointmentTime}. Please arrive 15 minutes early. For any changes, call the clinic. Thank you.";
    }
}
