<?php

namespace App\Console\Commands;

use App\Models\Taxon;
use App\Models\Number;
use Illuminate\Console\Command;

class NumbersCommand extends Command
{
    public $tmp;
    public $iucn;

    protected $signature = 'numbers:store {taxonID}';
    //protected $signature = 'numbers:store';
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $numbers = Number::where('node', null)
        ->where('taxonID', '>', $this->argument('taxonID'))
        ->orderBy('taxonID')
        ->get();

        foreach ($numbers as $number) {
            $this->tmp = array();
            $this->tmp['JP'] = 0;
            $this->tmp['EN'] = 0;
            $this->tmp['IMG'] = 0;
            $this->tmp['IUCN'] = 0;

            $this->iucn = array();
            $this->iucn["EX"] = 0;
            $this->iucn["EW"] = 0;
            $this->iucn["CR"] = 0;
            $this->iucn["EN"] = 0;
            $this->iucn["VU"] = 0;
            $this->iucn["CD"] = 0;
            $this->iucn["NT"] = 0;
            $this->iucn["LC"] = 0;
            $this->iucn["DD"] = 0;

            echo $number->taxonID . "\n";

            $this->nodes($number->taxon);

            if (empty($this->tmp['JP']) && empty($this->tmp['EN']) && empty($this->tmp['IMG']) && empty($this->tmp['IUCN'])) continue;

            $number->node = json_encode($this->tmp);
            $number->status = json_encode($this->iucn);
            $number->save();

            echo json_encode($this->tmp) . "\n";
            echo json_encode($this->iucn) . "\n";
        }
    }

    private function nodes(Taxon $taxon)
    {
        foreach ($taxon->children as $c) {
            if ($c->taxonRank == 'species') {
                if ($c->eol && !empty($c->eol->jp)) {
                    $this->tmp['JP']++;
                }
                if ($c->eol && !empty($c->eol->en)) {
                    $this->tmp['EN']++;
                }
                if ($c->image) {
                    $this->tmp['IMG']++;
                }
                if ($c->iucn) {
                    $this->tmp['IUCN']++;
                    $key = $c->iucn->status;
                    $this->iucn[$key]++;
                }
            }
            $this->nodes($c);
        }
    }
}
