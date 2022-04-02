<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Land;
use App\Models\Line;
use App\Models\Station;
use Gate;
use App\Http\Requests\ContactRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserLandContact;
use App\Mail\AdminLandContact;

class LandUserController extends Controller
{
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

        return view('landshow', [
            'user' => $user,
            'land' => $land,
            'location' => $location
        ]);
    }

    public function contact($bukken_num)
    {
        // カテゴリDBからデータを選別
        $user = Auth::user();
        // System 以外は不可
        Gate::authorize('isUser');

        $land = Land::where('bukken_num', $bukken_num)->first();

        return view('landcontact', [
            'user' => $user,
            'land' => $land
        ]);
    }

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

        return view('landthanks', [
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
}
