<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the investments belonging to the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $investments = Investment::where('user_id', Auth::id())->paginate(10);
        return response()->json($investments);
    }

    /**
     * Display the specified investment belonging to the authenticated user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $investment = Investment::where('user_id', Auth::id())->findOrFail($id);
        return response()->json($investment);
    }

    /**
     * Store a newly created investment in storage for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'maturity_date' => 'nullable|date',
        ]);

        $investment = Investment::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'amount' => $request->amount,
            'maturity_date' => $request->maturity_date,
        ]);

        return response()->json($investment, 201);
    }

    /**
     * Update the specified investment belonging to the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'status' => 'required|string',
            'maturity_date' => 'nullable|date',
        ]);

        $investment = Investment::where('user_id', Auth::id())->findOrFail($id);

        // Use a transaction to ensure atomicity
        DB::beginTransaction();
        try {
            $investment->update($request->all());
            DB::commit();
            return response()->json($investment);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to update investment', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified investment belonging to the authenticated user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $investment = Investment::where('user_id', Auth::id())->findOrFail($id);

        // Use a transaction to ensure atomicity
        DB::beginTransaction();
        try {
            $investment->delete();
            DB::commit();
            return response()->json(['message' => 'Investment deleted successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to delete investment', 'message' => $e->getMessage()], 500);
        }
    }
}
