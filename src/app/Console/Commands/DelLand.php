<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Land;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use League\Csv\Statement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Line;
use App\Models\Prefecture;
use App\Models\Station;

class DelLand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:delland';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'csvに存在しない物件番号のデータと画像を削除';

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
    public function handle()
    {
        // return 0;
        $ymd = Carbon::today()->format('Ymd');
        $file_name = 'estate_num' . $ymd . '.csv';

        $csv = Reader::createFromString(Storage::get('/csv/land/' . $file_name))->setHeaderOffset(0);

        $records = $stmt->process($csv);
        dump($records);

        // storageから不要な画像を削除
        $now_lands = Land::all()->pluck('bukken_num')->toArray();
        $storage_lists = Storage::directories('public/landimages');
        $now_lists = [];
        foreach ($now_lands as $land) {
            $now_lists[] = "public/landimages/" . $land;
        }
        $diff_lists = array_diff($storage_lists, $now_lists);
        foreach ($diff_lists as $diff_list) {
            Storage::deleteDirectory($diff_list);
        }

        // estate_num.csvに存在しないmysqlデータを削除
        $diff_nums = array_diff($records['bukken_num'], $now_lists);
        dd($diff_nums);
        foreach ($diff_nums as $value) {
            Land::where('bukken_num', $value)->delete();
        }

    }
}
