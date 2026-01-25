<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Expense extends Model
{
    /** @use HasFactory<\Database\Factories\ExpenseFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'expense_date',
        'category',
        'amount',
        'note',
    ];

    public function user()
        {
            return $this->belongsTo(User::class);
        }
}


