<?php

namespace App\Http\Controllers;

use App\Models\Iucn2;
use Illuminate\Http\Request;

class IucnController extends Controller
{
    public function store(Request $r)
    {
        $iucn = new Iucn2;
        $iucn->id = $r->taxonID;
        $iucn->genus = $r->genus;
        $iucn->order = $r->order;
        $iucn->class = $r->class;
        $iucn->family = $r->family;
        $iucn->phylum = $r->phylum;
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
        
        return response()->json($iucn);
    }
}
