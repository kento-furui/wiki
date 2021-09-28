<?php

namespace App\Http\Controllers;

use App\Models\Eol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EolController extends Controller
{
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
