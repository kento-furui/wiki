<?php

namespace App\Http\Controllers;

use App\Models\Iucn;
use Illuminate\Http\Request;

class IucnController extends Controller
{
    public function store(Request $request)
    {
        if ($iucn = Iucn::find($request->id)) {
            $iucn->EX = null;
            $iucn->EW = null;
            $iucn->CR = null;
            $iucn->EN = null;
            $iucn->VU = null;
            $iucn->NT = null;
            $iucn->LC = null;
            $iucn->DD = null;
        } else {
            $iucn = new Iucn;
            $iucn->taxonID = $request->id;
        }

        switch($request->value) {
            case 'EX': $iucn->EX = 1; break;
            case 'EW': $iucn->EW = 1; break;
            case 'CR': $iucn->CR = 1; break;
            case 'EN': $iucn->EN = 1; break;
            case 'VU': $iucn->VU = 1; break;
            case 'NT': $iucn->NT = 1; break;
            case 'LC': $iucn->LC = 1; break;
            case 'DD': $iucn->DD = 1; break;
        }
        $iucn->save();
    }
}
