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
use App\Services\LineNotifyService;

class DelLand extends Command
{
    private $notify;
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
        $this->notify = new LineNotifyService();
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
        $file_name = 'estate_num' . $ymd . '.csv';

        $csv = Reader::createFromString(Storage::get('/csv/land/' . $file_name))->setHeaderOffset(0);
        $records = $stmt->process($csv);
        // dump($records);

        // storageから不要な画像を削除
        $now_lands = Land::all()->pluck('bukken_num')->toArray();
        $storage_lists = Storage::directories('public/landimages');
        $now_lists = [];
        foreach ($now_lands as $land) {
            $now_lists[] = "public/landimages/" . $land;
        }
        $diff_lists = array_diff($now_lists, $storage_lists);
        foreach ($diff_lists as $diff_list) {
            Storage::deleteDirectory($diff_list);
        }

        // estate_num.csvに存在しないmysqlデータを削除
        $bukken_num = [];
        foreach ($records as $record) {
            $bukken_num[] = $record['bukken_num'];
        }
        // dd($bukken_num,$now_lands);
        $diff_nums = array_diff($now_lands, $bukken_num);
        // dd($diff_nums);
        foreach ($diff_nums as $diff_num) {
            Land::where('bukken_num', $diff_num)->delete();
        }
        dump('不要な物件データと画像を削除しました');

        $c_name = config('const.company_name');
        $message = $c_name . '：不要な物件データと画像を削除しました';
        $this->notify->notify($message);
    }
}
