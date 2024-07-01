<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MultiLanguage;
use Illuminate\Validation\ValidationException;

class MultiLanguageController extends Controller
{
    /**
     * Set the preferred language for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setLanguage(Request $request)
    {
        try {
            $request->validate([
                'language' => 'required|string|in:en,fr,es,de,it', // Add supported languages here
            ]);

            $user = Auth::user();
            $multiLanguage = $user->multiLanguage ?? new MultiLanguage(['user_id' => $user->id]);
            $multiLanguage->language = $request->language;
            $multiLanguage->save();

            return response()->json(['message' => 'Language updated successfully']);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'messages' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to set language', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get the preferred language of the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLanguage()
    {
        try {
            $user = Auth::user();
            $language = $user->multiLanguage ? $user->multiLanguage->language : 'en'; // Default to English if not set

            return response()->json(['language' => $language]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to get language', 'message' => $e->getMessage()], 500);
        }
    }
}
