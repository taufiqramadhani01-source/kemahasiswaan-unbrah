<?php

namespace App\Livewire\Public;

use App\Models\Post;
use App\Models\Kegiatan;
use App\Models\Prestasi;
use App\Models\Beasiswa;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        // Mengambil berita terbaru (limit 4: 1 featured + 3 sidebar)
        $latestPosts = Post::where('is_published', true)
            ->latest()
            ->limit(4)
            ->get();

        // Mengambil agenda kegiatan terdekat (limit 3)
        $upcomingEvents = Kegiatan::latest()
            ->limit(3)
            ->get();

        // Mengambil beasiswa aktif (limit 3)
        $latestBeasiswas = Beasiswa::latest()
            ->limit(3)
            ->get();

        // Mengambil prestasi terbaru (limit 4)
        $latestPrestasis = Prestasi::latest()
            ->limit(4)
            ->get();

        return view('livewire.public.home', [
            'latestPosts' => $latestPosts,
            'upcomingEvents' => $upcomingEvents,
            'latestBeasiswas' => $latestBeasiswas,
            'latestPrestasis' => $latestPrestasis,
        ])->layout('layouts.frontend');
    }
}
