<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Train;
use League\Csv\CharsetConverter;
use League\Csv\Writer;
use League\Csv\Reader;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; //ファイルアクセス
use Gate;

class TrainCsvExportController extends Controller
{

  public function download(Request $request)
  {
    // カテゴリDBからデータを選別
    $user = Auth::user();
    // System 以外は不可
    Gate::authorize('isSystem');

    $trains = Train::select(['company_cd', 'company_name', 'display_flag'])->get();

    $count = count($trains);

    if ($count === 0) {
      return Storage::disk('local')->download('csv_template/trains_template.csv');
    } else {
      // 文字コード変換
      // mb_convert_variables('sjis-win', 'UTF-8', $categories);

      // CSVのライターを作成(新規作成)
      $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
      $csv->insertOne(array_keys($trains[0]->getAttributes()));
    }

    // データをcsv用の配列に格納
    foreach ($trains as $train) {
      $csv->insertOne($train->toArray());
    }

    return new Response($csv->getContent(), 200, [
      'Content-Encoding' => 'none',
      'Content-Type' => 'text/csv; charset=UTF-8',
      'Content-Disposition' => 'attachment; filename="trains.csv',
      'Content-Description' => 'File Transfer',
    ]);
  }
}
