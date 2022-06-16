<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Station;
use League\Csv\CharsetConverter;
use League\Csv\Writer;
use League\Csv\Reader;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; //ファイルアクセス
use Gate;

class StationCsvExportController extends Controller
{

  public function download(Request $request)
  {
    // カテゴリDBからデータを選別
    $user = Auth::user();
    // System 以外は不可
    Gate::authorize('isSystem');

    $stations = Station::selectRaw("station_cd, station_g_cd, station_name, line_cd, pref_cd, post, address, ST_X( location ) As lat, ST_Y( location ) As lon, display_flag")->get();

    $count = count($stations);

    if ($count === 0) {
      return Storage::disk('local')->download('csv_template/stations_template.csv');
    } else {
      // 文字コード変換 sjisの場合
      // mb_convert_variables('sjis-win', 'UTF-8', $categories);

      // CSVのライターを作成(新規作成)
      $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
      $csv->insertOne(array_keys($stations[0]->getAttributes()));
    }

    // データをcsv用の配列に格納
    foreach ($stations as $station) {
      $csv->insertOne($station->toArray());
    }

    return new Response($csv->getContent(), 200, [
      'Content-Encoding' => 'none',
      'Content-Type' => 'text/csv; charset=UTF-8',
      'Content-Disposition' => 'attachment; filename="stations.csv',
      'Content-Description' => 'File Transfer',
    ]);
  }
}
