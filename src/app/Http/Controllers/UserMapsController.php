<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Land;
use App\Models\Station;

class UserMapsController extends Controller
{
    public function index(Request $request)
    {
        // カテゴリDBからデータを選別
        $user = Auth::user();
        
        try {
        $lands = Land::ActiveLand()->SetLatLng()->orderBy('address1', 'desc')->get();
        } catch(\Exception $e) {}

        foreach($lands as $land){
            foreach ($land->lines as $line) {
                $station_name = Station::where('station_cd', $line->pivot->station_cd)->pluck('station_name');
                $line['station_name'] = $station_name; // これでも追加できる
            }
        }
        
        return view("lands.map")->with([
            'user' => $user,
            'lands' => $lands
        ]);
    }
}
