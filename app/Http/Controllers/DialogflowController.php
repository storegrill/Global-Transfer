<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Dialogflow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DialogflowController extends Controller
{
    public function handle(Request $request)
    {
        try {
            $queryResult = $request->input('queryResult');
            $intent = $queryResult['intent']['displayName'];
            $parameters = $queryResult['parameters'];

            switch ($intent) {
                case 'GetAccountBalance':
                    $response = $this->getAccountBalance($parameters);
                    break;
                case 'TransferMoney':
                    $response = $this->transferMoney($parameters);
                    break;
                default:
                    $response = response()->json([
                        'fulfillmentText' => 'Sorry, I did not understand that. Can you please rephrase?'
                    ]);
            }

            // Store interaction
            Dialogflow::create([
                'user_id' => Auth::id(),
                'intent' => $intent,
                'parameters' => json_encode($parameters),
                'response' => $response->getData()->fulfillmentText,
            ]);

            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'fulfillmentText' => 'An error occurred while processing your request. Please try again later.'
            ]);
        }
    }

    protected function getAccountBalance($parameters)
    {
        $accountNumber = $parameters['account-number'];
        $account = Account::where('account_number', $accountNumber)->where('user_id', Auth::id())->first();

        if ($account) {
            $balance = $account->balance;
            return response()->json([
                'fulfillmentText' => "The balance for account number $accountNumber is $balance."
            ]);
        } else {
            return response()->json([
                'fulfillmentText' => 'I could not find that account. Please check the account number and try again.'
            ]);
        }
    }

    protected function transferMoney($parameters)
    {
        $fromAccountNumber = $parameters['from-account'];
        $toAccountNumber = $parameters['to-account'];
        $amount = $parameters['amount']['amount'];

        $fromAccount = Account::where('account_number', $fromAccountNumber)->where('user_id', Auth::id())->first();
        $toAccount = Account::where('account_number', $toAccountNumber)->first();

        if (!$fromAccount || !$toAccount) {
            return response()->json([
                'fulfillmentText' => 'I could not find one or both of the accounts. Please check the account numbers and try again.'
            ]);
        }

        if ($fromAccount->balance < $amount) {
            return response()->json([
                'fulfillmentText' => 'Insufficient funds in the from account.'
            ]);
        }

        DB::beginTransaction();

        try {
            $fromAccount->balance -= $amount;
            $toAccount->balance += $amount;

            $fromAccount->save();
            $toAccount->save();

            Transaction::create([
                'account_id' => $fromAccount->id,
                'type' => 'debit',
                'amount' => $amount,
            ]);

            Transaction::create([
                'account_id' => $toAccount->id,
                'type' => 'credit',
                'amount' => $amount,
            ]);

            DB::commit();

            return response()->json([
                'fulfillmentText' => "Successfully transferred $amount from account number $fromAccountNumber to account number $toAccountNumber."
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'fulfillmentText' => 'An error occurred during the transfer. Please try again later.'
            ]);
        }
    }
}
