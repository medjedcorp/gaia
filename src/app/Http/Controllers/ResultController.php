<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function currentLocation(Request $request)
    {
        $lat = $request->lat;
        $lng = $request->lng;
        // currentLocationで表示
        return view('mapsLocation', [
            // 現在地緯度latをbladeへ渡す
            'lat' => $lat,
            // 現在地経度lngをbladeへ渡す
            'lng' => $lng,
        ]);
    }
}
