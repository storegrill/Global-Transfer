<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TrueLayerController extends Controller
{
    /**
     * Handle callback from TrueLayer after user authentication.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleTrueLayerCallback(Request $request)
    {
        $code = $request->input('code');

        // Exchange code for access token
        $accessToken = $this->getAccessToken($code);

        if ($accessToken) {
            // Ensure user is authenticated
            if (Auth::check()) {
                $user = Auth::user();

                // Ensure that the user instance is valid
                if (!$user instanceof User) {
                    return redirect()->route('login')->with('error', 'User authentication failed.');
                }

                // Update user's access token
                $user->true_layer_access_token = $accessToken;
                $user->save(); // Save the updated user instance

                return redirect()->route('dashboard')->with('success', 'TrueLayer authentication successful.');
            } else {
                return redirect()->route('login')->with('error', 'User authentication failed.');
            }
        }

        return redirect()->route('dashboard')->with('error', 'Failed to authenticate with TrueLayer.');
    }

    /**
     * Exchange authorization code for an access token.
     *
     * @param  string  $code
     * @return string|null
     */
    private function getAccessToken(string $code): ?string
    {
        $clientId = config('services.truelayer.client_id');
        $clientSecret = config('services.truelayer.client_secret');
        $redirectUri = route('truelayer.callback');

        $response = Http::asForm()->post('https://auth.truelayer.com/connect/token', [
            'grant_type' => 'authorization_code',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUri,
            'code' => $code,
        ]);

        if ($response->successful()) {
            return $response['access_token'];
        }

        return null;
    }
}
