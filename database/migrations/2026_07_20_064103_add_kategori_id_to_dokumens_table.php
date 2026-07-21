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
        Schema::table('dokumens', function (Blueprint $table) {
            $table->foreignId('dokumen_kategori_id')->nullable()->constrained('dokumen_kategoris')->nullOnDelete()->after('id');
            $table->string('kategori')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->dropForeign(['dokumen_kategori_id']);
            $table->dropColumn('dokumen_kategori_id');
        });
    }
};
