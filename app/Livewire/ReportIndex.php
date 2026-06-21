<?php

namespace App\Livewire;

use App\Models\Loan;
use Livewire\Component;
use Livewire\WithPagination;

class ReportIndex extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $status = '';

    public function mount()
    {
        // Default rentang tanggal hari ini / bulan ini
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function render()
    {
        $query = Loan::with(['user', 'details.book']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('loan_date', [$this->startDate, $this->endDate]);
        }

        if ($this->status !== '') {
            $query->where('status', $this->status);
        }

        $loans = $query->latest()->get();

        return view('livewire.report-index', [
            'loans' => $loans
        ])->layout('layouts::app');
    }
}