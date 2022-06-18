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
        // $lands_count = DB::table('lands')->count();
        // if ($user->secret_flag === 0) {
        //     $lands_count = Land::ActiveLand()->count();
        // } else {
        //     $lands_count = Land::SecretLand()->count();
        // }
        $lands_count = Land::ActiveLand()->count();

        // $test = Land::where('id','302')->first();
        // dd($test);
        // 新着日付取得用
        // if ($user->secret_flag === 0) {
        //     $new_date = Land::ActiveLand()->orderBy('created_at', 'desc')->first();
        // } else {
        //     $new_date = Land::SecretLand()->orderBy('created_at', 'desc')->first();
        // }
        $new_date = Land::ActiveLand()->orderBy('created_at', 'desc')->first();
        // 更新日付取得用
        $update_date = Land::ActiveLand()->orderBy('updated_at', 'desc')->first();

        // １週間以内のデータ数
        $sevendays = Carbon::today()->subDay(7);
        $seven_count = Land::ActiveLand()->whereDate('created_at', '>=', $sevendays)->count();

        $today = Carbon::today();
        $new_count = Land::ActiveLand()->whereDate('created_at', $today)->count();

        // 最安物件価格
        $low_price = Land::ActiveLand()->orderBy('price', 'asc')->first();
        $high_price = Land::ActiveLand()->orderBy('price', 'desc')->first();

        $lands = Land::ActiveLand()->orderBy('created_at', 'DESC')->take(10)->get();
        // dd($lands->address1);
        foreach ($lands as $land) {
            // １週間以内ならnewバッジをつけるための、boolean設定を追記
            $land['newflag'] = Carbon::parse($today)->between($land->created_at, $land->created_at->addWeek());
            $land['updateflag'] = Carbon::parse($today)->between($land->updated_at, $land->updated_at->addWeek());
            // dd($land);
            foreach ($land->lines as $line) {
                if ($line->pivot->level === 1) {
                    $station_name = Station::where('station_cd', $line->pivot->station_cd)->pluck('station_name');
                    $line['station_name'] = $station_name; // これでも追加できる
                }
            }
        }

        try {
            $maplands = Land::ActiveLand()->orderBy('created_at', 'DESC')->take(10)->SetLatLng()->get();
        } catch (\Exception $e) {
        }


        return view('dashboard', compact('user', 'lands_count', 'new_date', 'update_date', 'seven_count',  'new_count', 'low_price', 'high_price', 'lands', 'maplands'));
    }
}
