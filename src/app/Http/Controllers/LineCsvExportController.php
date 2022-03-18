<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Line;
use League\Csv\CharsetConverter;
use League\Csv\Writer;
use League\Csv\Reader;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; //ファイルアクセス
use Gate;

class LineCsvExportController extends Controller
{

  public function download(Request $request)
  {
    // カテゴリDBからデータを選別
    $user = Auth::user();
    // System 以外は不可
    Gate::authorize('isSystem');

    $lines = Line::selectRaw("line_cd, company_cd, line_name, line_color_c, ST_X( location ) As lat, ST_Y( location ) As lon, display_flag")->get();

    $count = count($lines);

    if ($count === 0) {
      return Storage::disk('local')->download('csv_template/lines_template.csv');
    } else {
      // 文字コード変換
      // mb_convert_variables('sjis-win', 'UTF-8', $categories);

      // CSVのライターを作成(新規作成)
      $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
      $csv->insertOne(array_keys($lines[0]->getAttributes()));
    }

    // データをcsv用の配列に格納
    foreach ($lines as $line) {
      $csv->insertOne($lines->toArray());
    }

    return new Response($csv->getContent(), 200, [
      'Content-Encoding' => 'none',
      'Content-Type' => 'text/csv; charset=SJIS-win',
      'Content-Disposition' => 'attachment; filename="lines.csv',
      'Content-Description' => 'File Transfer',
    ]);
  }
}
