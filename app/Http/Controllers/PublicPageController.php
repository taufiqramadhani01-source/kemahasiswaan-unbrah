<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PublicPageController extends Controller
{
    /**
     * Tampilkan halaman dinamis berdasarkan slug
     */
    public function show(string $slug)
    {
        $query = Page::where('slug', $slug);
        
        // Jika pengunjung bukan admin yang login, hanya boleh melihat halaman yang sudah diterbitkan
        if (!auth()->check()) {
            $query->where('is_published', true);
        }

        $page = $query->firstOrFail();

        return view('public.page', compact('page'));
    }
}
