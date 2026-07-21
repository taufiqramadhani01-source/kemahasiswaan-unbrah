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
        Schema::create('dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('kategori');           // slug: hasimaru, saps, peraturan-ormawa, sop, sk-ormawa
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('url_file')->nullable(); // link Google Drive, atau path upload
            $table->string('tipe_file')->default('pdf'); // pdf | link | docx | xlsx
            $table->string('ukuran_file')->nullable(); // e.g. "4.8 MB"
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumens');
    }
};
