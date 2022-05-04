<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Land;

class AddressController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        Gate::authorize('isUser');
        $lands = Land::ActiveLand()->all();

        
    }


}
