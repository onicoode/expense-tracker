<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Handle receipt upload
        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $this->handleReceiptUpload($request->file('receipt'));
        }    

        Expense::create([
            'user_id' => auth()->id(),
            'expense_date' => $validated['expense_date'],
            'category' => $validated['category'],
            'amount' => $validated['amount'],
            'note' => $validated['note'] ?? null,
            'receipt' => $receiptPath,
        ]);

        return redirect('/expenses')->with('success', 'Pengeluaran berhasil ditambahkan!');
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
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Handle receipt upload
        if ($request->hasFile('receipt')) {
            // Delete old receipt if exists
            if ($expense->receipt) {
                $oldPath = storage_path('app/public/' . $expense->receipt);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $validated['receipt'] = $this->handleReceiptUpload($request->file('receipt'));
        }        

        $expense->update($validated);

        return redirect('/expenses')->with('success', 'Pengeluaran berhasil diubah!');
    }

    public function destroy(Expense $expense)
    {
    if ($expense->user_id !== auth()->id()) 
        {
            abort(403, 'Unauthorized action.');
        }

        // Delete receipt file if exists
        if ($expense->receipt) {
            $filePath = storage_path('app/public/' . $expense->receipt);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }        

        $expense->delete();

        return redirect('/expenses')->with('success', 'Pengeluaran berhasil dihapus!');
    }

    private function handleReceiptUpload($file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . uniqid() . '.' . $extension;
        
        // Create receipts directory if it doesn't exist
        $receiptDir = storage_path('app/public/receipts');
        if (!file_exists($receiptDir)) {
            mkdir($receiptDir, 0755, true);
        }
        
        $filePath = 'receipts/' . $filename;
        $fullPath = storage_path('app/public/' . $filePath);
        
        // If it's an image, compress it
        if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
            // Create image manager instance
            $manager = new ImageManager(new Driver());
            
            // Read and process image
            $image = $manager->read($file);
            
            // Resize if width is greater than 1920px (keep aspect ratio)
            if ($image->width() > 1920) {
                $image->scale(width: 1920);
            }
            
            // Save with 80% quality (good balance of quality and size)
            if (strtolower($extension) === 'png') {
                $image->toPng()->save($fullPath);
            } else {
                $image->toJpeg(quality: 80)->save($fullPath);
            }
        } else {
            // For PDFs, just move the file
            $file->move($receiptDir, $filename);
        }
        
        return $filePath;
    }    

}
