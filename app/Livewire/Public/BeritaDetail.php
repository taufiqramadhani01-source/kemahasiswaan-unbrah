<?php

namespace App\Livewire\Public;

use App\Models\Post;
use Livewire\Component;

class BeritaDetail extends Component
{
    public $slug;

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function render()
    {
        $post = Post::where('slug', $this->slug)
            ->where('is_published', true)
            ->firstOrFail();

        $related = Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->where('tag', $post->tag)
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return view('livewire.public.berita-detail', [
            'post'    => $post,
            'related' => $related,
        ])->layout('layouts.frontend');
    }
}
