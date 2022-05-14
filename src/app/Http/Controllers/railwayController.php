<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gate;
use App\Models\Land;
use App\Models\LandLine;
use App\Models\Station;
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
        $y = 0;
        $z = 0;

        foreach ($list_datas as $pref) {

            // 県全体でのデータ抽出
            $pref_lands = Land::ActiveLand()->where('prefecture_id',  $pref->prefecture_id)->get();

            // 県に所属する物件の沿線データ抽出
            $land_lines = LandLine::where('bukken_num', $pref_lands->bukken_num)->get();
            // 沿線データ取得
            $lines = Line::where('id', $land_lines->line_id)->get();
            //
            $companies = Train::where('company_cd', $lines->company_cd)->get();

            dd($pref_lands->prefecture_id, $land_lines->bukken_num, $lines->line_name, $companies->company_name);

            $list_datas[$i]['ad1'] = $address1_lists;

            foreach($address1_lists as $address1_list){
                $address2_lists = Land::ActiveLand()->where('prefecture_id',  $pref->prefecture_id)->where('address1',  $address1_list->address1)->select(DB::raw('address2, COUNT(address2) AS address2_count'))->groupBy('address2')->having('address2_count', '>=', 1)->get();
                $list_datas[$i]['ad1'][$k]['ad2'] = $address2_lists;
                $x++;
            }
            # $kを１回リセット。リセットしない場合
            # [0]['ad1'][0]['ad2']　/ [0]['ad1'][1]['ad2'] / [1]['ad1'][2]['ad2'] と連番で入ってしまう
            $x = 0;
            $y++;
        }
        return view('railway.index', compact('user', 'list_datas'));
    }
}
