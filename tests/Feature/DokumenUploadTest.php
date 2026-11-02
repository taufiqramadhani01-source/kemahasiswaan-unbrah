<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DokumenKategori;
use App\Models\Dokumen;
use App\Livewire\Admin\ManageDokumens;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class DokumenUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_dokumen_file_upload_and_save(): void
    {
        $this->seed();
        Storage::fake('public');

        $admin = User::where('role', 'superadmin')->first();
        $kategori = DokumenKategori::first();

        $file = UploadedFile::fake()->create('struktur_organisasi.pdf', 1500, 'application/pdf');

        $component = Livewire::actingAs($admin)
            ->test(ManageDokumens::class)
            ->call('openForm')
            ->set('dokumen_kategori_id', $kategori->id)
            ->set('uploaded_file', $file)
            ->assertSet('tipe_file', 'pdf')
            ->assertSet('ukuran_file', '1.5 MB')
            ->assertSet('judul', 'Struktur Organisasi')
            ->call('save')
            ->assertHasNoErrors();

        $dokumen = Dokumen::where('judul', 'Struktur Organisasi')->first();
        $this->assertNotNull($dokumen);
        $this->assertEquals('pdf', $dokumen->tipe_file);
        $this->assertEquals('1.5 MB', $dokumen->ukuran_file);
        $this->assertStringStartsWith('/storage/dokumens/', $dokumen->url_file);
        $this->assertEquals($kategori->slug, $dokumen->kategori);
    }

    public function test_can_remove_uploaded_file(): void
    {
        $this->seed();
        $admin = User::where('role', 'superadmin')->first();
        $file = UploadedFile::fake()->create('test.docx', 500, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

        Livewire::actingAs($admin)
            ->test(ManageDokumens::class)
            ->call('openForm')
            ->set('uploaded_file', $file)
            ->assertSet('tipe_file', 'docx')
            ->call('removeUploadedFile')
            ->assertSet('uploaded_file', null);
    }
}
