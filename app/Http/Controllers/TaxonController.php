<?php

namespace App\Http\Controllers;

use App\Models\Taxon;
use Illuminate\Http\Request;

class TaxonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->search)) {
            $taxa = Taxon::where('canonicalName', 'LIKE', $request->search . "%")
                ->whereIn('taxonomicStatus', ['valid', 'accepted'])
                ->paginate(100)
                ->withQueryString();
        } else {
            $taxa = Taxon::whereIn('taxonomicStatus', ['valid', 'accepted'])
                ->paginate(100)
                ->withQueryString();
        }
        return view('taxon.index', compact('taxa'));
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
    public function show(Taxon $taxon)
    {
        $tree = array();
        $me = $taxon;
        while ($me->parent)
        {
            $me = $tree[] = $me->parent;
        }
        return view('taxon.show', compact('taxon', 'tree'));
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
        //
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
