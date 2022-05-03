<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Land;
use App\Models\Station;
use Gate;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        Gate::authorize('isUser');
        // 物件数
        $lands_count = DB::table('lands')->count();

        // $test = Land::where('id','302')->first();
        // dd($test);
        // 新着日付取得用
        $new_date = Land::orderBy('created_at', 'desc')->first();


        // 更新日付取得用
        $update_date = Land::orderBy('updated_at', 'desc')->first();

        // １週間以内のデータ数
        $sevendays = Carbon::today()->subDay(7);
        $seven_count = Land::whereDate('created_at', '>=', $sevendays)->count();

        $today = Carbon::today();
        $new_count = Land::whereDate('created_at', $today)->count();

        // 最安物件価格
        $low_price = Land::orderBy('price', 'asc')->first();
        $high_price = Land::orderBy('price', 'desc')->first();


        $lands = Land::ActiveLand()->orderBy('created_at', 'DESC')->take(10)->get();
        // dd($lands->address1);
        foreach ($lands as $land) {
            foreach ($land->lines as $line) {
                if ($line->pivot->level === 1) {
                    $station_name = Station::where('station_cd', $line->pivot->station_cd)->pluck('station_name');
                    $line['station_name'] = $station_name; // これでも追加できる
                }
            }
        }

        try {
            $maplands = Land::orderBy('created_at', 'DESC')->take(3)->SetLatLng()->get();
        } catch (\Exception $e) {
        }

        // foreach ($maplands as $mapland) {
        //     foreach ($mapland->lines as $line) {
        //         $station_name = Station::where('station_cd', $line->pivot->station_cd)->pluck('station_name');
        //         $line['station_name'] = $station_name; // これでも追加できる
        //     }
        // }

        // return view("lands.map")->with([
        //     'user' => $user,
        //     'lands' => $lands
        // ]);

        return view('dashboard', compact('user', 'lands_count', 'new_date', 'update_date', 'seven_count',  'new_count', 'low_price', 'high_price', 'lands', 'maplands'));
    }
}
