<?php

namespace App\Http\Controllers;

use App\Models\Eol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $eol = Eol::find($id);
        if ($eol == null) {
            $eol = new Eol;
            $eol->EOLid = $id;
        }
        if (isset($request->jp)) $eol->jp = $request->jp;
        if (isset($request->en)) $eol->en = $request->en;
        if (isset($request->img)) $eol->img = $request->img;
        $eol->save();
        return response()->json($eol);
    }

    public function image(Eol $eol)
    {
        $source = imagecreatefromjpeg($eol->img);
        $rotate = imagerotate($source, 90, 0);
        $path = storage_path() . "/app/img/" . $eol->EOLid . ".jpg";
        imagejpeg($rotate, $path);
        $eol->img = '/img/' . $eol->EOLid . ".jpg";
        $eol->save();

        return response()->json($eol);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
