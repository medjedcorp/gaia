<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Land;
use Gate;
use Illuminate\Support\Facades\Storage;

class LandAdminController extends Controller
{
    public function index(Request $request)
    {
        // カテゴリDBからデータを選別
        $user = Auth::user();
        // System 以外は不可
        Gate::authorize('isAdmin');
        $lands = Land::select(['bukken_num', 'torihiki_taiyou', 'torihiki_jyoukyou', 'bukken_shumoku', 'price', 'youto_chiki', 'kenpei_rate', 'youseki_rate','land_menseki','heibei_tanka','tsubo_tanka','pref_id','address1','address2','address3','other_address','line_cd1','station_cd1','company','company_tel','contact_tel','eki_toho1','zumen'])->paginate(15);
        // 通常の処理
        // $users = User::select(['id', 'name', 'email', 'role', 'tel', 'accepted', 'created_at'])->orderBy('created_at', 'desc')->get();
        return view("admin.lands")->with([
            'user' => $user,
            'lands' => $lands,
            // 'count' => $count,
        ]);
    }

    public function show(Request $request, $bukken_num)
    {
        // カテゴリDBからデータを選別
        $user = Auth::user();
        // System 以外は不可
        Gate::authorize('isAdmin');
        return view("admin.show")->with([
            'user' => $user,
            'lands' => $lands,
            // 'count' => $count,
        ]);
        return view('admin.show', [
            'user' => $user,
            'lands' => Land::where('bukken_num', $bukken_num)->first()
        ]);
    }


    public function pdfDownload(Request $request)
    {
        // Admin 以外は不可
        Gate::authorize('isAdmin');
        $zumen = $request->zumen;
        $fileName = $zumen . '_zumen.pdf';
        $filePath = '/app/pdfs/' . $zumen . '/' . $fileName;
        return Storage::download($filePath);
    }
}
