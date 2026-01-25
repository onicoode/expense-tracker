<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Expense;

class ReportController extends Controller
{
    public function monthly()
    {
        $userId = auth()->id();

        $monthlyExpenses = Expense::select(
                DB::raw('YEAR(expense_date) as year'),
                DB::raw('MONTH(expense_date) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->where('user_id', $userId)
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('reports.monthly', compact('monthlyExpenses'));
    }
}