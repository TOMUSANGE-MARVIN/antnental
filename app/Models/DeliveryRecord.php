<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id', 
        'admin_id',
        'delivery_datetime',
        'delivery_type',
        'delivery_outcome',
        'complications',
        'surgery_performed',
        'surgery_details',
        'number_of_babies',
        'pregnancy_type',
        'babies_details',
        'maternal_status',
        'maternal_complications',
        'maternal_death_time',
        'maternal_death_cause',
        'attending_physician',
        'midwife_nurse',
        'medical_notes',
        'delivery_location',
        'ward_room',
        'discharge_datetime',
        'post_delivery_notes',
        'requires_followup',
        'next_appointment_date'
    ];

    protected $casts = [
        'delivery_datetime' => 'datetime',
        'maternal_death_time' => 'datetime',
        'discharge_datetime' => 'datetime',
        'next_appointment_date' => 'datetime',
        'surgery_performed' => 'boolean',
        'requires_followup' => 'boolean',
        'babies_details' => 'array',
        'number_of_babies' => 'integer'
    ];

    // Relationships
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Accessors and Mutators
    public function getFormattedDeliveryDateAttribute(): string
    {
        return $this->delivery_datetime->format('M j, Y g:i A');
    }

    public function getDeliveryTypeDisplayAttribute(): string
    {
        return match($this->delivery_type) {
            'natural' => 'Natural Delivery',
            'cesarean' => 'Cesarean Section',
            'assisted' => 'Assisted Delivery',
            'emergency_cesarean' => 'Emergency C-Section',
            default => ucfirst(str_replace('_', ' ', $this->delivery_type))
        };
    }

    public function getDeliveryOutcomeDisplayAttribute(): string
    {
        return match($this->delivery_outcome) {
            'successful' => 'Successful',
            'complicated' => 'Complicated',
            'maternal_death' => 'Maternal Death',
            'infant_death' => 'Infant Death',
            'both_deaths' => 'Both Deaths',
            default => ucfirst(str_replace('_', ' ', $this->delivery_outcome))
        };
    }

    public function getPregnancyTypeDisplayAttribute(): string
    {
        return ucfirst($this->pregnancy_type);
    }

    public function getMaternalStatusDisplayAttribute(): string
    {
        return match($this->maternal_status) {
            'alive_healthy' => 'Alive & Healthy',
            'alive_complications' => 'Alive with Complications',
            'deceased' => 'Deceased',
            default => ucfirst(str_replace('_', ' ', $this->maternal_status))
        };
    }

    // Helper Methods
    public function getTotalLiveBabies(): int
    {
        if (!$this->babies_details || !is_array($this->babies_details)) {
            return 0;
        }

        return collect($this->babies_details)->where('status', 'alive')->count();
    }

    public function getTotalDeceasedBabies(): int
    {
        if (!$this->babies_details || !is_array($this->babies_details)) {
            return 0;
        }

        return collect($this->babies_details)->where('status', 'deceased')->count();
    }

    public function getGenderBreakdown(): array
    {
        if (!$this->babies_details || !is_array($this->babies_details)) {
            return ['male' => 0, 'female' => 0];
        }

        $breakdown = collect($this->babies_details)
            ->where('status', 'alive')
            ->countBy('gender');

        return [
            'male' => $breakdown->get('male', 0),
            'female' => $breakdown->get('female', 0)
        ];
    }

    public function isSuccessful(): bool
    {
        return $this->delivery_outcome === 'successful';
    }

    public function hasMaternalComplications(): bool
    {
        return $this->maternal_status === 'alive_complications' || $this->maternal_status === 'deceased';
    }

    public function hasInfantDeaths(): bool
    {
        return in_array($this->delivery_outcome, ['infant_death', 'both_deaths']) || 
               $this->getTotalDeceasedBabies() > 0;
    }

    // Scopes
    public function scopeSuccessful($query)
    {
        return $query->where('delivery_outcome', 'successful');
    }

    public function scopeWithComplications($query)
    {
        return $query->whereIn('delivery_outcome', ['complicated', 'maternal_death', 'infant_death', 'both_deaths']);
    }

    public function scopeRecentDeliveries($query, $days = 30)
    {
        return $query->where('delivery_datetime', '>=', now()->subDays($days));
    }

    public function scopeByDeliveryType($query, $type)
    {
        return $query->where('delivery_type', $type);
    }
}
