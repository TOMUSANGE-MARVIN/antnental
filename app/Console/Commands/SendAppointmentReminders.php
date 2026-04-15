<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\SmsNotification;
use App\Services\SmsService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders {--days=1 : Number of days in advance to send reminders} {--immediate : Send immediately instead of scheduling}';
    protected $description = 'Send SMS reminders for appointments scheduled in advance';

    public function handle(SmsService $sms): int
    {
        $daysInAdvance = (int) $this->option('days');
        $immediate = $this->option('immediate');
        $targetAppointmentDate = Carbon::today()->addDays($daysInAdvance);

        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->whereDate('appointment_date', $targetAppointmentDate->toDateString())
            ->where('reminder_sent', false)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        if ($appointments->isEmpty()) {
            $this->info("No appointments found for {$targetAppointmentDate->toDateString()}. No reminders to send.");
            return self::SUCCESS;
        }

        $this->info("Found {$appointments->count()} appointments for {$targetAppointmentDate->toDateString()}");

        $sent = 0;
        $failed = 0;
        $scheduled = 0;
        $now = now();

        foreach ($appointments as $appointment) {
            $patient = $appointment->patient;
            $patientUser = $patient->user;

            if (!$patientUser->phone) {
                $this->warn("Patient {$patientUser->name} has no phone number. Skipping.");
                continue;
            }

            try {
                // Reminder should be sent at 8 AM, X days before appointment.
                $reminderTime = Carbon::parse($appointment->appointment_date)
                    ->subDays($daysInAdvance)
                    ->setTime(8, 0, 0);

                if ($immediate || $reminderTime->lessThanOrEqualTo($now)) {
                    $result = $sms->sendAppointmentReminder($appointment);
                    if ($result) {
                        $appointment->update(['reminder_sent' => true]);
                        $sent++;
                        $this->info("✓ Reminder sent to {$patientUser->name} ({$patientUser->phone})");
                    } else {
                        $failed++;
                        $this->error("✗ Failed to send reminder to {$patientUser->name}");
                    }
                } else {
                    SmsNotification::createAppointmentReminder($appointment, $reminderTime);

                    $appointment->update(['reminder_sent' => true]);
                    $scheduled++;
                    $this->info("📅 Reminder scheduled for {$patientUser->name} at {$reminderTime->format('M j, Y g:i A')}");
                }
            } catch (\Exception $e) {
                $failed++;
                $this->error("✗ Error processing {$patientUser->name}: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info("Reminder run complete. Sent: {$sent}, Scheduled: {$scheduled}, Failed: {$failed}");
        if ($scheduled > 0) {
            $this->comment("Scheduled SMS will be sent by the SMS processor.");
        }

        return self::SUCCESS;
    }
}
