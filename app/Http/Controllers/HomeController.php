<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display the user's accounts with pagination.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // Fetch accounts with pagination
        $user = Auth::user();
        $accounts = $user ? Account::where('user_id', $user->id)->paginate(10) : collect();

        // Pass data to the view
        return view('home', compact('accounts'));
    }
}
