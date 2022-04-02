<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Land;
use App\Models\Line;
use App\Models\Station;
use Gate;

class LandUserController extends Controller
{
    public function show(Request $request, $bukken_num)
    {
        // カテゴリDBからデータを選別
        $user = Auth::user();
        // System 以外は不可
        Gate::authorize('isAdmin');
        if(isset($request->display_flag)){
            $land = Land::where('bukken_num', $bukken_num)->first();
            $land->display_flag = $request->display_flag;
            $land->save();
        }
        $land = Land::where('bukken_num', $bukken_num)->first();
        $location = Land::where('bukken_num', $bukken_num)->selectRaw("ST_X( location ) As latitude, ST_Y( location ) As longitude")->first();

        foreach ($land->lines as $line) {
            $station_name = Station::where('station_cd', $line->pivot->station_cd)->pluck('station_name');
            $line['station_name'] = $station_name; // これでも追加できる
        }

        return view('landshow', [
            'user' => $user,
            'land' => $land,
            'location' => $location
        ]);
    }
}
