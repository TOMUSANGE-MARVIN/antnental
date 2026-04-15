<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::firstOrCreate(['email' => 'admin@antenatal.com'], [
            'name'      => 'Ssebuliba Ronald',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'phone'     => '+256 700 000 001',
            'is_active' => true,
        ]);

        // Create Doctor 1
        $doctorUser = User::firstOrCreate(['email' => 'doctor@antenatal.com'], [
            'name'      => 'Emily Chen',
            'password'  => Hash::make('password'),
            'role'      => 'doctor',
            'phone'     => '+256 701 234 567',
            'is_active' => true,
        ]);

        $doctor = Doctor::firstOrCreate(['user_id' => $doctorUser->id], [
            'specialization' => 'Obstetrics & Gynecology',
            'qualification'  => 'MBChB (Makerere), MMed OB-GYN, FCOS(ECSA)',
            'license_number' => 'UMDPC/2019/OBG/0041',
            'available_days' => 'Mon–Fri, 8:00 AM – 5:00 PM',
            'bio'            => 'Dr. Emily Chen is an experienced Obstetrician and Gynaecologist at Mulago National Referral Hospital, Kampala, with over 10 years of expertise in maternal-foetal medicine and antenatal care across Uganda.',
        ]);

        // Create Doctor 2
        $doctorUser2 = User::firstOrCreate(['email' => 'doctor2@antenatal.com'], [
            'name'      => 'Michael Osei',
            'password'  => Hash::make('password'),
            'role'      => 'doctor',
            'phone'     => '+256 702 345 678',
            'is_active' => true,
        ]);

        Doctor::firstOrCreate(['user_id' => $doctorUser2->id], [
            'specialization' => 'Obstetrics & Gynecology',
            'qualification'  => 'MBChB (Makerere), MRCOG (UK)',
            'license_number' => 'UMDPC/2021/OBG/0088',
            'available_days' => 'Mon, Wed, Fri, 8:00 AM – 4:00 PM',
            'bio'            => 'Dr. Michael Osei specialises in routine antenatal care, labour management, and postpartum support at Naguru Hospital, Kampala.',
        ]);

        // Create sample patient
        $patientUser = User::firstOrCreate(['email' => 'patient@antenatal.com'], [
            'name'      => 'Sarah Johnson',
            'password'  => Hash::make('password'),
            'role'      => 'patient',
            'phone'     => '+256 772 100 200',
            'is_active' => true,
        ]);

        $lmpDate = Carbon::now()->subWeeks(20);
        $eddDate = $lmpDate->copy()->addDays(280);

        $patient = Patient::firstOrCreate(['user_id' => $patientUser->id], [
            'date_of_birth'     => '1997-06-22',
            'lmp_date'          => $lmpDate->toDateString(),
            'edd_date'          => $eddDate->toDateString(),
            'blood_group'       => 'O+',
            'address'           => 'Makindye Division, Kampala',
            'emergency_contact' => 'Johnson Robert (Husband)',
            'emergency_phone'   => '+256 772 100 201',
            'gravida'           => 2,
            'para'              => 1,
        ]);

        // Additional sample patients
        $samplePatients = [
            ['name' => 'Nabirye Fatuma',   'email' => 'nabirye@example.com',   'phone' => '+256 775 300 100', 'dob' => '1999-03-10', 'weeks' => 16, 'blood' => 'A+',  'address' => 'Kawempe, Kampala',          'ec' => 'Nabirye Hamid (Husband)',    'ecph' => '+256 775 300 101', 'g' => 1, 'p' => 0],
            ['name' => 'Akello Sharon',    'email' => 'akello@example.com',    'phone' => '+256 778 400 200', 'dob' => '1994-11-05', 'weeks' => 28, 'blood' => 'B+',  'address' => 'Lira, Northern Uganda',     'ec' => 'Akello James (Husband)',     'ecph' => '+256 778 400 201', 'g' => 3, 'p' => 2],
            ['name' => 'Apio Christine',   'email' => 'apio@example.com',      'phone' => '+256 779 500 300', 'dob' => '2000-07-18', 'weeks' => 10, 'blood' => 'AB+', 'address' => 'Gulu, Northern Uganda',     'ec' => 'Apio Geoffrey (Husband)',    'ecph' => '+256 779 500 301', 'g' => 1, 'p' => 0],
            ['name' => 'Nakayima Rehema',  'email' => 'nakayima@example.com',  'phone' => '+256 701 600 400', 'dob' => '1996-01-30', 'weeks' => 34, 'blood' => 'O-',  'address' => 'Entebbe, Wakiso District',   'ec' => 'Nakayima Ali (Husband)',     'ecph' => '+256 701 600 401', 'g' => 2, 'p' => 1],
        ];

        foreach ($samplePatients as $sp) {
            $u = User::firstOrCreate(['email' => $sp['email']], [
                'name'      => $sp['name'],
                'password'  => Hash::make('password'),
                'role'      => 'patient',
                'phone'     => $sp['phone'],
                'is_active' => true,
            ]);
            $lmp = Carbon::now()->subWeeks($sp['weeks']);
            Patient::firstOrCreate(['user_id' => $u->id], [
                'date_of_birth'     => $sp['dob'],
                'lmp_date'          => $lmp->toDateString(),
                'edd_date'          => $lmp->copy()->addDays(280)->toDateString(),
                'blood_group'       => $sp['blood'],
                'address'           => $sp['address'],
                'emergency_contact' => $sp['ec'],
                'emergency_phone'   => $sp['ecph'],
                'gravida'           => $sp['g'],
                'para'              => $sp['p'],
            ]);
        }

        // Create a sample appointment
        Appointment::firstOrCreate([
            'patient_id' => $patient->id,
            'doctor_id'  => $doctor->id,
            'type'       => 'first_visit',
        ], [
            'appointment_date' => Carbon::now()->addDays(2)->toDateString(),
            'appointment_time' => '10:30:00',
            'status'           => 'confirmed',
            'notes'            => 'Okwakira okusooka (First antenatal visit). Omusawo asilikira ebirowoozo byonna.',
            'reminder_sent'    => false,
            'created_by'       => $patientUser->id,
        ]);

        // Run delivery record seeder
        $this->call(DeliveryRecordSeeder::class);

        $this->command->info('');
        $this->command->info('Seeding complete! Test accounts (Uganda context):');
        $this->command->info('Admin:    admin@antenatal.com / password  — Ssebuliba Ronald');
        $this->command->info('Doctor:   doctor@antenatal.com / password — Emily Chen');
        $this->command->info('Doctor2:  doctor2@antenatal.com / password — Michael Osei');
        $this->command->info('Patient:  patient@antenatal.com / password — Sarah Johnson');
    }
}
