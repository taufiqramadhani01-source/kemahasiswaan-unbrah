<?php

namespace App\Livewire\Admin;

use App\Models\Prestasi;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManagePrestasis extends Component
{
    use WithFileUploads;

    public $search = '';

    // Form fields
    public $prestasiId = null;
    public $student_name = '';
    public $title = '';
    public $level = 'Nasional'; // Default
    public $year = '';
    public $certificate_path = '';
    public $certificate_file = null; // for upload

    public $isFormOpen = false;

    protected $rules = [
        'student_name' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'level' => 'required|string|max:100',
        'year' => 'required|integer|min:2000|max:2100',
        'certificate_path' => 'nullable|string',
        'certificate_file' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048', // max 2MB
    ];

    public function openForm($id = null)
    {
        $this->resetValidation();
        $this->resetExcept(['search', 'isFormOpen']);

        if ($id) {
            $prestasi = Prestasi::findOrFail($id);
            $this->prestasiId = $prestasi->id;
            $this->student_name = $prestasi->student_name;
            $this->title = $prestasi->title;
            $this->level = $prestasi->level;
            $this->year = $prestasi->year;
            $this->certificate_path = $prestasi->certificate_path;
        } else {
            $this->prestasiId = null;
            $this->student_name = '';
            $this->title = '';
            $this->level = 'Nasional';
            $this->year = date('Y');
            $this->certificate_path = '';
            $this->certificate_file = null;
        }

        $this->isFormOpen = true;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'student_name' => $this->student_name,
            'title' => $this->title,
            'level' => $this->level,
            'year' => $this->year,
        ];

        // Handle File Upload if selected
        if ($this->certificate_file) {
            $path = $this->certificate_file->store('certificates', 'public');
            $data['certificate_path'] = asset('storage/' . $path);
        } else {
            $data['certificate_path'] = $this->certificate_path;
        }

        if ($this->prestasiId) {
            Prestasi::findOrFail($this->prestasiId)->update($data);
            session()->flash('message', 'Prestasi berhasil diperbarui.');
        } else {
            Prestasi::create($data);
            session()->flash('message', 'Prestasi berhasil ditambahkan.');
        }

        $this->isFormOpen = false;
    }

    public function delete($id)
    {
        Prestasi::findOrFail($id)->delete();
        session()->flash('message', 'Prestasi berhasil dihapus.');
    }

    public function render()
    {
        $prestasis = Prestasi::where('student_name', 'like', '%' . $this->search . '%')
            ->orWhere('title', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.manage-prestasis', [
            'prestasis' => $prestasis
        ])->layout('layouts.app');
    }
}
