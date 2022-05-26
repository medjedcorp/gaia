<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Land;
use App\Models\Station;
use App\Models\Prefecture;
use Illuminate\Support\Facades\DB;
use Gate;
use Carbon\Carbon;
use App\Services\UserAgentService;

class AddressController extends Controller
{
    private $agent;

    public function __construct()
    {
        $this->agent = new UserAgentService();
    }

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

        $i = 0;
        $k = 0;
        foreach ($list_datas as $pref) {
            $address1_lists = Land::ActiveLand()->where('prefecture_id',  $pref->prefecture_id)->select(DB::raw('address1, COUNT(address1) AS address1_count'))->groupBy('address1')->having('address1_count', '>=', 1)->get();

            $list_datas[$i]['ad1'] = $address1_lists;

            foreach ($address1_lists as $address1_list) {
                $address2_lists = Land::ActiveLand()->where('prefecture_id',  $pref->prefecture_id)->where('address1',  $address1_list->address1)->select(DB::raw('address2, COUNT(address2) AS address2_count'))->groupBy('address2')->having('address2_count', '>=', 1)->get();
                $list_datas[$i]['ad1'][$k]['ad2'] = $address2_lists;
                $k++;
            }
            # $kを１回リセット。リセットしない場合
            # [0]['ad1'][0]['ad2']　/ [0]['ad1'][1]['ad2'] / [1]['ad1'][2]['ad2'] と連番で入ってしまう
            $k = 0;
            $i++;
        }

        return view('address.index', compact('user', 'list_datas'));
    }

    public function lists(Request $request)
    {
        $user = Auth::user();
        Gate::authorize('isUser');

        // ユーザーエージェントで分岐
        $terminal = $this->agent->GetAgent($request);

        // dd($request->pref_id);
        $today = Carbon::today();
        $pref_name = Prefecture::where('id',  $request->pref_id)->first();
        $keyword = null;

        if ($terminal === 'mobile') {
            if ($request->ad2) {
                $lands = Land::ActiveLand()->where('prefecture_id',  $request->pref_id)->where('address1',  $request->ad1)->where('address2', $request->ad2)->paginate(15);
                $keyword = $pref_name->name . $request->ad1 . $request->ad2;
            } elseif ($request->ad1) {
                $lands = Land::ActiveLand()->where('prefecture_id',  $request->pref_id)->where('address1',  $request->ad1)->paginate(15);
                $keyword = $pref_name->name . $request->ad1;
            } else {
                $lands = Land::ActiveLand()->where('prefecture_id',  $request->pref_id)->paginate(15);
            }
        } else {
            if ($request->ad2) {
                $lands = Land::ActiveLand()->where('prefecture_id',  $request->pref_id)->where('address1',  $request->ad1)->where('address2', $request->ad2)->get();
            } elseif ($request->ad1) {
                $lands = Land::ActiveLand()->where('prefecture_id',  $request->pref_id)->where('address1',  $request->ad1)->get();
            } else {
                $lands = Land::ActiveLand()->where('prefecture_id',  $request->pref_id)->get();
            }
        }

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

        return view("lands.index")->with([
            'user' => $user,
            'lands' => $lands,
            'keyword' => $keyword
        ]);
        // return view("address.list")->with([
        //     'user' => $user,
        //     'lands' => $lands,
        //     'pref_name' => $pref_name
        // ]);
    }
}
