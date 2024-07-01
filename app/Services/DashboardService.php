<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DashboardService
{
    /**
     * Fetch dashboard summary data.
     *
     * @return array
     */
    public function getDashboardSummary()
    {
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

        return [
            'user' => $user,
            'accounts' => $accounts,
            'recent_transactions' => $recentTransactions,
            'total_balance' => $totalBalance,
        ];
    }

    /**
     * Fetch paginated transactions for a specific account.
     *
     * @param int $accountId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAccountTransactions($accountId, $perPage = 10)
    {
        // Ensure the account belongs to the authenticated user
        $account = Account::where('id', $accountId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Fetch paginated transactions for the account
        return Transaction::where('account_id', $account->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Fetch summary of transactions for a specific period.
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getTransactionSummary($startDate, $endDate)
    {
        // Fetch transactions within the specified date range
        $transactions = Transaction::where('user_id', Auth::id())
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Calculate summary data (total count, total amount, etc.)
        $totalCount = $transactions->count();
        $totalAmount = $transactions->sum('amount');

        return [
            'total_count' => $totalCount,
            'total_amount' => $totalAmount,
            'transactions' => $transactions,
        ];
    }
}
