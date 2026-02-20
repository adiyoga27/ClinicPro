<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->decimal('height', 5, 1)->nullable()->after('plan')->comment('cm');
            $table->decimal('weight', 5, 1)->nullable()->after('height')->comment('kg');
            $table->integer('blood_pressure_systolic')->nullable()->after('weight')->comment('mmHg');
            $table->integer('blood_pressure_diastolic')->nullable()->after('blood_pressure_systolic')->comment('mmHg');
            $table->decimal('temperature', 4, 1)->nullable()->after('blood_pressure_diastolic')->comment('°C');
            $table->integer('heart_rate')->nullable()->after('temperature')->comment('bpm');
            $table->integer('respiratory_rate')->nullable()->after('heart_rate')->comment('breaths/min');
            $table->integer('spo2')->nullable()->after('respiratory_rate')->comment('%');
            $table->text('allergy_notes')->nullable()->after('spo2');
        });
    }

    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropColumn([
                'height',
                'weight',
                'blood_pressure_systolic',
                'blood_pressure_diastolic',
                'temperature',
                'heart_rate',
                'respiratory_rate',
                'spo2',
                'allergy_notes',
            ]);
        });
    }
};
