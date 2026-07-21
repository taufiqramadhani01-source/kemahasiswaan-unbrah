<?php

namespace App\Livewire\Admin;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class PageEditor extends Component
{
    use WithFileUploads;

    public ?int $pageId = null;
    public string $title = '';
    public string $slug = '';
    public bool $is_published = false;
    public array $blocks = [];

    // Temporary upload storage per block index
    // e.g. [3 => LivewireTemporaryUploadedFile]
    public $temp_images = [];

    public string $newBlockType = 'text';

    protected $rules = [
        'title' => 'required|string|max:255',
        'slug'  => 'required|string|max:255',
    ];

    public function mount(?int $pageId = null): void
    {
        if (!auth()->user() || !auth()->user()->hasAccessToMenu('pages')) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }

        if ($pageId) {
            $page = Page::findOrFail($pageId);
            $this->pageId = $page->id;
            $this->title = $page->title;
            $this->slug = $page->slug;
            $this->is_published = (bool) $page->is_published;
            $this->blocks = $page->content ?? [];
        } else {
            // Default blocks
            $this->blocks = [
                [
                    'type' => 'hero',
                    'data' => [
                        'label'           => 'INFORMASI KEMAHASISWAAN',
                        'title'           => 'Selamat Datang di Halaman',
                        'highlight'       => 'Baru',
                        'highlight_color' => '#c8b282',
                        'subtitle'        => 'Menyajikan informasi seputar aktivitas kemahasiswaan',
                        'bg_color'        => '#052c22',
                        'align'           => 'center',
                    ]
                ]
            ];
        }
    }

    public function updatedTitle(): void
    {
        if (empty($this->slug) || $this->pageId === null) {
            $this->slug = Str::slug($this->title);
        }
    }

    // ─── Block Actions ────────────────────────────────────────────────────────
    public function addBlock(): void
    {
        $defaults = [
            'heading'  => ['text' => '', 'level' => 'h2'],
            'text'     => ['html' => ''],
            'image'    => ['url' => '', 'caption' => '', 'align' => 'center', 'local_path' => ''],
            'divider'  => [],
            'callout'  => ['text' => '', 'color' => 'blue'],
            'hero'     => [
                'label'           => '',
                'title'           => '',
                'highlight'       => '',
                'highlight_color' => '#c8b282',
                'subtitle'        => '',
                'bg_color'        => '#052c22',
                'align'           => 'center',
            ],
            'two_col'  => [
                'left_heading'     => '',
                'left_content'     => '',
                'left_bg_color'    => '',
                'left_text_color'  => '#1a2421',
                'right_heading'    => '',
                'right_content'    => '',
                'right_bg_color'   => '',
                'right_text_color' => '#1a2421',
                'ratio'            => '50/50',
            ],
            'accordion' => [
                'items' => [
                    ['title' => 'Pertanyaan 1', 'content' => 'Jawaban detail di sini...']
                ]
            ],
            'downloads' => [
                'items' => [
                    ['label' => 'Unduh Dokumen PDF', 'url' => '#', 'type' => 'pdf'] // pdf | link
                ]
            ],
            'card_grid' => [
                'columns' => '3', // 2 | 3 | 4
                'items' => [
                    ['title' => 'Kartu 1', 'desc' => 'Deskripsi singkat...', 'image' => '', 'link' => '']
                ]
            ],
            'custom_html' => [
                'code' => ''
            ]
        ];

        $this->blocks[] = [
            'type' => $this->newBlockType,
            'data' => $defaults[$this->newBlockType] ?? [],
        ];
    }

    // Helper untuk memanipulasi sub-item di dalam list blok (nested array)
    public function addNestedItem(int $blockIndex, string $arrayKey, array $defaultStructure): void
    {
        $this->blocks[$blockIndex]['data'][$arrayKey][] = $defaultStructure;
    }

    public function removeNestedItem(int $blockIndex, string $arrayKey, int $itemIndex): void
    {
        unset($this->blocks[$blockIndex]['data'][$arrayKey][$itemIndex]);
        $this->blocks[$blockIndex]['data'][$arrayKey] = array_values($this->blocks[$blockIndex]['data'][$arrayKey]);
    }


    public function removeBlock(int $index): void
    {
        unset($this->blocks[$index]);
        $this->blocks = array_values($this->blocks);
        if (isset($this->temp_images[$index])) {
            unset($this->temp_images[$index]);
        }
    }

    public function moveBlockUp(int $index): void
    {
        if ($index > 0) {
            $temp = $this->blocks[$index - 1];
            $this->blocks[$index - 1] = $this->blocks[$index];
            $this->blocks[$index] = $temp;

            // Swap temp images too if any
            $imgTemp = $this->temp_images[$index - 1] ?? null;
            if (isset($this->temp_images[$index])) {
                $this->temp_images[$index - 1] = $this->temp_images[$index];
            } else {
                unset($this->temp_images[$index - 1]);
            }
            if ($imgTemp) {
                $this->temp_images[$index] = $imgTemp;
            } else {
                unset($this->temp_images[$index]);
            }
        }
    }

    public function moveBlockDown(int $index): void
    {
        if ($index < count($this->blocks) - 1) {
            $temp = $this->blocks[$index + 1];
            $this->blocks[$index + 1] = $this->blocks[$index];
            $this->blocks[$index] = $temp;

            // Swap temp images
            $imgTemp = $this->temp_images[$index + 1] ?? null;
            if (isset($this->temp_images[$index])) {
                $this->temp_images[$index + 1] = $this->temp_images[$index];
            } else {
                unset($this->temp_images[$index + 1]);
            }
            if ($imgTemp) {
                $this->temp_images[$index] = $imgTemp;
            } else {
                unset($this->temp_images[$index]);
            }
        }
    }

    public function updateBlockData(int $index, string $key, $value): void
    {
        $this->blocks[$index]['data'][$key] = $value;
    }

    // ─── Image Upload Handling ────────────────────────────────────────────────
    public function updatedTempImages($file, $index): void
    {
        $this->validate([
            "temp_images.{$index}" => 'image|max:2048', // max 2MB
        ]);

        // Simpan langsung ke public disk
        $path = $this->temp_images[$index]->store('pages', 'public');
        
        // Update URL di block data
        $this->blocks[$index]['data']['url'] = '/storage/' . $path;
        $this->blocks[$index]['data']['local_path'] = $path;
    }

    // ─── Save & Store ─────────────────────────────────────────────────────────
    public function save()
    {
        $this->validate();

        // Cek keunikan slug (ignore data saat ini)
        $slugCheck = Page::where('slug', $this->slug);
        if ($this->pageId) {
            $slugCheck->where('id', '!=', $this->pageId);
        }
        if ($slugCheck->exists()) {
            $this->addError('slug', 'Slug ini sudah digunakan oleh halaman lain.');
            return;
        }

        $data = [
            'title'        => $this->title,
            'slug'         => $this->slug,
            'content'      => $this->blocks,
            'is_published' => $this->is_published,
        ];

        if ($this->pageId) {
            $page = Page::findOrFail($this->pageId);
            $page->update($data);
            session()->flash('message', 'Halaman berhasil diperbarui.');
        } else {
            $page = Page::create($data);
            session()->flash('message', 'Halaman baru berhasil dibuat.');
            return redirect()->route('admin.pages.edit', ['pageId' => $page->id]);
        }
    }

    public function render()
    {
        return view('livewire.admin.page-editor')
            ->layout('layouts.app');
    }
}
