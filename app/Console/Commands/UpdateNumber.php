<?php

namespace App\Console\Commands;

use App\Models\Taxon;
use App\Models\Number;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateNumber extends Command
{
    protected $signature = 'update:number';
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $taxa = Taxon::whereIn('taxonomicStatus', ['valid', 'accepted'])
            //->where('taxonID', 'EOL-000002321060')    
            ->whereIn('taxonRank', ['phylum', 'order', 'clade', 'domain'])
            ->orderBy('taxonID', 'DESC')
            ->get();

        foreach ($taxa as $taxon) {            
            $tmp = array(
                'kingdom' => 0,
                'phylum' => 0,
                'class' => 0,
                'order' => 0,
                'family' => 0,
                'genus' => 0,
                'species' => 0,
            );

            foreach ($taxon->children as $c) {
                if (array_key_exists($c->taxonRank, $tmp)) {
                    $tmp[$c->taxonRank]++;
                }
                if ($c->number) {
                    foreach (array_keys($tmp) as $key) {
                        $tmp[$key] += $c->number->$key;
                    }
                }
            }

            if ($tmp['kingdom'] == 0 &&
                $tmp['phylum'] == 0 &&
                $tmp['class'] == 0 &&
                $tmp['order'] == 0 &&
                $tmp['family'] == 0 &&
                $tmp['genus'] == 0 &&
                $tmp['species'] == 0) continue;

            Number::updateOrCreate(['taxonID' => $taxon->taxonID], $tmp);
            echo implode(",", [$taxon->taxonID, $taxon->taxonRank]);
            echo ",";
            echo implode(",", $tmp) . "\n";
        }
    }
}
