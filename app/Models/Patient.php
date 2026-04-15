<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date_of_birth',
        'lmp_date',
        'edd_date',
        'blood_group',
        'address',
        'emergency_contact',
        'emergency_phone',
        'gravida',
        'para',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'lmp_date' => 'date',
        'edd_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the delivery records for this patient.
     */
    public function deliveryRecords()
    {
        return $this->hasMany(DeliveryRecord::class);
    }

    /**
     * Get the latest delivery record for this patient.
     */
    public function latestDelivery()
    {
        return $this->hasOne(DeliveryRecord::class)->latest('delivery_datetime');
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'appointments')->distinct();
    }

    public function getWeeksPregnantAttribute()
    {
        if (!$this->lmp_date) return null;
        return (int) now()->diffInWeeks($this->lmp_date);
    }

    public function getDaysUntilEddAttribute()
    {
        if (!$this->edd_date) return null;
        return now()->diffInDays($this->edd_date, false);
    }

    public function getTrimesterAttribute()
    {
        $weeks = $this->weeks_pregnant;
        if ($weeks === null) return null;
        if ($weeks <= 12) return '1st Trimester';
        if ($weeks <= 27) return '2nd Trimester';
        return '3rd Trimester';
    }
}
