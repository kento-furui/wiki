<?php

namespace App\Http\Controllers;

use App\Models\Eol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EolController extends Controller
{
    public function cname($lang, $id)
    {
        $url = "https://eol.org/api/pages/1.0/$id.json?details=true&common_names=true";
        $response = @file_get_contents($url);
        $json = json_decode($response);
        foreach ($json->taxonConcept->vernacularNames as $vn) {
            if ($vn->eol_preferred && $vn->language == $lang) {
                $eol = Eol::find($id);
                if ($eol == null) {
                    $eol = new Eol;
                    $eol->EOLid = $id;
                }
                $eol->$lang = $vn->vernacularName;
                $eol->save();
                return response()->json($eol);
            }
        }

        return response()->json($json);
    }

    public function update(Request $request, $id)
    {
        $eol = Eol::find($id);
        if ($eol == null) {
            $eol = new Eol;
            $eol->EOLid = $id;
        }
        if ($request->has('jp')) $eol->jp = $request->jp;
        if ($request->has('en')) $eol->en = $request->en;
        if ($request->has('img')) $eol->img = $request->img;
        $eol->save();
        return response()->json($eol);
    }
}
