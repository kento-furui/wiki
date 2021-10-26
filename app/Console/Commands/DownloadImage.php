<?php

namespace App\Console\Commands;

use App\Models\Eol;
use App\Models\Taxon;
use App\Models\Number;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DownlaodImage extends Command
{
    protected $signature = 'download:image';
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $eol = Eol::query()->where('img', 'IS NOT NULL')->get();

    }
}
