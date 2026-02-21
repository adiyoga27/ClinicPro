<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('photo_path')->after('blood_type')->nullable();
            $table->string('mother_name')->after('photo_path')->nullable();
            $table->string('mother_nik', 16)->after('mother_name')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['photo_path', 'mother_name', 'mother_nik']);
        });
    }
};
