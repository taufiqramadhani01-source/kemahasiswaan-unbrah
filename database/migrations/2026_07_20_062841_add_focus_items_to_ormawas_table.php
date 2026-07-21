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
        Schema::table('ormawas', function (Blueprint $table) {
            $table->json('focus_items')->nullable()->after('description');
            $table->unsignedSmallInteger('order')->default(0)->after('focus_items');
        });
    }

    public function down(): void
    {
        Schema::table('ormawas', function (Blueprint $table) {
            $table->dropColumn(['focus_items', 'order']);
        });
    }
};
