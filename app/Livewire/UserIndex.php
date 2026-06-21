<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Flux;

class UserIndex extends Component
{
    use WithPagination;

    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'student';
    public $editingUserId = null;
    public $showModal = false;

    public function create()
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'password', 'editingUserId']);
        $this->role = 'student';
        $this->showModal = true;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($this->editingUserId ?? 'NULL'),
            // ✅ Tambah 'petugas' ke daftar role yang valid
            'role' => 'required|in:admin,petugas,student',
        ];

        $rules['password'] = $this->editingUserId
            ? 'nullable|string|min:8'
            : 'required|string|min:8';

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if ($this->editingUserId) {
            $user = User::find($this->editingUserId);

            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }

            $user->update($data);
            Flux::toast('Data user berhasil diperbarui.');
        } else {
            $data['password'] = Hash::make($this->password);
            User::create($data);
            Flux::toast('User baru berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->reset(['name', 'email', 'password', 'editingUserId']);
    }

    public function edit($id)
    {
        $this->resetValidation();
        $user = User::find($id);

        if ($user) {
            $this->editingUserId = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role;
            $this->password = '';
            $this->showModal = true;
        }
    }

    public function delete($id)
    {
        if ($id == Auth::id()) {
            Flux::toast(
                text: 'Anda tidak bisa menghapus akun Anda sendiri.',
                variant: 'danger'
            );
            return;
        }

        $user = User::find($id);

        if ($user) {
            $user->delete();
            Flux::toast('User berhasil dihapus.');
        }
    }

    public function render()
    {
        return view('pages::user.index', [
            'users' => User::latest()->paginate(10),
        ])->layout('layouts::app');
    }
}