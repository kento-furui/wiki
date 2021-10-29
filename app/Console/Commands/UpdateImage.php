<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateImage extends Command
{
    protected $signature = 'update:image';
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $eol = DB::table('eols')->distinct()->whereNotNull('img')->get(['img']);
        foreach ($eol as $e) {
            try {
                $image = file_get_contents($e->img);
                file_put_contents('/home/family/wiki/public/img/' . basename($e->img), $image);
                echo $e->img . "\n";
            } catch(Exception $e) {
                print_r($e);
            }
        }
    }
}
