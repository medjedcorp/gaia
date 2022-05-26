<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Land;
use App\Models\Line;
use App\Models\Station;
use Illuminate\Support\Facades\DB;
use Gate;
use App\Http\Requests\ContactRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserLandContact;
use App\Mail\AdminLandContact;
use Carbon\Carbon;
use App\Services\UserAgentService;

class LandUserController extends Controller
{
    private $agent;

    public function __construct()
    {
        $this->agent = new UserAgentService();
    }

    public function index(Request $request)
    {
        // カテゴリDBからデータを選別
        $user = Auth::user();
        // Admin 以外は不可
        Gate::authorize('isUser');

        // ユーザーエージェントで分岐
        $terminal = $this->agent->GetAgent($request);

        if ($request->keyword) {
            // dd('test');
            $keyword = $request->keyword;

            if ($terminal === 'mobile') {
                // キーワードから検索。県名・住所を結合
                $lands = Land::ActiveLand()
                    ->join('prefectures', 'prefectures.id', '=', 'lands.prefecture_id')
                    ->where(DB::raw('CONCAT(name, address1, address2)'), 'like', '%' . $keyword . '%')
                    ->orderBy('lands.address1', 'desc')
                    ->paginate(15);
            } else {
                // キーワードから検索。県名・住所を結合
                $lands = Land::ActiveLand()
                    ->join('prefectures', 'prefectures.id', '=', 'lands.prefecture_id')
                    ->where(DB::raw('CONCAT(name, address1, address2)'), 'like', '%' . $keyword . '%')
                    ->orderBy('lands.address1', 'desc')
                    ->get();
            }
        } else {
            if ($terminal === 'mobile') {
                $lands = Land::ActiveLand()->paginate(15);
                // dd('スマホ', $lands,);
            } else {
                $lands = Land::ActiveLand()->get();
                // dd('pc', $lands);
            }
            // $lands = Land::ActiveLand()->get();

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
            'lands' => $lands,
            'terminal' => $terminal
        ]);
    }

    public function show($bukken_num)
    {
        // カテゴリDBからデータを選別
        $user = Auth::user();
        // System 以外は不可
        Gate::authorize('isUser');

        $land = Land::where('bukken_num', $bukken_num)->first();
        $location = Land::where('bukken_num', $bukken_num)->selectRaw("ST_X( location ) As latitude, ST_Y( location ) As longitude")->first();

        foreach ($land->lines as $line) {
            $station_name = Station::where('station_cd', $line->pivot->station_cd)->pluck('station_name');
            $line['station_name'] = $station_name; // これでも追加できる
        }

        return view('lands.show', [
            'user' => $user,
            'land' => $land,
            'location' => $location
        ]);
    }

    // public function contact($bukken_num)
    // {
    //     // カテゴリDBからデータを選別
    //     $user = Auth::user();
    //     // System 以外は不可
    //     Gate::authorize('isUser');

    //     $land = Land::where('bukken_num', $bukken_num)->first();

    //     return view('lands.contact', [
    //         'user' => $user,
    //         'land' => $land
    //     ]);
    // }

    public function thanks(ContactRequest $request)
    {
        // カテゴリDBからデータを選別
        $user = Auth::user();
        // System 以外は不可
        Gate::authorize('isUser');

        // $land = Land::where('bukken_num', $bukken_num)->first();

        $to   = $request->email;
        $name   = $request->name;
        $bukken_num   = $request->bukken_num;
        $tel   = $request->tel;
        $other   = $request->other;
        $contact_mail = config('const.contact_mail');
        $contacts = $request->contact;

        // dd($bukken_num);
        $value = '';

        if ($contacts) {
            foreach ($contacts as $contact) {
                if ($contact === 'look') {
                    $value .= '『※物件を実際に見たい』';
                } elseif ($contact === 'know') {
                    $value .= '『※物件の詳しい情報を知りたい』';
                } elseif ($contact === 'consultant') {
                    $value .= '『※ローン・購入に関して相談したい』';
                }
            }
        }

        Mail::to($to)->send(new UserLandContact($name, $bukken_num, $value, $other));
        Mail::to($contact_mail)->send(new AdminLandContact($name, $bukken_num, $tel, $contact_mail, $value, $other));

        return view('lands.thanks', [
            'user' => $user,
            'to' => $to,
            'name' => $name,
            'bukken_num' => $bukken_num,
            'bukken_num' => $bukken_num,
            'tel' => $tel,
            'other' => $other,
            'value' => $value,
        ]);
    }

    public function new(Request $request)
    {
        // カテゴリDBからデータを選別
        $user = Auth::user();
        // Admin 以外は不可
        Gate::authorize('isUser');
        $today = Carbon::today();
        $week = Carbon::now()->subWeek(1);

        // ユーザーエージェントで分岐
        $terminal = $this->agent->GetAgent($request);

        if ($request->keyword) {
            // dd('test');
            $keyword = $request->keyword;
            if ($terminal === 'mobile') {
                $lands = Land::ActiveLand()
                    ->join('prefectures', 'prefectures.id', '=', 'lands.prefecture_id')
                    ->where(DB::raw('CONCAT(name, address1, address2)'), 'like', '%' . $keyword . '%')
                    ->whereBetween('lands.created_at', [$week, $today])
                    ->orderBy('lands.created_at', 'desc')
                    ->paginate(15);
            } else {
                // キーワードから検索。県名・住所を結合
                $lands = Land::ActiveLand()
                    ->join('prefectures', 'prefectures.id', '=', 'lands.prefecture_id')
                    ->where(DB::raw('CONCAT(name, address1, address2)'), 'like', '%' . $keyword . '%')
                    ->whereBetween('lands.created_at', [$week, $today])
                    ->orderBy('lands.created_at', 'desc')
                    ->get();
            }
        } else {
            if ($terminal === 'mobile') {
                $lands = Land::ActiveLand()
                    ->orderBy('lands.created_at', 'desc')
                    ->whereBetween('lands.created_at', [$week, $today])
                    ->paginate(15);
                // dd('スマホ', $lands,);
            } else {
                $lands = Land::ActiveLand()
                    ->orderBy('lands.created_at', 'desc')
                    ->whereBetween('lands.created_at', [$week, $today])
                    ->get();
                // dd('pc', $lands);
            }
            // $lands = Land::ActiveLand()->get();

        }

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

        return view("lands.new")->with([
            'user' => $user,
            'lands' => $lands,
            'terminal' => $terminal
        ]);
    }
}
