<?php

namespace App\Http\Controllers;

use App\Models\Iucn;
use App\Models\Taxon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IucnController extends Controller
{
    public function store(Request $request)
    {
        $iucn = new Iucn;
        $iucn->taxonID = $request->id;
        $iucn->status = $request->value;
        $iucn->save();

        
    }
}
