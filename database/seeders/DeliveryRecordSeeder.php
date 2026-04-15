<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeliveryRecord;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\User;
use Carbon\Carbon;

class DeliveryRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get available patients and doctors
        $patients = Patient::all();
        $doctors = Doctor::all();
        $admin = User::where('role', 'admin')->first();

        if ($patients->isEmpty() || $doctors->isEmpty() || !$admin) {
            $this->command->warn('No patients, doctors, or admin found. Please run basic seeders first.');
            return;
        }

        $deliveryTypes = ['natural', 'cesarean', 'assisted', 'emergency_cesarean'];
        $outcomes = ['successful', 'complicated', 'maternal_death', 'infant_death'];
        $pregnancyTypes = ['single', 'twins', 'triplets'];
        $maternalStatuses = ['alive_healthy', 'alive_complications', 'deceased'];
        $genders = ['male', 'female'];
        $babyStatuses = ['alive', 'deceased', 'stillborn'];

        // Sample delivery records
        $deliveryData = [
            // Successful single birth
            [
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'admin_id' => $admin->id,
                'delivery_datetime' => Carbon::now()->subDays(rand(1, 30)),
                'delivery_type' => 'natural',
                'delivery_outcome' => 'successful',
                'complications' => null,
                'surgery_performed' => false,
                'surgery_details' => null,
                'number_of_babies' => 1,
                'pregnancy_type' => 'single',
                'babies_details' => json_encode([
                    [
                        'gender' => 'female',
                        'weight' => 3.2,
                        'length' => 48,
                        'status' => 'alive',
                        'birth_time' => Carbon::now()->subDays(rand(1, 30))->format('H:i:s'),
                        'complications' => null
                    ]
                ]),
                'maternal_status' => 'alive_healthy',
                'maternal_complications' => null,
                'attending_physician' => 'Dr. Emily Chen',
                'midwife_nurse' => 'Nurse Sarah',
                'medical_notes' => 'Normal delivery, no complications. Mother and baby healthy.',
                'delivery_location' => 'hospital',
                'ward_room' => 'Maternity Ward A-101',
                'discharge_datetime' => Carbon::now()->subDays(rand(1, 30))->addDays(2),
                'requires_followup' => true,
                'next_appointment_date' => Carbon::now()->addWeeks(2),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Emergency C-section
            [
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'admin_id' => $admin->id,
                'delivery_datetime' => Carbon::now()->subDays(rand(31, 60)),
                'delivery_type' => 'emergency_cesarean',
                'delivery_outcome' => 'successful',
                'complications' => 'Fetal distress detected during labor',
                'surgery_performed' => true,
                'surgery_details' => 'Emergency C-section performed due to fetal distress. General anesthesia administered.',
                'number_of_babies' => 1,
                'pregnancy_type' => 'single',
                'babies_details' => json_encode([
                    [
                        'gender' => 'male',
                        'weight' => 3.8,
                        'length' => 51,
                        'status' => 'alive',
                        'birth_time' => Carbon::now()->subDays(rand(31, 60))->format('H:i:s'),
                        'complications' => 'Mild respiratory distress, resolved quickly'
                    ]
                ]),
                'maternal_status' => 'alive_healthy',
                'maternal_complications' => 'Post-operative recovery normal',
                'attending_physician' => 'Dr. Michael Osei',
                'midwife_nurse' => 'Nurse Rebecca',
                'medical_notes' => 'Emergency cesarean section performed successfully. Mother and baby both stable.',
                'delivery_location' => 'hospital',
                'ward_room' => 'Operating Theater 2',
                'discharge_datetime' => Carbon::now()->subDays(rand(31, 60))->addDays(4),
                'requires_followup' => true,
                'next_appointment_date' => Carbon::now()->addWeeks(1),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Twin delivery
            [
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'admin_id' => $admin->id,
                'delivery_datetime' => Carbon::now()->subDays(rand(15, 45)),
                'delivery_type' => 'cesarean',
                'delivery_outcome' => 'successful',
                'complications' => null,
                'surgery_performed' => true,
                'surgery_details' => 'Planned C-section for twin delivery at 37 weeks',
                'number_of_babies' => 2,
                'pregnancy_type' => 'twins',
                'babies_details' => json_encode([
                    [
                        'gender' => 'female',
                        'weight' => 2.8,
                        'length' => 45,
                        'status' => 'alive',
                        'birth_time' => Carbon::now()->subDays(rand(15, 45))->format('H:i:s'),
                        'complications' => null
                    ],
                    [
                        'gender' => 'male',
                        'weight' => 2.9,
                        'length' => 46,
                        'status' => 'alive',
                        'birth_time' => Carbon::now()->subDays(rand(15, 45))->addMinutes(2)->format('H:i:s'),
                        'complications' => null
                    ]
                ]),
                'maternal_status' => 'alive_healthy',
                'maternal_complications' => null,
                'attending_physician' => 'Dr. Emily Chen',
                'midwife_nurse' => 'Nurse Mary, Nurse Grace',
                'medical_notes' => 'Twin delivery via planned cesarean section. Both babies and mother are healthy.',
                'delivery_location' => 'hospital',
                'ward_room' => 'Operating Theater 1',
                'discharge_datetime' => Carbon::now()->subDays(rand(15, 45))->addDays(5),
                'requires_followup' => true,
                'next_appointment_date' => Carbon::now()->addWeeks(2),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Complicated delivery with premature birth
            [
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'admin_id' => $admin->id,
                'delivery_datetime' => Carbon::now()->subDays(rand(5, 20)),
                'delivery_type' => 'natural',
                'delivery_outcome' => 'complicated',
                'complications' => 'Premature labor at 34 weeks, postpartum bleeding',
                'surgery_performed' => false,
                'surgery_details' => null,
                'number_of_babies' => 1,
                'pregnancy_type' => 'single',
                'babies_details' => json_encode([
                    [
                        'gender' => 'female',
                        'weight' => 2.1,
                        'length' => 42,
                        'status' => 'alive',
                        'birth_time' => Carbon::now()->subDays(rand(5, 20))->format('H:i:s'),
                        'complications' => 'Premature birth, respiratory support required, NICU admission'
                    ]
                ]),
                'maternal_status' => 'alive_complications',
                'maternal_complications' => 'Postpartum bleeding (controlled), required blood transfusion',
                'attending_physician' => 'Dr. Michael Osei',
                'midwife_nurse' => 'Nurse Patricia',
                'medical_notes' => 'Premature delivery at 34 weeks. Baby required NICU care but stabilized. Mother had significant bleeding but recovered.',
                'delivery_location' => 'hospital',
                'ward_room' => 'Maternity Ward B-205',
                'discharge_datetime' => Carbon::now()->subDays(rand(5, 20))->addDays(7),
                'requires_followup' => true,
                'next_appointment_date' => Carbon::now()->addDays(3),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Stillbirth case
            [
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'admin_id' => $admin->id,
                'delivery_datetime' => Carbon::now()->subDays(rand(60, 90)),
                'delivery_type' => 'natural',
                'delivery_outcome' => 'infant_death',
                'complications' => 'Cord around neck, severe fetal distress',
                'surgery_performed' => false,
                'surgery_details' => null,
                'number_of_babies' => 1,
                'pregnancy_type' => 'single',
                'babies_details' => json_encode([
                    [
                        'gender' => 'male',
                        'weight' => 1.8,
                        'length' => 38,
                        'status' => 'stillborn',
                        'birth_time' => Carbon::now()->subDays(rand(60, 90))->format('H:i:s'),
                        'complications' => 'Cord around neck, severe fetal distress, could not be resuscitated'
                    ]
                ]),
                'maternal_status' => 'alive_healthy',
                'maternal_complications' => null,
                'attending_physician' => 'Dr. Emily Chen',
                'midwife_nurse' => 'Nurse Susan',
                'medical_notes' => 'Stillbirth due to cord complications. Grief counseling provided to family. No maternal complications.',
                'delivery_location' => 'hospital',
                'ward_room' => 'Maternity Ward A-103',
                'discharge_datetime' => Carbon::now()->subDays(rand(60, 90))->addDays(2),
                'requires_followup' => true,
                'next_appointment_date' => Carbon::now()->addWeeks(1),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        // Insert the predefined records
        foreach ($deliveryData as $record) {
            DeliveryRecord::create($record);
        }

        // Generate additional random records
        for ($i = 0; $i < 10; $i++) {
            $numBabies = rand(1, 2); // Mostly single births, some twins
            $pregnancyType = $numBabies === 1 ? 'single' : 'twins';
            $babies = [];

            $deliveryOutcome = 'successful';
            $maternalStatus = 'alive_healthy';
            
            // 10% chance of complications
            if (rand(1, 10) === 1) {
                $deliveryOutcome = 'complicated';
                $maternalStatus = 'alive_complications';
            }
            
            // 1% chance of infant death
            if (rand(1, 100) === 1) {
                $deliveryOutcome = 'infant_death';
            }

            for ($j = 0; $j < $numBabies; $j++) {
                $status = 'alive';
                if ($deliveryOutcome === 'infant_death' && $j === 0) {
                    $status = rand(1, 2) === 1 ? 'stillborn' : 'deceased';
                }

                $babies[] = [
                    'gender' => $genders[array_rand($genders)],
                    'weight' => round(rand(180, 420) / 100, 1), // 1.8 to 4.2 kg
                    'length' => rand(38, 55), // 38 to 55 cm
                    'status' => $status,
                    'birth_time' => Carbon::now()->subDays(rand(1, 180))->addMinutes($j * 3)->format('H:i:s'),
                    'complications' => rand(1, 5) === 1 ? 'Minor complications noted' : null
                ];
            }

            $deliveryType = $deliveryTypes[array_rand($deliveryTypes)];
            $surgeryPerformed = in_array($deliveryType, ['cesarean', 'emergency_cesarean']);
            
            DeliveryRecord::create([
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'admin_id' => $admin->id,
                'delivery_datetime' => Carbon::now()->subDays(rand(1, 180)),
                'delivery_type' => $deliveryType,
                'delivery_outcome' => $deliveryOutcome,
                'complications' => $deliveryOutcome === 'complicated' ? 'Various complications noted' : null,
                'surgery_performed' => $surgeryPerformed,
                'surgery_details' => $surgeryPerformed ? ucfirst($deliveryType) . ' procedure performed' : null,
                'number_of_babies' => $numBabies,
                'pregnancy_type' => $pregnancyType,
                'babies_details' => json_encode($babies),
                'maternal_status' => $maternalStatus,
                'maternal_complications' => $maternalStatus === 'alive_complications' ? 'Post-delivery complications' : null,
                'attending_physician' => $doctors->random()->user->name,
                'midwife_nurse' => 'Nurse ' . ['Mary', 'Grace', 'Sarah', 'Rebecca', 'Patricia'][array_rand(['Mary', 'Grace', 'Sarah', 'Rebecca', 'Patricia'])],
                'medical_notes' => 'Standard delivery procedure completed.',
                'delivery_location' => 'hospital',
                'ward_room' => 'Ward ' . chr(rand(65, 67)) . '-' . rand(101, 210),
                'discharge_datetime' => Carbon::now()->subDays(rand(1, 180))->addDays(rand(2, 5)),
                'requires_followup' => rand(1, 3) === 1,
                'next_appointment_date' => rand(1, 3) === 1 ? Carbon::now()->addWeeks(rand(1, 4)) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Created ' . DeliveryRecord::count() . ' delivery records successfully!');
    }
}
