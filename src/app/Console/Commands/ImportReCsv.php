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
use Illuminate\Support\Facades\Storage;

class ImportReCsv extends Command
{
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

        $csv = Reader::createFromString(Storage::get('/csv/land/'. $file_name))->setHeaderOffset(0);

        //UTF-8に変換
        CharsetConverter::addTo($csv, 'SJIS-win', 'UTF-8');

        $records = $stmt->process($csv);
        $data = [];

        foreach ($records as $record) {
            // Log::debug($record['line_cd1']);
            $lands = Land::where('bukken_num', $record['bukken_num'])->first();
            if ($lands) {
                $record['display_flag'] = $lands->display_flag;
            }
            if ($record['line_cd1']) {
                $line_cd1 = $record['line_cd1'];
                $record['line_cd1'] = DB::table('lines')->where('line_name', 'like', '%' . $line_cd1 . '%')->value('line_cd');
            } else {
                $record['line_cd1'] = null;
            }
            if ($record['line_cd2']) {
                $line_cd2 = $record['line_cd2'];
                $record['line_cd2'] = DB::table('lines')->where('line_name', 'like', '%' . $line_cd2 . '%')->value('line_cd');
            } else {
                $record['line_cd2'] = null;
            }
            if ($record['line_cd3']) {
                $line_cd3 = $record['line_cd3'];
                $record['line_cd3'] = DB::table('lines')->where('line_name', 'like', '%' . $line_cd3 . '%')->value('line_cd');
            } else {
                $record['line_cd3'] = null;
            }
            if ($record['station_cd1']) {
                $station_cd1 = $record['station_cd1'];
                $record['station_cd1'] = DB::table('stations')->where('station_name', 'like', '%' . $station_cd1 . '%')->value('station_cd');
            } else {
                $record['station_cd1'] = null;
            }
            if ($record['station_cd2']) {
                $station_cd2 = $record['station_cd2'];
                $record['station_cd2'] = DB::table('stations')->where('station_name', 'like', '%' . $station_cd2 . '%')->value('station_cd');
            } else {
                $record['station_cd2'] = null;
            }
            if ($record['station_cd3']) {
                $station_cd3 = $record['station_cd3'];
                $record['station_cd3'] = DB::table('stations')->where('station_name', 'like', '%' . $station_cd3 . '%')->value('station_cd');
            } else {
                $record['station_cd3'] = null;
            }
            // Log::debug($record['line_cd1']);
            $record['created_at'] = now();
            $record['updated_at'] = now();
            $data[] = $record;
        }
        DB::table('lands')->delete();
        DB::table('lands')->insert($data);
        // logger('test');
    }
}
