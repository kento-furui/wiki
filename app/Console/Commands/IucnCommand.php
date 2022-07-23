<?php

namespace App\Console\Commands;

use App\Models\Iucn2;
use App\Models\Taxon;
use Exception;
use Illuminate\Console\Command;

class IucnCommand extends Command
{
    protected $signature = 'iucn:store {char}';
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $taxa = Taxon::whereIn('taxonomicStatus', ['valid', 'accepted'])
        ->where('taxonRank', 'species')
        ->where('canonicalName', 'LIKE', $this->argument('char') . '%')
        //->has('iucn')
        ->doesntHave('iucn2')
        //->orderBy('taxonID')
        ->orderBy('canonicalName')
        //->limit(50000)
        ->get();

        foreach ($taxa as $taxon) {
            echo $taxon->taxonID . " " . $taxon->canonicalName . "\n";
            $this->getIucn($taxon);
        }
    }

    public function getIucn(Taxon $taxon)
    {
        $name = $taxon->canonicalName;
        $token = "25ef48b3629d17b58768363e36c5d7ce34130df6ca7bf81a52667ab63320471b";
        $url = "https://apiv3.iucnredlist.org/api/v3/species/$name?token=$token";
        $response = @file_get_contents($url);
        $json = json_decode($response);
        if (isset($json->result[0])) {
            $r = $json->result[0];

            $iucn = new Iucn2;
            $iucn->genus = $r->genus;
            $iucn->order = $r->order;
            $iucn->class = $r->class;
            $iucn->family = $r->family;
            $iucn->phylum = $r->phylum;
            $iucn->id = $taxon->taxonID;
            $iucn->kingdom = $r->kingdom;
            $iucn->taxonid = $r->taxonid;
            $iucn->aoo_km2 = $r->aoo_km2;
            $iucn->eoo_km2 = $r->eoo_km2;
            $iucn->reviewer = $r->reviewer;
            $iucn->category = $r->category;
            $iucn->assessor = $r->assessor;
            $iucn->criteria = $r->criteria;
            $iucn->authority = $r->authority;
            $iucn->errata_flag = $r->errata_flag;
            $iucn->depth_lower = $r->depth_lower;
            $iucn->depth_upper = $r->depth_upper;
            $iucn->amended_flag = $r->amended_flag;
            $iucn->marine_system = $r->marine_system;
            $iucn->errata_reason = $r->errata_reason;
            $iucn->published_year = $r->published_year;
            $iucn->amended_reason = $r->amended_reason;
            $iucn->scientific_name = $r->scientific_name;
            $iucn->assessment_date = $r->assessment_date;
            $iucn->elevation_lower = $r->elevation_lower;
            $iucn->elevation_upper = $r->elevation_upper;
            $iucn->main_common_name = $r->main_common_name;
            $iucn->population_trend = $r->population_trend;
            $iucn->freshwater_system = $r->freshwater_system;
            $iucn->terrestrial_system = $r->terrestrial_system;
            $iucn->save();

            echo $iucn->category . "\n";
        }
    }
}
