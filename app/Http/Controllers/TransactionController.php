<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Display a listing of the user's transactions.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $transactions = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Pagination for large datasets

        return response()->json($transactions);
    }

    /**
     * Store a newly created transaction in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'type' => 'required|in:deposit,withdrawal,transfer',
            'amount' => 'required|numeric|min:0.01',
            'target_account_id' => 'nullable|exists:accounts,id',
        ]);

        $user = Auth::user();
        $account = Account::find($request->account_id);

        if ($account->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        DB::beginTransaction();

        try {
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->account_id = $request->account_id;
            $transaction->type = $request->type;
            $transaction->amount = $request->amount;

            if ($request->type === 'transfer') {
                $targetAccount = Account::find($request->target_account_id);

                if (!$targetAccount || $account->balance < $request->amount) {
                    return response()->json(['error' => 'Invalid transfer details'], 400);
                }

                $targetAccount->balance += $request->amount;
                $targetAccount->save();

                $transaction->target_account_id = $targetAccount->id;
            }

            if ($request->type === 'withdrawal' && $account->balance < $request->amount) {
                return response()->json(['error' => 'Insufficient balance'], 400);
            }

            $account->balance += ($request->type === 'deposit' ? $request->amount : -$request->amount);
            $account->save();

            $transaction->save();

            DB::commit();

            return response()->json($transaction, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction failed: ' . $e->getMessage());
            return response()->json(['error' => 'Transaction failed'], 500);
        }
    }

    /**
     * Display the specified transaction.
     */
    public function show($id)
    {
        $user = Auth::user();
        $transaction = Transaction::where('user_id', $user->id)->find($id);

        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        return response()->json($transaction);
    }
}
