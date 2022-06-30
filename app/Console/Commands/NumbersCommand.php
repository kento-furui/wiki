<?php

namespace App\Console\Commands;

use App\Models\Taxon;
use Illuminate\Console\Command;

class NumbersCommand extends Command
{
    public $tmp;

    protected $signature = 'numbers:store {taxonID}';
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $taxa = Taxon::whereIn('taxonomicStatus', ['valid', 'accepted'])
            //->where('taxonID', $this->argument('taxonID'))
            ->where('taxonID', '>', $this->argument('taxonID'))
            //->where('taxonRank', $this->argument('taxonID'))
            ->has('children')
            ->orderBy('taxonID')
            //->limit(50000)
            ->get();

        foreach ($taxa as $taxon) {
            $this->tmp = array();
            $this->tmp['jp'] = 0;
            $this->tmp['en'] = 0;
            $this->tmp['img'] = 0;
            $this->tmp['iucn'] = 0;

            echo $taxon->taxonID . " " . $taxon->canonicalName . "\n";

            $this->nodes($taxon);

            if (empty($this->tmp['jp']) && empty($this->tmp['en']) && empty($this->tmp['img']) && empty($this->tmp['iucn'])) continue;

            $taxon->number->node = json_encode($this->tmp);
            $taxon->number->save();

            echo json_encode($this->tmp) . "\n";
        }
    }

    private function nodes(Taxon $taxon)
    {
        foreach ($taxon->children as $c) {
            if ($c->taxonRank == 'species') {
                if ($c->eol && !empty($c->eol->jp)) {
                    $this->tmp['jp']++;
                }
                if ($c->eol && !empty($c->eol->en)) {
                    $this->tmp['en']++;
                }
                if ($c->image) {
                    $this->tmp['img']++;
                }
                if ($c->iucn) {
                    $this->tmp['iucn']++;
                }
            }
            $this->nodes($c);
        }
    }
}
