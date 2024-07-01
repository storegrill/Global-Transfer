<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransferController extends Controller
{
    public function transfer(Request $request)
    {
        $request->validate([
            'from_account' => 'required|string|exists:accounts,account_number',
            'to_account' => 'required|string|exists:accounts,account_number',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $fromAccount = Account::where('account_number', $request->from_account)->first();
        $toAccount = Account::where('account_number', $request->to_account)->first();

        if (! $fromAccount || ! $toAccount) {
            return response()->json(['error' => 'One or both accounts do not exist'], 404);
        }

        if ($fromAccount->balance < $request->amount) {
            return response()->json(['error' => 'Insufficient funds'], 400);
        }

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Perform the transfer
            $fromAccount->balance -= $request->amount;
            $toAccount->balance += $request->amount;

            $fromAccount->save();
            $toAccount->save();

            // Record transactions
            Transaction::create([
                'account_id' => $fromAccount->id,
                'type' => 'debit',
                'amount' => $request->amount,
            ]);

            Transaction::create([
                'account_id' => $toAccount->id,
                'type' => 'credit',
                'amount' => $request->amount,
            ]);

            // Commit the transaction if all went well
            DB::commit();

            // Log successful transfer
            Log::info('Transfer successful', [
                'from_account' => $fromAccount->account_number,
                'to_account' => $toAccount->account_number,
                'amount' => $request->amount,
            ]);

            return response()->json(['message' => 'Transfer successful']);
        } catch (\Exception $e) {
            // Rollback the transaction if an error occurred
            DB::rollback();
            Log::error('Transfer failed: ' . $e->getMessage(), [
                'from_account' => $fromAccount->account_number,
                'to_account' => $toAccount->account_number,
                'amount' => $request->amount,
            ]);
            return response()->json(['error' => 'Transfer failed', 'message' => $e->getMessage()], 500);
        }
    }
}
