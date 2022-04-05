<?php

namespace App\Http\Controllers;

use App\Models\Iucn;
use Illuminate\Http\Request;

class IucnController extends Controller
{
    public function store(Request $request)
    {
        if (Iucn::find($request->id)) {
            $iucn = Iucn::find($request->id);
        } else {
            $iucn = new Iucn;
            $iucn->taxonID = $request->id;
        }
        $iucn->status = $request->value;
        $iucn->save();
    }
}
