<?php

namespace App\Http\Controllers;

use App\Models\Iucn;
use App\Models\Taxon;
use Illuminate\Http\Request;

class IucnController extends Controller
{
    public function store($taxonID)
    {
        $token = "25ef48b3629d17b58768363e36c5d7ce34130df6ca7bf81a52667ab63320471b";
        $taxon = Taxon::find($taxonID);
        $value = $taxon->canonicalName;
        $url = "https://apiv3.iucnredlist.org/api/v3/species/$value?token=$token";

        $response = @file_get_contents($url);
        $json = json_decode($response);


        return response()->json($json);
    }
}
