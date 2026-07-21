<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class PostEditor extends Component
{
    use WithFileUploads;

    public ?int $postId = null;
    public string $title = '';
    public string $slug = '';
    public string $content = '';
    public string $tag = '';
    public string $author = '';
    public string $excerpt = '';
    public string $image_path = '';
    public $image_file = null; // for upload
    public bool $is_published = false;
    public ?string $published_at = null;

    protected $rules = [
        'title'        => 'required|string|max:255',
        'slug'         => 'required|string|max:255',
        'content'      => 'required|string',
        'tag'          => 'nullable|string|max:100',
        'author'       => 'nullable|string|max:150',
        'excerpt'      => 'nullable|string|max:500',
        'image_file'   => 'nullable|image|max:2048', // max 2MB
        'is_published' => 'boolean',
        'published_at' => 'nullable|date',
    ];

    public function mount(?int $postId = null): void
    {
        // Proteksi RBAC dinamis: Cek akses menu 'posts'
        if (!auth()->user() || !auth()->user()->hasAccessToMenu('posts')) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }

        if ($postId) {
            $post = Post::findOrFail($postId);
            $this->postId       = $post->id;
            $this->title        = $post->title;
            $this->slug         = $post->slug;
            $this->content      = $post->content;
            $this->tag          = $post->tag ?? '';
            $this->author       = $post->author ?? '';
            $this->excerpt      = $post->excerpt ?? '';
            $this->image_path   = $post->image_path ?? '';
            $this->is_published = (bool) $post->is_published;
            $this->published_at = $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : null;
        } else {
            // Default value
            $this->author = auth()->user()->name ?? '';
            $this->published_at = now()->format('Y-m-d\TH:i');
        }
    }

    public function updatedTitle(): void
    {
        if (empty($this->slug) || $this->postId === null) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function save()
    {
        $this->validate();

        // Cek keunikan slug
        $slugCheck = Post::where('slug', $this->slug);
        if ($this->postId) {
            $slugCheck->where('id', '!=', $this->postId);
        }
        if ($slugCheck->exists()) {
            $this->addError('slug', 'Slug berita ini sudah digunakan oleh berita lain.');
            return;
        }

        $data = [
            'title'        => $this->title,
            'slug'         => $this->slug,
            'content'      => $this->content,
            'tag'          => $this->tag ?: null,
            'author'       => $this->author ?: null,
            'excerpt'      => $this->excerpt ?: null,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at ? \Carbon\Carbon::parse($this->published_at) : null,
        ];

        // Upload featured image
        if ($this->image_file) {
            $path = $this->image_file->store('posts', 'public');
            $data['image_path'] = '/storage/' . $path;
        }

        if ($this->postId) {
            $post = Post::findOrFail($this->postId);
            $post->update($data);
            session()->flash('message', 'Berita berhasil diperbarui.');
        } else {
            $post = Post::create($data);
            session()->flash('message', 'Berita baru berhasil dibuat.');
        }

        return redirect()->route('admin.posts');
    }

    public function render()
    {
        return view('livewire.admin.post-editor')
            ->layout('layouts.app');
    }
}
