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
            foreach ($companies_raw as $company_raw) {
                $companies_count[] = collect([
                    'company_cd' => $company_raw->company_cd,
                    'company_cd_count' => $company_raw->company_cd_count
                ]);
            }

            $list_datas[$x]['company'] = $companies_count;
            $y = 0;
            foreach ($companies_count as $company_count) {

                $lines_raw = DB::table('land_line')->whereIn('bukken_num', $pref_lands)->leftJoin('lines', 'land_line.line_id', '=', 'lines.id')->where('company_cd', $company_count['company_cd'])->select(DB::raw('line_id, COUNT(line_id) AS line_id_count'))->groupBy('line_id')->having('line_id', '>=', 1)->get();

                // 他のデータと結合できるようにコレクションに変換
                foreach ($lines_raw as $line_raw) {
                    $lines[] = collect([
                        'line_id' => $line_raw->line_id,
                        'line_id_count' => $line_raw->line_id_count
                    ]);
                }

                $list_datas[$x]['company'][$y]->put('lines',$lines);

                $y++;
            }

            // var_dump($x);
            $x++;
        }
        // dd('end');
        // dd($list_datas);
        return view('railway.index', compact('user', 'list_datas'));
    }
}
