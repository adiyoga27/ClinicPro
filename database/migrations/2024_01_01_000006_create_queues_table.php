<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clinic_queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('queue_no');
            $table->date('date');
            $table->enum('status', ['waiting', 'in_progress', 'completed', 'skipped'])->default('waiting');
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamps();

            $table->index(['clinic_id', 'date']);
            $table->unique(['clinic_id', 'queue_no', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinic_queues');
    }
};
