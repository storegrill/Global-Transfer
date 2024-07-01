<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Webhook;
use App\Models\StripeTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function createCharge(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.50',
            'currency' => 'required|string',
            'source' => 'required|string',
        ]);

        try {
            $charge = Charge::create([
                'amount' => $request->amount * 100, // Stripe expects amount in cents
                'currency' => $request->currency,
                'source' => $request->source,
                'description' => 'Charge for ' . $request->user()->email,
            ]);

            StripeTransaction::create([
                'user_id' => Auth::id(),
                'transaction_id' => $charge->id,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'status' => $charge->status,
            ]);

            return response()->json($charge, 201);
        } catch (\Exception $e) {
            Log::error('Error creating charge: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create charge'], 500);
        }
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload, $sigHeader, env('STRIPE_WEBHOOK_SECRET')
            );
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid webhook payload: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Invalid webhook signature: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event['type']) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event['data']['object']; // contains a StripePaymentIntent
                Log::info('PaymentIntent was successful!', ['paymentIntent' => $paymentIntent]);
                // Update the transaction status in the database
                $transaction = StripeTransaction::where('transaction_id', $paymentIntent->id)->first();
                if ($transaction) {
                    $transaction->update(['status' => 'succeeded']);
                }
                break;
            // Add handling for other event types as needed
            default:
                Log::info('Received unknown event type', ['event' => $event['type']]);
        }

        return response()->json(['status' => 'success']);
    }
}
