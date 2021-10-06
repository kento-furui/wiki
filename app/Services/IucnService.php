<?php

namespace App\Services;

use App\Models\Iucn;
use App\Models\Number;
use App\Models\Taxon;
use Illuminate\Support\Facades\DB;

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

    public function number(Taxon $taxon)
    {
        if ($taxon->taxonRank == 'species') return;
        if ($taxon->children()->count() == 0) return;

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

        if (! $taxon->number) {
            $taxon->number = new Number;
            $taxon->number->taxonID = $taxon->taxonID;
        }

        $taxon->number->species = $tmp['species'];
        $taxon->number->kingdom = $tmp['kingdom'];
        $taxon->number->phylum = $tmp['phylum'];
        $taxon->number->family = $tmp['family'];
        $taxon->number->class = $tmp['class'];
        $taxon->number->order = $tmp['order'];
        $taxon->number->genus = $tmp['genus'];
        $taxon->number->save();
    }

    public function extinct(Taxon $taxon)
    {
        if (! $taxon->iucn) {
            $taxon->iucn = new Iucn;
            $taxon->iucn->taxonID = $taxon->taxonID;
        }
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
            if (! $c->iucn) {
                $c->iucn = new Iucn;
                $c->iucn->taxonID = $c->taxonID;
            }
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
