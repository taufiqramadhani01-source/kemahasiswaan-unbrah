<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ManageUsers extends Component
{
    // Form state
    public $userId = null;
    public $name = '';
    public $email = '';
    public $role = 'staf_layanan'; // default role
    public $group_id = '';          // Kelompok Akses dinamis
    public $password = '';
    public $password_confirmation = '';
    
    public $isFormOpen = false;
    public $search = '';

    public function mount()
    {
        if (!auth()->user() || !auth()->user()->hasAccessToMenu('users')) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }
    }

    // ─── Open / Close Form ───────────────────────────────────────────────────
    public function openForm($id = null)
    {
        $this->resetValidation();
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->role = 'staf_layanan';
        $this->group_id = '';
        $this->password = '';
        $this->password_confirmation = '';

        if ($id) {
            $user = User::findOrFail($id);
            $this->userId = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role;
            $this->group_id = $user->group_id ?: '';
        }

        $this->isFormOpen = true;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
    }

    // ─── Save User ───────────────────────────────────────────────────────────
    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'role' => 'required|in:superadmin,staf_layanan,staf_humas',
            'group_id' => 'nullable|exists:groups,id',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->userId),
            ],
        ];

        // Jika user baru, password wajib diisi. Jika edit, password opsional
        if (empty($this->userId)) {
            $rules['password'] = 'required|string|min:6|confirmed';
        } else {
            $rules['password'] = 'nullable|string|min:6|confirmed';
        }

        $this->validate($rules);

        $data = [
            'name'  => $this->name,
            'email' => $this->email,
            'role'  => $this->role,
            'group_id' => $this->group_id ?: null,
        ];

        // Update password jika diisi
        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->userId) {
            User::findOrFail($this->userId)->update($data);
            session()->flash('message', 'User admin berhasil diperbarui.');
        } else {
            User::create($data);
            session()->flash('message', 'User admin baru berhasil ditambahkan.');
        }

        $this->isFormOpen = false;
    }

    // ─── Delete ──────────────────────────────────────────────────────────────
    public function delete($id)
    {
        // Mencegah admin menghapus dirinya sendiri
        if (auth()->id() == $id) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
            return;
        }

        User::findOrFail($id)->delete();
        session()->flash('message', 'User admin berhasil dihapus.');
    }

    // ─── Render ──────────────────────────────────────────────────────────────
    public function render()
    {
        $query = User::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        $users = $query->with('group')->orderBy('name')->get();
        $groups = \App\Models\Group::orderBy('name')->get();

        return view('livewire.admin.manage-users', [
            'users' => $users,
            'groups' => $groups,
        ])->layout('layouts.app');
    }
}
