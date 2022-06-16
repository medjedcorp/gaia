<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Land;
use Illuminate\Support\Facades\Storage;

class DelImg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:delimg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lnadsに物件番号のない画像を削除';

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
        // storageから不要な画像を削除
        $now_lands = Land::all()->pluck('bukken_num')->toArray();
        $storage_lists = Storage::directories('public/landimages');
        $now_lists = [];
        foreach($now_lands as $land){
            $now_lists[] = "public/landimages/" . $land;
        }
        $diff_lists = array_diff($storage_lists, $now_lists);
        foreach($diff_lists as $diff_list){
            Storage::deleteDirectory($diff_list);
        }
    }
}
