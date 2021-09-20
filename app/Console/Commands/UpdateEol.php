<?php

namespace App\Console\Commands;

use App\Models\Eol;
use App\Models\Taxon;
use Illuminate\Console\Command;

class UpdateEol extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:eol';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $file = file('aaa.csv');
        foreach ($file as $f) {
            list($jp, $latin) = explode(',', trim($f));
            //list($latin, $jp, $en) = explode(',', trim($f));
            $taxon = Taxon::where('canonicalName', $latin)->first();
            if ($taxon) {
                if ($taxon->eol) {
                    //$taxon->eol->en = $en;
                    //$taxon->eol->jp = $jp;
                    $taxon->eol->status = $jp;
                    $taxon->eol->save();
                    echo $f;
                } else {
                    $eol = new Eol;
                    $eol->EOLid = $taxon->EOLid;
                    //$eol->jp = $jp;
                    $eol->status = $jp;
                    $eol->save();
                    echo $f;
                }
            }
        }
    }
}
