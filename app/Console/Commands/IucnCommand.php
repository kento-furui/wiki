<?php

namespace App\Console\Commands;

use App\Models\Iucn;
use App\Models\Taxon;
use Exception;
use Illuminate\Console\Command;

class IucnCommand extends Command
{
    protected $signature = 'iucn:store {taxonID}';
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $taxa = Taxon::whereIn('taxonomicStatus', ['valid', 'accepted'])
        ->where('taxonID', '>', $this->argument('taxonID'))
        ->where('taxonRank', 'species')
        ->doesntHave('iucn')
        ->orderBy('taxonID')
        ->limit(50000)
        ->get();

        foreach ($taxa as $taxon) {
            echo $taxon->taxonID . " " . $taxon->canonicalName . "\n";
            $this->getIucn($taxon);
        }
    }

    public function getIucn(Taxon $taxon)
    {
        $token = "25ef48b3629d17b58768363e36c5d7ce34130df6ca7bf81a52667ab63320471b";
        $url = "https://apiv3.iucnredlist.org/api/v3/species/$taxon->canonicalName?token=$token";
        $response = @file_get_contents($url);
        $json = json_decode($response);
        if (isset($json->result[0]->category)) {
            $iucn = new Iucn;
            $iucn->TaxonID = $taxon->taxonID;
            $iucn->status = $json->result[0]->category;
            $iucn->save();
            echo $json->result[0]->category . "\n";
        }
    }
}
