<?php

namespace App\Livewire;

use App\Models\Fine;
use Livewire\Component;
use Livewire\WithPagination;

class FineIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function markAsPaid($fineId)
    {
        $fine = Fine::findOrFail($fineId);
        $fine->paid = true;
        $fine->save();

        session()->flash('success', 'Status denda berhasil diubah menjadi lunas.');
    }

    public function render()
    {
        // Ambil data denda beserta relasi peminjaman dan user
        $fines = Fine::whereHas('loan.user', function($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->with(['loan.user', 'loan.details.book'])
            ->latest()
            ->paginate(10);

        return view('pages::fine.index', [
            'fines' => $fines
        ])->layout('layouts.app');
    }
}