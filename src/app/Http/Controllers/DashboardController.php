<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index () 
    {
        $user = Auth::user();
        $lands_count = DB::table('lands')->count();
        return view('dashboard', compact('user', 'lands_count'));
    }
}
