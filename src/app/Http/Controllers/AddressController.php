<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Land;
use Illuminate\Support\Facades\DB;
use Gate;

class AddressController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        Gate::authorize('isUser');
        // $lands = Land::ActiveLand()->groupBy('prefecture_id')->get(['prefecture_id']);

        // 例：奈良県と大阪府とそれぞれの件数
        $prefs = Land::ActiveLand()
            ->join('prefectures','prefectures.id','=','lands.prefecture_id')
            ->select('name', DB::raw('prefecture_id, COUNT(prefecture_id) AS prefecture_id_count'))
            ->groupBy('prefecture_id')
            ->having('prefecture_id_count', '>=', 1)
            ->get();
        // dd($prefs);
        $address1_lists = [];
        foreach ($prefs as $pref) {
            $address1_lists[] = Land::ActiveLand()->where('prefecture_id',  $pref->prefecture_id)->select(DB::raw('address1, COUNT(address1) AS address1_count'))->groupBy('address1')->having('address1_count', '>=', 1)->get();
        }

        // dd($address1_lists);
        return view('address.index', compact('user', 'prefs', 'address1_lists'));
    }
}
