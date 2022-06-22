<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Land;
use App\Models\Line;
use App\Models\Station;
use Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use App\Services\UserAgentService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// use Illuminate\Support\Facades\File;
// use Illuminate\Pagination\LengthAwarePaginator;
// use Illuminate\Support\Str;

class LandAdminController extends Controller
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
        Gate::authorize('isAdmin');

        // ユーザーエージェントで分岐
        $terminal = $this->agent->GetAgent($request);

        if ($request->keyword) {
            $keyword = $request->keyword;
            if ($terminal === 'mobile') {
                $lands = Land::join('prefectures', 'prefectures.id', '=', 'lands.prefecture_id')
                    ->where(DB::raw('CONCAT(name, address1, address2, company, bukken_num, ad_kubun, created_at)'), 'like', '%' . $keyword . '%')
                    ->orderBy('lands.address1', 'desc')
                    ->paginate(15);
            } else {
                $lands = Land::orderBy('address1', 'desc')->get();
                // dd('pc', $lands);
            }
        } else {
            if ($terminal === 'mobile') {
                $lands = Land::orderBy('address1', 'desc')->paginate(15);
                // dd('スマホ', $lands,);
            } else {
                $lands = Land::orderBy('address1', 'desc')->get();
                // dd('pc', $lands);
            }
        }

        $today = Carbon::today();

        foreach ($lands as $land) {
            $land['newflag'] = Carbon::parse($today)->between($land->created_at, $land->created_at->addWeek());
            if($land->ad_kubun !== "広告可"){
                $land['adflag'] = false;
            } else {
                $land['adflag'] = true;
            }
            foreach ($land->lines as $line) {
                if ($line->pivot->level === 1) {
                    $station_name = Station::where('station_cd', $line->pivot->station_cd)->pluck('station_name');
                    $line['station_name'] = $station_name; // これでも追加できる
                }
            }
        }

        return view("admin.lands")->with([
            'user' => $user,
            'lands' => $lands
        ]);
    }

    public function show(Request $request, $bukken_num)
    {
        // カテゴリDBからデータを選別
        $user = Auth::user();
        // System 以外は不可
        Gate::authorize('isAdmin');
        if (isset($request->display_flag)) {
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

        return view('admin.show', [
            'user' => $user,
            'land' => $land,
            'location' => $location
        ]);
    }


    public function pdfDownload(Request $request)
    {
        // Admin 以外は不可
        Gate::authorize('isAdmin');
        $zumen = $request->zumen;
        $fileName = $zumen . '_zumen.pdf';
        $filePath = '/pdfs/' . $zumen . '/' . $fileName;
        // if(File::exists(Storage::download($filePath))){
        if (Storage::disk('local')->exists($filePath)) {
            return Storage::download($filePath);
        } else {
            return back()->with('notfound', 'ファイルが見つかりませんでした');
            // return 'File Not Found';
        }
    }
}
