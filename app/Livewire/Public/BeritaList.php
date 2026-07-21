<?php

namespace App\Livewire\Public;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class BeritaList extends Component
{
    use WithPagination;

    public $search = '';
    public $tag = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'tag'    => ['except' => ''],
    ];

    public function updatedSearch() { $this->resetPage(); }
    public function updatedTag()    { $this->resetPage(); }

    public function render()
    {
        $query = Post::where('is_published', true);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->tag) {
            $query->where('tag', $this->tag);
        }

        $posts = $query->orderBy('published_at', 'desc')->paginate(9);

        $tags = Post::where('is_published', true)
            ->select('tag')
            ->distinct()
            ->whereNotNull('tag')
            ->pluck('tag');

        return view('livewire.public.berita-list', [
            'posts' => $posts,
            'tags'  => $tags,
        ])->layout('layouts.frontend');
    }
}
