<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class ExchangeRateController extends Controller
{
    protected $client;
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiUrl = env('EXCHANGE_RATE_API_URL', 'https://api.exchangerate-api.com/v4/latest/');
        $this->apiKey = env('EXCHANGE_RATE_API_KEY');
    }

    public function getExchangeRate(Request $request, $baseCurrency, $targetCurrency)
    {
        $cacheKey = "{$baseCurrency}_to_{$targetCurrency}";
        $exchangeRate = Cache::get($cacheKey);

        if (!$exchangeRate) {
            try {
                $response = $this->client->get("{$this->apiUrl}{$baseCurrency}?apikey={$this->apiKey}");
                $data = json_decode($response->getBody(), true);

                if (isset($data['rates'][$targetCurrency])) {
                    $exchangeRate = $data['rates'][$targetCurrency];
                    Cache::put($cacheKey, $exchangeRate, 3600); // Cache for 1 hour
                } else {
                    return response()->json(['error' => 'Target currency not found'], 404);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to fetch exchange rate'], 500);
            }
        }

        return response()->json([
            'base_currency' => $baseCurrency,
            'target_currency' => $targetCurrency,
            'exchange_rate' => $exchangeRate
        ]);
    }

    public function convertCurrency(Request $request)
    {
        try {
            $request->validate([
                'base_currency' => 'required|string|size:3',
                'target_currency' => 'required|string|size:3',
                'amount' => 'required|numeric|min:0.01'
            ]);

            $baseCurrency = $request->input('base_currency');
            $targetCurrency = $request->input('target_currency');
            $amount = $request->input('amount');

            $response = $this->getExchangeRate(new Request(), $baseCurrency, $targetCurrency);

            if ($response->getStatusCode() !== 200) {
                throw new \Exception('Failed to fetch exchange rate');
            }

            $exchangeRate = $response->getData()->exchange_rate;

            if (!$exchangeRate) {
                return response()->json(['error' => 'Exchange rate not found'], 404);
            }

            $convertedAmount = $amount * $exchangeRate;

            return response()->json([
                'base_currency' => $baseCurrency,
                'target_currency' => $targetCurrency,
                'exchange_rate' => $exchangeRate,
                'amount' => $amount,
                'converted_amount' => $convertedAmount
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
