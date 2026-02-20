<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('prescription_items', function (Blueprint $table) {
            $table->foreignId('medicine_id')->nullable()->after('prescription_id')->constrained()->nullOnDelete();
            $table->decimal('price', 12, 2)->default(0)->after('duration');
            $table->integer('qty')->default(1)->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('prescription_items', function (Blueprint $table) {
            $table->dropForeign(['medicine_id']);
            $table->dropColumn(['medicine_id', 'price', 'qty']);
        });
    }
};
