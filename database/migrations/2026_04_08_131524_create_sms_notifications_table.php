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
        Schema::create('sms_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number');
            $table->string('message', 1000);
            $table->enum('type', ['appointment_reminder', 'delivery_notification', 'general'])->default('general');
            $table->enum('status', ['pending', 'sent', 'failed', 'delivered'])->default('pending');
            $table->string('external_id')->nullable(); // Africa's Talking message ID
            $table->text('response_data')->nullable(); // Full API response
            $table->text('error_message')->nullable();
            $table->integer('retry_count')->default(0);
            $table->datetime('scheduled_at')->nullable();
            $table->datetime('sent_at')->nullable();
            $table->datetime('delivered_at')->nullable();
            
            // Polymorphic relationship - can link to appointment, delivery, etc.
            $table->unsignedBigInteger('notifiable_id')->nullable();
            $table->string('notifiable_type')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['phone_number', 'status']);
            $table->index(['type', 'status']);
            $table->index(['scheduled_at', 'status']);
            $table->index(['notifiable_type', 'notifiable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_notifications');
    }
};
