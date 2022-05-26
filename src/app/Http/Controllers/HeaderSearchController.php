<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HeaderSearchController extends Controller
{
    public function index(Request $request)
    {
        // カテゴリDBからデータを選別
        $user = Auth::user();
        // Admin 以外は不可
        Gate::authorize('isUser');
        if($request->keyword){
            dd('test');
        } else {
            $lands = Land::ActiveLand()->get();
        }
        
        $today = Carbon::today();
        // dd($lands->address1);
        foreach ($lands as $land) {
            // １週間以内ならnewバッジをつけるための、boolean設定を追記
            $land['newflag'] = Carbon::parse($today)->between($land->created_at, $land->created_at->addWeek());
            $land['updateflag'] = Carbon::parse($today)->between($land->updated_at, $land->updated_at->addWeek());
            foreach ($land->lines as $line) {
                if ($line->pivot->level === 1) {
                    $station_name = Station::where('station_cd', $line->pivot->station_cd)->pluck('station_name');
                    $line['station_name'] = $station_name; // これでも追加できる
                }
            }
        }

        return view("lands.index")->with([
            'user' => $user,
            'lands' => $lands
        ]);
    }
}
