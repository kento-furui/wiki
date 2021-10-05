<?php

namespace App\Services;

use App\Models\Iucn;
use App\Models\Taxon;

class IucnService
{
    public function sum(Taxon $taxon)
    {
        if ($taxon->taxonRank == 'species') return;
        if ($taxon->children()->count() == 0) return;

        $tmp = array('EX' => 0, 'EW' => 0, 'CR' => 0, 'EN' => 0, 'VU' => 0, 'NT' => 0, 'LC' => 0, 'DD' => 0);
        $keys = array_keys($tmp);
        foreach ($taxon->children as $c) {
            if ($c->iucn) {
                foreach ($keys as $k) {
                    $tmp[$k] += $c->iucn->$k;
                }
            }
        }
        if (!$taxon->iucn) {
            $taxon->iucn = new Iucn;
            $taxon->iucn->taxonID = $taxon->taxonID;
        }
        foreach ($keys as $k) {
            $taxon->iucn->$k = $tmp[$k];
        }
        $taxon->iucn->save();
    }

    public function extinct(Taxon $taxon)
    {
        $taxon->iucn->EX = 1;
        $taxon->iucn->EW = null;
        $taxon->iucn->CR = null;
        $taxon->iucn->EN = null;
        $taxon->iucn->VU = null;
        $taxon->iucn->NT = null;
        $taxon->iucn->LC = null;
        $taxon->iucn->DD = null;
        $taxon->iucn->save();

        foreach($taxon->children as $c) {
            $c->iucn->EX = 1;
            $c->iucn->EW = null;
            $c->iucn->CR = null;
            $c->iucn->EN = null;
            $c->iucn->VU = null;
            $c->iucn->NT = null;
            $c->iucn->LC = null;
            $c->iucn->DD = null;
            $c->iucn->save();
        }
    }
}
