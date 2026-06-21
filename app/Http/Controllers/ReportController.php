<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Default rentang tanggal jika belum ada filter
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $status = $request->input('status', '');

        $query = Loan::with(['user', 'details.book']);

        if ($startDate && $endDate) {
            $query->whereBetween('loan_date', [$startDate, $endDate]);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $loans = $query->latest()->get();

        return view('admin.reports.index', compact('loans', 'startDate', 'endDate', 'status'));
    }
}