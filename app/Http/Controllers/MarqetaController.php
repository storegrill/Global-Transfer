<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarqetaController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:16', // Assuming card numbers are strings
            // Add validation rules for other card details
        ]);

        // Create the card in the database
        $card = Card::create($validatedData);

        return response()->json($card, 201);
    }
}
