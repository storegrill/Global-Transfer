<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function sendMoney(Request $request)
    {
        $request->validate([
            'recipient' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $transaction = Transaction::create([
            'user_id' => $request->user()->id,
            'recipient' => $request->recipient,
            'amount' => $request->amount,
        ]);

        return response()->json(['message' => 'Money sent successfully', 'transaction' => $transaction]);
    }
}

