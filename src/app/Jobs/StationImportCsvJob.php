<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Validator;
use App\Services\StationImportService;
use Illuminate\Support\Facades\Mail;
use App\Mail\CsvErrorMail;
use App\Mail\CsvSuccessMail;
use Illuminate\Support\Facades\Storage;
use League\Csv\CharsetConverter;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Http\Request;
use App\Models\Station;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StationImportCsvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     * 
     */
    public $timeout = 600;

    protected $user;
    protected $upload_filename;
    protected $filename;
    protected $csv_service;
    protected $csv_path;

    public function __construct($upload_filename, $filename, $user, $csv_path)
    {
        $this->csv_service = new StationImportService();
        $this->upload_filename = $upload_filename;
        $this->filename = $filename;
        $this->user = $user;
        $this->csv_path = $csv_path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // user情報取得
        $name = $this->user->name;
        $to   = $this->user->email;

        $csv = Reader::createFromPath(storage_path('app/' . $this->csv_path), 'r');
        $csv->setHeaderOffset(0); //headerは何行目か

        //UTF-8に変換
        // CharsetConverter::addTo($csv, 'SJIS-win', 'UTF-8');

        $error_list = [];
        $count = 1;

        //csv_serviceの中のバリデーション
        // Log::debug($csv);
        
        foreach ($csv as $row) {
            //csv_serviceの中のバリデーション
            // Log::debug($row);
            $validator = Validator::make(
                $row,
                $this->csv_service->validationRules(),
                $this->csv_service->validationMessages(),
                $this->csv_service->validationAttributes()
            );

            if ($validator->fails() === true) {
                $error_list[$count] = $validator->errors()->all();
            }

            $count++;
        }

        // エラーがあれば終わり
        if (count($error_list) > 0) {
            // ファイル名設定
            $fname = 'csv/error/' . 'stations_' . date('YmdHis') . '.txt';
            Storage::disk('public')->put($fname, "");
            // $path = storage_path('public/'.$fname);
            $path = url('storage/' . $fname);

            // Storage::disk('public')->append($fname, '※ヘッダーを除いた行数です');

            // エラーログをテキストで出力
            $err_num = 1;
            // $error = errorlist($error_list); ?いるやつ？
            foreach ($error_list as $key => $val) {
                $key++;
                foreach ($val as $msg) {
                    $err_num++;
                    $txt_list = 'データ' . $key . '行目：' . $msg;
                    Storage::disk('public')->append($fname, $txt_list);
                }
                if ($err_num > 100) {
                    $txt_list = 'エラー行数が100件を超えました。csvの内容を再度確認してください';
                    Storage::disk('public')->append($fname, $txt_list);
                    break;
                }
            }

            // エラーメール送信処理
            Mail::to($to)->send(new CsvErrorMail($name, $path, $this->upload_filename));

            // // 3日後にファイル削除
            CsvFileDeleteJob::dispatch($path)->delay(now()->addDays(3));
        } else {
            // 成功時の処理
            foreach ($csv as $row_data => $v) {

                // 保存処理。saveで対応。
                $station = Station::where('station_cd', $v['station_cd'])->first();
                if (empty($station)) {
                    $station = new Station;
                }
                $station->station_cd = $v['station_cd'];
                $station->station_g_cd = $v['station_g_cd'];
                $station->station_name = $v['station_name'];
                $station->line_cd = $v['line_cd'];
                $station->pref_cd = $v['pref_cd'];
                $station->post = $v['post'];
                $station->address = $v['address'];

                if (isset($v['lon'])) {
                    $lon = $v['lon'];
                } elseif( empty($station->location) ) {
                    $lon = null;
                }
                if (isset($v['lat'])) {
                    $lat = $v['lat'];
                } elseif( empty($station->location) ) {
                    $lat = null;
                }

                $station->location = DB::raw("ST_GeomFromText('POINT(" . $lat . " " . $lon . ")')");

                if (isset($v['display_flag'])) {
                    $station->display_flag = $v['display_flag'];
                } elseif( empty($station->display_flag) ) {
                    $station->display_flag = 0;
                }
                $station->save();
            }
            // 成功メール送信
            Mail::to($to)->send(new CsvSuccessMail($name, $this->upload_filename));
        }
    }
}
