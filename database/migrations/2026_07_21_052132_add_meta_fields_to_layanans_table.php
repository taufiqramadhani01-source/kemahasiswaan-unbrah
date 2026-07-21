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
        Schema::table('layanans', function (Blueprint $table) {
            $table->string('category')->nullable()->default('Kemahasiswaan Universitas Baiturrahmah')->after('whatsapp_number');
            $table->string('fee')->nullable()->default('Gratis Bagi Mahasiswa Aktif UNBRAH')->after('category');
        });
    }

    public function down(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            $table->dropColumn(['category', 'fee']);
        });
    }
};
