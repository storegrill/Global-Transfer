<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DigitalWallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DigitalWalletController extends Controller
{
    public function show()
    {
        $wallet = Auth::user()->digitalWallet;
        return view('wallet.show', compact('wallet'));
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $wallet = Auth::user()->digitalWallet;

        // Perform deposit within a database transaction
        DB::beginTransaction();

        try {
            $wallet->balance += $request->amount;
            $wallet->save();

            DB::commit();

            return redirect()->back()->with('success', 'Amount deposited successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to deposit amount. Please try again.');
        }
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $wallet = Auth::user()->digitalWallet;

        // Check if sufficient balance
        if ($wallet->balance < $request->amount) {
            return redirect()->back()->with('error', 'Insufficient balance.');
        }

        // Perform withdrawal within a database transaction
        DB::beginTransaction();

        try {
            $wallet->balance -= $request->amount;
            $wallet->save();

            DB::commit();

            return redirect()->back()->with('success', 'Amount withdrawn successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to withdraw amount. Please try again.');
        }
    }
}
