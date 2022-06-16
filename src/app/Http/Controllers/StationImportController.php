<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\CsvFileImportService;
use Illuminate\Http\File;
use App\Jobs\StationImportCsvJob;
use Illuminate\Support\Facades\Auth;
use App\Jobs\CsvFileDeleteJob;
use App\Models\Station;
use League\Csv\Reader;
use League\Csv\Statement;
use Gate;

class StationImportController extends Controller
{
    protected $csv_service;

    public function __construct(Request $request)
    {
        // クラスCSVサービスを使うよ定義？
        $this->csv_service = new CsvFileImportService();
    }

    public function importStationCSV(Request $request)
    {
        // ジョブに渡すためのユーザ情報
        $user = Auth::user();
        // System 以外は不可
        Gate::authorize('isSystem');

        // アップロードファイルに対してのバリデート。Serviceの呼び出し
        $validator = $this->csv_service->validateUploadFile($request);


        if ($validator->fails() === true) {
            // アップロードファイル自体にエラーがあれば出力
            // return redirect('/csv/train')->with('danger', $validator->errors()->first('file'));
            return view('csv.station', [
                'user' => $user,
                'danger' => $validator->errors()->first('file')
            ]);
        }

        $upload_filename = $request->file('file')->getClientOriginalName();
        $file = $request->file('file');
        $filename = 'station_' . date('YmdHis') . '.' . $file->getClientOriginalExtension();

        // ファイルをフォルダに保存
        $csv_path = $file->storeAs(
            'csv/station/import',
            $filename
            // 'public/'.$cid.'/csv/import/', $filename
        );

        // Queueに送信
        StationImportCsvJob::dispatch($upload_filename, $filename, $user, $csv_path);
        // 60分後にファイル削除
        CsvFileDeleteJob::dispatch($csv_path)->delay(now()->addMinutes(60));

        // return redirect('/csv/train')->with('success', 'CSVデータを読み込みました。処理結果はメールでお知らせ致します');
        return view('csv.station', [
            'user' => $user,
            'success' => '※CSVデータを読み込みました。処理結果はメールでお知らせ致します'
        ]);
    }
    
    public function showStation(Request $request){
        $user = Auth::user();
        Gate::authorize('isSystem');
        return view('csv.station', [
            'user' => $user,
        ]);
    }
}
