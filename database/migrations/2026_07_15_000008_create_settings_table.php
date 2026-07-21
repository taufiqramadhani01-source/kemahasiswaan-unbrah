<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();   // e.g. 'color_primary'
            $table->text('value')->nullable();  // e.g. '#052c22'
            $table->string('group')->default('general'); // 'colors' | 'general'
            $table->string('label')->nullable(); // Human-readable label
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
