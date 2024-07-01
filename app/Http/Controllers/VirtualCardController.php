<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\VirtualCard;

class VirtualCardController extends Controller
{
    /**
     * Create a new virtual card for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'card_number' => 'required|unique:virtual_cards,card_number',
            'expiry_date' => 'required|date',
            'cvv' => 'required|digits:3',
        ]);

        // Create new virtual card for the authenticated user
        $user = $request->user();
        $virtualCard = new VirtualCard();
        $virtualCard->card_number = $validatedData['card_number'];
        $virtualCard->expiry_date = $validatedData['expiry_date'];
        $virtualCard->cvv = $validatedData['cvv'];

        // Save the virtual card
        $user->virtualCards()->save($virtualCard);

        return response()->json(['message' => 'Virtual card created successfully', 'card' => $virtualCard], 201);
    }

    /**
     * Get all virtual cards belonging to the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $virtualCards = $user->virtualCards()->get();

        return response()->json(['cards' => $virtualCards], 200);
    }

    /**
     * Delete a virtual card.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // Validate the ID parameter
        if (!is_numeric($id) || $id <= 0) {
            return response()->json(['error' => 'Invalid ID parameter'], 400);
        }

        $user = $request->user();
        $virtualCard = VirtualCard::where('id', $id)->where('user_id', $user->id)->first();

        if (!$virtualCard) {
            return response()->json(['message' => 'Virtual card not found'], 404);
        }

        $virtualCard->delete();

        return response()->json(['message' => 'Virtual card deleted successfully'], 200);
    }
}
