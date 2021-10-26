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
        $taxonID = $request->id;

        $tmp = array();
        $tmp[$request->value] = 1;

        Iucn::updateOrCreate(
            ['taxonID' => $taxonID], $tmp
        );
        
        return response()->json(Iucn::find($taxonID));
    }
}
