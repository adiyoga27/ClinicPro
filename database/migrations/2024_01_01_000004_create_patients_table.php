<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->string('medical_record_no')->nullable();
            $table->string('nik', 16)->nullable();
            $table->string('name');
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->enum('blood_type', ['A', 'B', 'AB', 'O'])->nullable();
            $table->string('satu_sehat_patient_id')->nullable();
            $table->timestamps();

            $table->unique(['clinic_id', 'nik']);
            $table->unique(['clinic_id', 'medical_record_no']);
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
