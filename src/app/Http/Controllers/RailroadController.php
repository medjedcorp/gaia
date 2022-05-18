<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gate;
use App\Models\Land;
use App\Models\LandLine;
use App\Models\Station;
use App\Models\Line;
use App\Models\Train;
use App\Models\Prefecture;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RailroadController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        Gate::authorize('isUser');
        // 例：都道府県ごとの件数
        $list_datas = Land::ActiveLand()
            ->join('prefectures', 'prefectures.id', '=', 'lands.prefecture_id')
            ->select('name', DB::raw('prefecture_id, COUNT(prefecture_id) AS prefecture_id_count'))
            ->groupBy('prefecture_id')
            ->having('prefecture_id_count', '>=', 1)
            ->get();

        $x = 0;

        $land_lines = [];

        foreach ($list_datas as $pref) {
            // dd($list_datas);
            // 県全体でのデータ抽出
            $pref_lands = Land::ActiveLand()->where('prefecture_id',  $pref->prefecture_id)->pluck('bukken_num');
            // var_dump($pref);

            $companies_raw = DB::table('land_line')->whereIn('bukken_num', $pref_lands)
                ->leftJoin('lines', 'land_line.line_id', '=', 'lines.id')->select(DB::raw('company_cd, COUNT(company_cd) AS company_cd_count'))->groupBy('company_cd')->having('company_cd', '>=', 1)->get();
            // 他のデータと結合できるようにコレクションに変換
            // "company_cd" : 4 を "company_cd" => 4 にする
            $companies_count = [];
            foreach ($companies_raw as $company_raw) {
                $company_name = Train::where('company_cd', $company_raw->company_cd)->pluck('company_name')->first();
                $companies_count[] = collect([
                    'company_cd' => $company_raw->company_cd,
                    'company_name' => $company_name,
                    'company_cd_count' => $company_raw->company_cd_count
                ]);
            }

            $list_datas[$x]['company'] = $companies_count;
            $y = 0;
            foreach ($companies_count as $company_count) {
                $lines_raw = DB::table('land_line')->whereIn('bukken_num', $pref_lands)->leftJoin('lines', 'land_line.line_id', '=', 'lines.id')->where('company_cd', $company_count['company_cd'])->select(DB::raw('line_name, lines.line_cd, line_id, COUNT(line_id) AS line_id_count'))->groupBy('line_id')->having('line_id', '>=', 1)->get();

                // 他のデータと結合できるようにコレクションに変換
                $lines = [];
                foreach ($lines_raw as $line_raw) {
                    $lines[] = collect([
                        'line_id' => $line_raw->line_id,
                        'line_cd' => $line_raw->line_cd,
                        'line_name' => $line_raw->line_name,
                        'line_id_count' => $line_raw->line_id_count
                    ]);
                }

                $list_datas[$x]['company'][$y]->put('lines', $lines);
                // dump($list_datas);

                $z = 0;

                foreach ($lines as $line) {
                    $stations_raw = DB::table('land_line')->whereIn('bukken_num', $pref_lands)->leftJoin('stations', 'land_line.station_cd', '=', 'stations.station_cd')->where('stations.line_cd', $line['line_cd'])->select(DB::raw('stations.station_name,land_line.station_cd, COUNT(land_line.station_cd) AS station_cd_count'))->groupBy('station_cd')->having('station_cd', '>=', 1)->get();
                    // dump($line);
                    // dump($stations_raw);
                    // 他のデータと結合できるようにコレクションに変換
                    $stations = [];
                    foreach ($stations_raw as $station_raw) {
                        $stations[] = collect([
                            'station_cd' => $station_raw->station_cd,
                            'station_name' => $station_raw->station_name,
                            'station_cd_count' => $station_raw->station_cd_count
                        ]);
                    }
                    // dump($y);
                    // dump($list_datas[$x]['company'][$y]);
                    // dd($list_datas);
                    $list_datas[$x]['company'][$y]['lines'][$z]->put('stations', $stations);

                    $z++;
                }
                $y++;
            }
            $x++;
        }
        // dd('end');
        // dd($list_datas);
        return view('railroad.index', compact('user', 'list_datas'));
    }

    public function lists(Request $request)
    {
        $user = Auth::user();
        Gate::authorize('isUser');
        // dd($request->pref_id);
        $today = Carbon::today();

        $line_name = Line::where('line_cd', $request->line_cd)->first();
        $st_name = Station::where('station_cd', $request->station_cd)->first();

        if($request->station_cd && $request->line_cd){
            $line_station = LandLine::where('line_cd', $request->line_cd)->where('station_cd', $request->station_cd)->pluck('bukken_num');
            $lands = Land::ActiveLand()->whereIn('bukken_num',  $line_station)->get();
        } else {
            $lands = Land::ActiveLand()->where('prefecture_id',  $request->pref_id)->get();
        }
        // $lands = Land::ActiveLand()->where('prefecture_id',  $request->pref_id)->get();
        $pref_name = Prefecture::where('id',  $request->pref_id)->first();

        foreach ($lands as $land) {
            $land['newflag'] = Carbon::parse($today)->between($land->created_at, $land->created_at->addWeek());
            $land['updateflag'] = Carbon::parse($today)->between($land->updated_at, $land->updated_at->addWeek());
            foreach ($land->lines as $line) {
                if ($line->pivot->level === 1) {
                    $station_name = Station::where('station_cd', $line->pivot->station_cd)->pluck('station_name');
                    $line['station_name'] = $station_name; // これでも追加できる
                }
            }
        }
        // dd($request->pref_id);

        return view("railroad.list")->with([
            'user' => $user,
            'lands' => $lands,
            'line_name' => $line_name,
            'st_name' => $st_name,
            'pref_name' => $pref_name
        ]);
    }
}
