<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::where('user_id', auth()->id())
            ->orderBy('expense_date', 'desc')
            ->paginate(5); // 5 per page

        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_date' => 'required|date',
            'category' => 'required|string|max:50',
            'amount' => 'required|integer|min:1',
            'note' => 'nullable|string', 
        ]);

        Expense::create([
            'user_id' => auth()->id(),
            'expense_date' => $validated['expense_date'],
            'category' => $validated['category'],
            'amount' => $validated['amount'],
            'note' => $validated['note'] ?? null,
        ]);

        return redirect('/expenses');
    }

    public function edit(Expense $expense)
    {
        // Only allow owner to edit
        if ($expense->user_id !== auth()->id())
            {
                abort(403, 'Unauthorized action.');
            }

        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        if ($expense->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'expense_date' => 'required|date',
            'category' => 'required|string|max:50',
            'amount' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);

        $expense->update($validated);

        return redirect('/expenses');
    }

    public function destroy(Expense $expense)
    {
    if ($expense->user_id !== auth()->id()) 
        {
            abort(403, 'Unauthorized action.');
        }

        $expense->delete();

        return redirect('/expenses');
    }

}
