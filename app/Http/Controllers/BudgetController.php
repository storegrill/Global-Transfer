<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    /**
     * Display a listing of the user's budgets.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $budgets = Budget::where('user_id', Auth::id())->get();
            return response()->json($budgets);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch budgets', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created budget in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
            ]);

            $budget = Budget::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'amount' => $request->amount,
            ]);

            return response()->json($budget, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create budget', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified budget.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $budget = Budget::findOrFail($id);

            if ($budget->user_id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            return response()->json($budget);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch budget', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified budget in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $budget = Budget::findOrFail($id);

            if ($budget->user_id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
            ]);

            $budget->name = $request->name;
            $budget->amount = $request->amount;
            $budget->save();

            return response()->json($budget);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update budget', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified budget from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $budget = Budget::findOrFail($id);

            if ($budget->user_id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $budget->delete();

            return response()->json(['message' => 'Budget deleted']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete budget', 'error' => $e->getMessage()], 500);
        }
    }
}
