<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ManagePosts extends Component
{
    use WithFileUploads;

    public $search = '';

    // Form fields
    public $postId = null;
    public $title = '';
    public $slug = '';
    public $image_path = '';
    public $image_file = null;
    public $content = '';
    public $tag = '';
    public $author = '';
    public $excerpt = '';
    public $is_published = false;
    public $published_at = null;

    public $isFormOpen = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'slug' => 'required|string|unique:posts,slug',
        'image_path' => 'nullable|string',
        'image_file' => 'nullable|image|max:2048',
        'content' => 'required|string',
        'tag' => 'nullable|string|max:100',
        'author' => 'nullable|string|max:150',
        'excerpt' => 'nullable|string|max:500',
        'is_published' => 'boolean',
        'published_at' => 'nullable|date',
    ];

    public function updatedTitle($value)
    {
        $this->slug = Str::slug($value);
    }

    public function openForm($id = null)
    {
        $this->resetValidation();
        $this->resetExcept([]);

        if ($id) {
            $post = Post::findOrFail($id);
            $this->postId = $post->id;
            $this->title = $post->title;
            $this->slug = $post->slug;
            $this->image_path = $post->image_path;
            $this->content = $post->content;
            $this->tag = $post->tag ?? '';
            $this->author = $post->author ?? '';
            $this->excerpt = $post->excerpt ?? '';
            $this->is_published = $post->is_published;
            $this->published_at = $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : null;
        } else {
            $this->postId = null;
            $this->title = '';
            $this->slug = '';
            $this->image_path = '';
            $this->image_file = null;
            $this->content = '';
            $this->tag = '';
            $this->author = '';
            $this->excerpt = '';
            $this->is_published = false;
            $this->published_at = date('Y-m-d\TH:i');
        }

        $this->isFormOpen = true;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->postId) {
            $rules['slug'] = 'required|string|unique:posts,slug,' . $this->postId;
        }

        $this->validate($rules);

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'tag' => $this->tag ?: null,
            'author' => $this->author ?: null,
            'excerpt' => $this->excerpt ?: null,
            'is_published' => $this->is_published,
            'published_at' => $this->is_published ? ($this->published_at ?: now()) : null,
        ];

        // Handle File Upload if selected
        if ($this->image_file) {
            $path = $this->image_file->store('news', 'public');
            $data['image_path'] = asset('storage/' . $path);
        } else {
            $data['image_path'] = $this->image_path;
        }

        if ($this->postId) {
            Post::findOrFail($this->postId)->update($data);
            session()->flash('message', 'Berita berhasil diperbarui.');
        } else {
            Post::create($data);
            session()->flash('message', 'Berita berhasil diterbitkan.');
        }

        $this->isFormOpen = false;
    }

    public function togglePublish($id)
    {
        $post = Post::findOrFail($id);
        $newStatus = !$post->is_published;
        $post->update([
            'is_published' => $newStatus,
            'published_at' => $newStatus ? now() : null
        ]);
        session()->flash('message', 'Status publikasi berita berhasil diubah.');
    }

    public function delete($id)
    {
        Post::findOrFail($id)->delete();
        session()->flash('message', 'Berita berhasil dihapus.');
    }

    public function render()
    {
        $posts = Post::where('title', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.manage-posts', [
            'posts' => $posts
        ])->layout('layouts.app');
    }
}
