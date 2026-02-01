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
                DB::raw('EXTRACT(YEAR FROM expense_date) as year'),
                DB::raw('EXTRACT(MONTH FROM expense_date) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->where('user_id', $userId)
            ->groupBy(DB::raw('EXTRACT(YEAR FROM expense_date)'), DB::raw('EXTRACT(MONTH FROM expense_date)'))
            ->orderBy(DB::raw('EXTRACT(YEAR FROM expense_date)'), 'desc')
            ->orderBy(DB::raw('EXTRACT(MONTH FROM expense_date)'), 'desc')
            ->get();

        return view('reports.monthly', compact('monthlyExpenses'));
    }
}