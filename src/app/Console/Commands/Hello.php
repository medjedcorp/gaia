<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Hello extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:hello';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Helloを言うだけのテストです';

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
        dump('HelloWorld from Lravel!!!');
        Log::debug('HelloWorld from Lravel!!!');
    }
}
