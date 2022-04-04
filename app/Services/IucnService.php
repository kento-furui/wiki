<?php

namespace App\Services;

use App\Models\Taxon;

class IucnService
{
    public function number(Taxon $taxon)
    {
        if ($taxon->taxonRank == 'species') return;
        if ($taxon->children()->count() == 0) return;

        $tmp = array('jp' => 0, 'en' => 0, 'img' => 0, 'iucn' => 0);
        foreach ($taxon->children as $c) {
            if ($c->taxonRank == 'species') {
                if ($c->eol && !empty($c->eol->jp))  $tmp['jp']++;
                if ($c->eol && !empty($c->eol->en))  $tmp['en']++;
                if ($c->eol && !empty($c->eol->img)) $tmp['img']++;
                if ($c->iucn && !empty($c->iucn->status)) $tmp['iucn']++;
            } elseif ($c->number) {
                $tmp['jp'] += $c->number->jp;
                $tmp['en'] += $c->number->en;
                $tmp['img'] += $c->number->img;
                $tmp['iucn'] += $c->number->iucn;
            }
        }
        $taxon->number->jp = $tmp['jp'];
        $taxon->number->en = $tmp['en'];
        $taxon->number->img = $tmp['img'];
        $taxon->number->iucn = $tmp['iucn'];
        $taxon->number->save();
    }
}
