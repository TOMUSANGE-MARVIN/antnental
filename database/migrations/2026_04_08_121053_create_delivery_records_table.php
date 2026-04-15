<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('delivery_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade'); // Admin who recorded this
            
            // Delivery Details
            $table->datetime('delivery_datetime');
            $table->enum('delivery_type', ['natural', 'cesarean', 'assisted', 'emergency_cesarean']);
            $table->enum('delivery_outcome', ['successful', 'complicated', 'maternal_death', 'infant_death', 'both_deaths']);
            $table->text('complications')->nullable();
            $table->boolean('surgery_performed')->default(false);
            $table->text('surgery_details')->nullable();
            
            // Baby/Babies Information
            $table->integer('number_of_babies')->default(1); // 1 = single, 2 = twins, 3 = triplets, etc.
            $table->enum('pregnancy_type', ['single', 'twins', 'triplets', 'quadruplets', 'other_multiple']);
            
            // Individual baby details (JSON for flexibility with multiples)
            $table->json('babies_details'); // [{"gender": "male", "weight": 3.2, "status": "alive", "complications": null}, ...]
            
            // Maternal Information
            $table->enum('maternal_status', ['alive_healthy', 'alive_complications', 'deceased']);
            $table->text('maternal_complications')->nullable();
            $table->datetime('maternal_death_time')->nullable();
            $table->text('maternal_death_cause')->nullable();
            
            // Medical Staff
            $table->string('attending_physician')->nullable();
            $table->string('midwife_nurse')->nullable();
            $table->text('medical_notes')->nullable();
            
            // Hospital/Location Details
            $table->string('delivery_location')->default('hospital');
            $table->string('ward_room')->nullable();
            
            // Follow-up Information
            $table->datetime('discharge_datetime')->nullable();
            $table->text('post_delivery_notes')->nullable();
            $table->boolean('requires_followup')->default(false);
            $table->datetime('next_appointment_date')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['patient_id', 'delivery_datetime']);
            $table->index('delivery_outcome');
            $table->index('maternal_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_records');
    }
};
