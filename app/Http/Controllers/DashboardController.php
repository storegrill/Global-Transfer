<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Fetch and display the dashboard summary.
     *
     * @param  Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            // Fetch accounts belonging to the authenticated user
            $accounts = Account::where('user_id', $user->id)->get();

            // Fetch recent transactions for the user
            $recentTransactions = Transaction::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            // Calculate total balance across all accounts
            $totalBalance = $accounts->sum('balance');

            return Inertia::render('Dashboard', [
                'user' => $user,
                'accounts' => $accounts,
                'recent_transactions' => $recentTransactions,
                'total_balance' => $totalBalance,
            ]);
        } catch (\Exception $e) {
            return Inertia::render('Error', [
                'error' => 'Failed to fetch dashboard data',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Fetch paginated transactions for a specific account.
     *
     * @param  Request  $request
     * @param  int  $accountId
     * @return \Inertia\Response
     */
    public function accountTransactions(Request $request, $accountId)
    {
        try {
            // Ensure the account belongs to the authenticated user
            $account = Account::where('id', $accountId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Paginate transactions for the account
            $transactions = Transaction::where('account_id', $account->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10); // Adjust pagination as needed

            return Inertia::render('AccountTransactions', [
                'transactions' => $transactions,
            ]);
        } catch (\Exception $e) {
            return Inertia::render('Error', [
                'error' => 'Failed to fetch account transactions',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Fetch summary of transactions for a specific period.
     *
     * @param  Request  $request
     * @return \Inertia\Response
     */
    public function transactionSummary(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Fetch transactions within the specified date range
            $transactions = Transaction::where('user_id', Auth::id())
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            // Calculate summary data (total count, total amount, etc.)
            $totalCount = $transactions->count();
            $totalAmount = $transactions->sum('amount');

            return Inertia::render('TransactionSummary', [
                'total_count' => $totalCount,
                'total_amount' => $totalAmount,
                'transactions' => $transactions,
            ]);
        } catch (\Exception $e) {
            return Inertia::render('Error', [
                'error' => 'Failed to fetch transaction summary',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
