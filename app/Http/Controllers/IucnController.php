<?php

namespace App\Http\Controllers;

use App\Models\Iucn;
use App\Models\Taxon;
use Illuminate\Http\Request;

class IucnController extends Controller
{
    public function store($taxonID)
    {
        $iucn = new Iucn;
        $iucn->taxonID = $request->id;
        $iucn->status = $request->value;
        $iucn->save();
        
        return response()->json($iucn);
    }

    public function update(Request $request, $id)
    {
        if(!$iucn = Iucn::find($id)) {
            $iucn = new Iucn;
            $iucn->taxonID = $id;
        }
        $iucn->status = $request->value;
        $iucn->save();

        return response()->json($iucn);
    }
}
