<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
// use App\Eloquent\User;
// use Illuminate\Http\Request;
use League\Csv\CharsetConverter;
use League\Csv\Reader;
use League\Csv\Statement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Land;
use App\Models\Line;
use App\Models\LandLine;
use App\Models\Prefecture;
use App\Models\Station;
use Illuminate\Support\Facades\Storage;
use App\Services\LineNotifyService;
use App\Services\SeirekiService;

class ImportReCsv extends Command
{

    private $notify;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:importrecsv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'レインズCSVを取り込みます';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->notify = new LineNotifyService();
        $this->seireki = new SeirekiService();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Statement $stmt)
    {
        // return 0;
        $ymd = Carbon::today()->format('Ymd');
        $file_name = 'estate' . $ymd . '.csv';

        $csv = Reader::createFromString(Storage::get('/csv/land/' . $file_name))->setHeaderOffset(0);
        //UTF-8に変換
        // CharsetConverter::addTo($csv, 'SJIS-win', 'UTF-8');

        $records = $stmt->process($csv);
        $land_data = [];
        $land_line_data = [];
        // line nameにない沿線が出てきた場合変換する。出てきたら追記すること
        // DB の値 => csv の値とする
        $exception_line = [
            '生駒ケーブル' => '生駒鋼索線',
            '万葉まほろば線' => '桜井線',
            '関西本線' => '関西線',
            '大阪メトロ長堀鶴見緑地線' => '長堀鶴見線',
            '大阪メトロ中央線' => '大阪市中央線',
        ];

        // $bukkens = [];

        foreach ($records as $record) {
            $lands = Land::where('bukken_num', $record['bukken_num'])->first();
            $bukken_num = $record['bukken_num'];

            // DBにない値をチェック
            $line_result1 = array_search($record['line_cd1'], $exception_line);
            $line_result2 = array_search($record['line_cd2'], $exception_line);
            $line_result3 = array_search($record['line_cd3'], $exception_line);

            // var_dump($bukken_num,$record['line_cd1'],$line_result1,$line_result2,$line_result3);

            // DBにない値がある場合は、kyeとvalueを入替て文字列にして、入れなおす。登録のない沿線を修正する処理
            if ($line_result1) {
                $record['line_cd1'] = implode(array_keys($exception_line, $record['line_cd1']));
            }
            if ($line_result2) {
                $record['line_cd2'] = implode(array_keys($exception_line, $record['line_cd2']));
            }
            if ($line_result3) {
                $record['line_cd3'] = implode(array_keys($exception_line, $record['line_cd3']));
            }

            // 沿線１を処理
            if ($record['line_cd1']) {
                $line_name1 = $record['line_cd1']; // 例 南大阪線
                $station_name1 = $record['station_cd1'];

                $line_cd1_count = Line::where('line_name', 'like', '%' . $line_name1 . '%')->count();
                if($line_cd1_count === 1){
                    $line_cd1 = Line::where('line_name', 'like', '%' . $line_name1 . '%')->first();
                } else {
                    $line_cd1 = DB::table('lines')
                    ->leftJoin('stations', 'lines.line_cd', '=', 'stations.line_cd')
                    ->where('station_name', 'like', '%' . $station_name1 . '%')
                    ->where('line_name', 'like', '%' . $line_name1 . '%')
                    ->select('lines.id','lines.line_cd', 'line_name')
                    ->get(); 
                    $line_cd1 = $line_cd1->toArray();
                    $line_cd1 = $line_cd1[0];
                }

                $land_line_record['line_id'] = optional($line_cd1)->id;
                $land_line_record['bukken_num'] = $bukken_num;
                $land_line_record['line_cd'] = optional($line_cd1)->line_cd;

                if ($record['station_cd1']) {

                    if(is_null($line_cd1)){
                        Log::debug($line_name1 . "が見つかりませんでした");
                        continue;
                    } else {
                        $station_cd1 = DB::table('stations')->where('line_cd', $line_cd1->line_cd)->where('station_name', 'like', '%' . $station_name1 . '%')->value('station_cd');
                    }

                    $land_line_record['station_cd'] = $station_cd1;
                } else {
                    $land_line_record['station_cd'] = null;
                }
                if ($record['eki_toho1']) {
                    $land_line_record['eki_toho'] = $record['eki_toho1'];
                } else {
                    $land_line_record['eki_toho'] = null;
                }
                if ($record['eki_car1']) {
                    $land_line_record['eki_car'] = $record['eki_car1'];
                } else {
                    $land_line_record['eki_car'] = null;
                }
                if ($record['eki_bus1']) {
                    $land_line_record['eki_bus'] = $record['eki_bus1'];
                } else {
                    $land_line_record['eki_bus'] = null;
                }
                if ($record['bus_toho1']) {
                    $land_line_record['bus_toho'] = $record['bus_toho1'];
                } else {
                    $land_line_record['bus_toho'] = null;
                }
                if ($record['bus_route1']) {
                    $land_line_record['bus_route'] = $record['bus_route1'];
                } else {
                    $land_line_record['bus_route'] = null;
                }
                if ($record['bus_stop1']) {
                    $land_line_record['bus_stop'] = $record['bus_stop1'];
                } else {
                    $land_line_record['bus_stop'] = null;
                }
                $land_line_record['level'] = 1;
                $land_line_data[] = $land_line_record;
            }

            if ($record['line_cd2']) {
                $line_name2 = $record['line_cd2'];
                $station_name2 = $record['station_cd2'];
                // dump($line_name2);
                // $line_cd2 = DB::table('lines')->where('line_name', 'like', '%' . $line_name2 . '%')->where('station_name', 'like', '%' . $station_name2 . '%')->first();
                // $line_cd2 = Line::where('line_name', 'like', '%' . $line_name2 . '%')->whereHas('stations', function($query) use($station_name2){
                //     $query->where('station_name', 'like', '%' . $station_name2 . '%');
                // })->first();
                // dump($line_cd2->line_cd);
                // $land_line_record['land_id'] = optional($lands)->id;
                $line_cd2_count = Line::where('line_name', 'like', '%' . $line_name2 . '%')->count();
                if($line_cd2_count === 1){
                    $line_cd2 = Line::where('line_name', 'like', '%' . $line_name2 . '%')->first();
                } else {
                    $line_cd2 = DB::table('lines')
                    ->leftJoin('stations', 'lines.line_cd', '=', 'stations.line_cd')
                    ->where('station_name', 'like', '%' . $station_name2 . '%')
                    ->where('line_name', 'like', '%' . $line_name2 . '%')
                    ->select('lines.id','lines.line_cd', 'line_name')
                    ->get(); 
                    $line_cd2 = $line_cd2->toArray();
                    $line_cd2 = $line_cd2[0];
                }
                
                // dump($line_cd2);
                // dump(optional($line_cd2)->id);

                $land_line_record['line_id'] = optional($line_cd2)->id;
                $land_line_record['bukken_num'] = $bukken_num;
                $land_line_record['line_cd'] = optional($line_cd2)->line_cd;
                if ($record['station_cd2']) {

                    if(is_null($line_cd2)){
                        Log::debug($line_name2 . "が見つかりませんでした");
                        continue;
                    } else {
                        $station_cd2 = DB::table('stations')->where('line_cd', $line_cd2->line_cd)->where('station_name', 'like', '%' . $station_name2 . '%')->value('station_cd');
                    }

                    $land_line_record['station_cd'] = $station_cd2;
                } else {
                    $land_line_record['station_cd'] = null;
                }
                if ($record['eki_toho2']) {
                    $land_line_record['eki_toho'] = $record['eki_toho2'];
                } else {
                    $land_line_record['eki_toho'] = null;
                }
                if ($record['eki_car2']) {
                    $land_line_record['eki_car'] = $record['eki_car2'];
                } else {
                    $land_line_record['eki_car'] = null;
                }
                if ($record['eki_bus2']) {
                    $land_line_record['eki_bus'] = $record['eki_bus2'];
                } else {
                    $land_line_record['eki_bus'] = null;
                }
                if ($record['bus_toho2']) {
                    $land_line_record['bus_toho'] = $record['bus_toho2'];
                } else {
                    $land_line_record['bus_toho'] = null;
                }
                if ($record['bus_route2']) {
                    $land_line_record['bus_route'] = $record['bus_route2'];
                } else {
                    $land_line_record['bus_route'] = null;
                }
                if ($record['bus_stop2']) {
                    $land_line_record['bus_stop'] = $record['bus_stop2'];
                } else {
                    $land_line_record['bus_stop'] = null;
                }
                $land_line_record['level'] = 2;
                $land_line_data[] = $land_line_record;
            }
            if ($record['line_cd3']) {
                $line_name3 = $record['line_cd3'];
                $station_name3 = $record['station_cd3'];

                $line_cd3_count = Line::where('line_name', 'like', '%' . $line_name3 . '%')->count();
                if($line_cd3_count === 1){
                    $line_cd3 = Line::where('line_name', 'like', '%' . $line_name3 . '%')->first();
                } else {
                    $line_cd3 = DB::table('lines')
                    ->leftJoin('stations', 'lines.line_cd', '=', 'stations.line_cd')
                    ->where('station_name', 'like', '%' . $station_name3 . '%')
                    ->where('line_name', 'like', '%' . $line_name3 . '%')
                    ->select('lines.id','lines.line_cd', 'line_name')
                    ->get(); 
                    $line_cd3 = $line_cd3->toArray();
                    $line_cd3 = $line_cd3[0];
                }

                $land_line_record['line_id'] = optional($line_cd3)->id;
                $land_line_record['bukken_num'] = $bukken_num;
                $land_line_record['line_cd'] = optional($line_cd3)->line_cd;
                if ($record['station_cd3']) {
                    if(is_null($line_cd3)){
                        Log::debug($line_name3 . "が見つかりませんでした");
                        continue;
                    } else {
                        $station_cd3 = DB::table('stations')->where('line_cd', $line_cd3->line_cd)->where('station_name', 'like', '%' . $station_name3 . '%')->value('station_cd');
                    }

                    $land_line_record['station_cd'] = $station_cd3;
                } else {
                    $land_line_record['station_cd'] = null;
                }
                if ($record['eki_toho3']) {
                    $land_line_record['eki_toho'] = $record['eki_toho3'];
                } else {
                    $land_line_record['eki_toho'] = null;
                }
                if ($record['eki_car3']) {
                    $land_line_record['eki_car'] = $record['eki_car3'];
                } else {
                    $land_line_record['eki_car'] = null;
                }
                if ($record['eki_bus3']) {
                    $land_line_record['eki_bus'] = $record['eki_bus3'];
                } else {
                    $land_line_record['eki_bus'] = null;
                }
                if ($record['bus_toho3']) {
                    $land_line_record['bus_toho'] = $record['bus_toho3'];
                } else {
                    $land_line_record['bus_toho'] = null;
                }
                if ($record['bus_route3']) {
                    $land_line_record['bus_route'] = $record['bus_route3'];
                } else {
                    $land_line_record['bus_route'] = null;
                }
                if ($record['bus_stop3']) {
                    $land_line_record['bus_stop'] = $record['bus_stop3'];
                } else {
                    $land_line_record['bus_stop'] = null;
                }
                $land_line_record['level'] = 3;
                $land_line_data[] = $land_line_record;
            }

            if ($lands) {
                // DBにある場合
                $record['display_flag'] = $lands->display_flag;
                $record['location'] = $lands->location;
                $record['created_at'] = $lands->created_at;
            } else {
                // DBにない場合
                // geocoding 緯度経度を取得
                $record['display_flag'] = 1;
                $pref = Prefecture::where('id', $record['prefecture_id'])->first();
                // $address2 = $pref->name . $record['address1'] . $record['address2'] . $record['address3'] . $record['other_address'];
                $address2 = $pref->name . $record['address1'] . $record['address2'] . $record['address3'];
                $myKey = config('const.geo_key');
                $address = urlencode($address2);
                $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $address . "+CA&key=" . $myKey;
                $contents = file_get_contents($url);
                $jsonData = json_decode($contents, true);
                if (is_null($jsonData)) {
                    $lat = 0;
                    $lng = 0;
                } else {
                    if($jsonData["status"] === "ZERO_RESULTS"){
                        Log::debug($bukken_num . "の位置情報取得に失敗しました");
                        continue;
                    } else {
                        $lat = $jsonData["results"][0]["geometry"]["location"]["lat"];
                        $lng = $jsonData["results"][0]["geometry"]["location"]["lng"];
                    }

                }
                $record['location'] = DB::raw("ST_GeomFromText('POINT(" . $lat . " " . $lng . ")')");
                // $created_at = $lands->touroku_date;
                // dump($record);
                // $record['created_at'] = $this->seireki->convJtGDate($lands->touroku_date);
                $record['created_at'] = $this->seireki->convJtGDate($record['touroku_date']);
                // dump($this->seireki->convJtGDate($lands->touroku_date));
                // dd($created_at);
                // $record['created_at'] = now();
            }
            // $update_at = $lands->update_date;
            $record['updated_at'] = $this->seireki->convJtGDate($record['update_date']);
            // dd($update_at);
            // $record['updated_at'] = now();
            // $record['updated_at'] = now();
            // 別で保存するから削除
            unset($record['line_cd1']);
            unset($record['line_cd2']);
            unset($record['line_cd3']);
            unset($record['station_cd1']);
            unset($record['station_cd2']);
            unset($record['station_cd3']);
            unset($record['eki_toho1']);
            unset($record['eki_toho2']);
            unset($record['eki_toho3']);
            unset($record['eki_car1']);
            unset($record['eki_car2']);
            unset($record['eki_car3']);
            unset($record['eki_bus1']);
            unset($record['eki_bus2']);
            unset($record['eki_bus3']);
            unset($record['bus_toho1']);
            unset($record['bus_toho2']);
            unset($record['bus_toho3']);
            unset($record['bus_route1']);
            unset($record['bus_route2']);
            unset($record['bus_route3']);
            unset($record['bus_stop1']);
            unset($record['bus_stop2']);
            unset($record['bus_stop3']);
            // Log::debug($record['line_cd1']);
            // $recordをまとめて挿入
            $land_data[] = $record;
        }
        Land::upsert($land_data, ['bukken_num']);
        // landsを全削除してから最新をアップ
        // DB::table('lands')->delete();
        // DB::table('lands')->insert($land_data);
        // dump($land_data);

        // land_lineをアップ。あれば更新。なければ作成
        foreach ($land_line_data as $value) {
            $land_id = Land::where('bukken_num', $value['bukken_num'])->first();
            if(is_null($land_id)){
                continue;
            } else {
                LandLine::updateOrCreate(["land_id" => $land_id->id, "line_id" => $value["line_id"], "level" => $value["level"]], ["bukken_num" => $value["bukken_num"], "line_cd" => $value["line_cd"], "station_cd" => $value["station_cd"], "eki_toho" => $value["eki_toho"], "eki_car" => $value["eki_car"], "eki_bus" => $value["eki_bus"], "bus_toho" => $value["bus_toho"], "bus_route" => $value["bus_route"], "bus_stop" => $value["bus_stop"]]);
            }
        }
        // DB::table('land_line')->insert($line_data);

        dump('csvの取り込みが完了しました');

        $c_name = config('const.company_name');
        $message = $c_name . '：CSVの取り込みが完了しました';
        $this->notify->notify($message);
    }
}
