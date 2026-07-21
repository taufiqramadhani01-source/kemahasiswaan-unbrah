<?php

use App\Livewire\Admin\ManageOrmawas;
use App\Livewire\Admin\ManageKegiatans;
use App\Livewire\Admin\ManageLayanans;
use App\Livewire\Admin\ManageBeasiswas;
use App\Livewire\Admin\ManagePrestasis;
use App\Livewire\Admin\ManagePosts;
use App\Livewire\Admin\ManagePages;
use App\Livewire\Admin\ManageMenus;
use App\Livewire\Admin\ManageSettings;
use App\Livewire\Admin\ManageDokumens;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\Public\Home::class)->name('public.home');

// Rute Publik untuk Halaman Dinamis (Page Builder)
Route::get('/page/{slug}', [\App\Http\Controllers\PublicPageController::class, 'show'])->name('public.page.show');

// Rute Publik Ormawa List (Livewire SPA)
Route::get('/ormawa', \App\Livewire\Public\OrmawaList::class)->name('public.ormawa');

// Rute Publik Layanan & Beasiswa Mahasiswa
Route::get('/layanan', \App\Livewire\Public\LayananList::class)->name('public.layanan');
Route::get('/beasiswa', \App\Livewire\Public\BeasiswaList::class)->name('public.beasiswa');
Route::get('/prestasi', \App\Livewire\Public\PrestasiList::class)->name('public.prestasi');

// Rute Publik Berita
Route::get('/berita', \App\Livewire\Public\BeritaList::class)->name('berita.index');
Route::get('/berita/{slug}', \App\Livewire\Public\BeritaDetail::class)->name('berita.show');

// Rute Publik Dokumen
Route::get('/dokumen', \App\Livewire\Public\DokumenList::class)->name('public.dokumen');


// Switcher Bahasa (Locale Changer)
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['id', 'en'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');


Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [LoginController::class, 'login']);
Route::post('/admin/logout', [LoginController::class, 'logout']);

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });

    Route::get('/ormawas', ManageOrmawas::class);
    Route::get('/kegiatans', ManageKegiatans::class);
    Route::get('/beasiswas', ManageBeasiswas::class);
    Route::get('/prestasis', ManagePrestasis::class)->name('admin.prestasis');
    Route::get('/layanans', ManageLayanans::class)->name('admin.layanans');
    Route::get('/posts', ManagePosts::class)->name('admin.posts');
    Route::get('/posts/create',      \App\Livewire\Admin\PostEditor::class)->name('admin.posts.create');
    Route::get('/posts/{postId}/edit', \App\Livewire\Admin\PostEditor::class)->name('admin.posts.edit');
    Route::get('/pages',             ManagePages::class)->name('admin.pages');
    Route::get('/pages/create',      \App\Livewire\Admin\PageEditor::class)->name('admin.pages.create');
    Route::get('/pages/{pageId}/edit', \App\Livewire\Admin\PageEditor::class)->name('admin.pages.edit');
    Route::get('/menus',             ManageMenus::class);
    Route::get('/settings',          ManageSettings::class);
    Route::get('/dokumens',          ManageDokumens::class)->name('admin.dokumens');
    Route::get('/homepage',          \App\Livewire\Admin\ManageHomepage::class)->name('admin.homepage');
    Route::get('/users',             \App\Livewire\Admin\ManageUsers::class)->name('admin.users');
    Route::get('/groups',            \App\Livewire\Admin\ManageGroups::class)->name('admin.groups');
});

