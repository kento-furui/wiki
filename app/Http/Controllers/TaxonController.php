<?php

namespace App\Http\Controllers;

use App\Models\Eol;
use App\Models\Taxon;
use Illuminate\Database\Eloquent\Builder;
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
        $taxa = Taxon::whereIn('taxonomicStatus', ['valid', 'accepted']);

        if (isset($request->name)) {
            if ($request->name == "noeol") {
                $taxa = $taxa->doesntHave('eol');
            } elseif ($request->name == "haseol") {
                $taxa = $taxa->has('eol');
            } else {
                $taxa = $taxa->where('canonicalName', 'LIKE', $request->name . "%");
            }
        }

        if (isset($request->rank)) {
            $taxa = $taxa->where('taxonRank', $request->rank);
        }

        if (isset($request->jp)) {
            $taxa = $taxa->whereHas('eol', function (Builder $query) use ($request) {
                $query->where('jp', 'LIKE', $request->jp . "%");
            });
        }

        if (isset($request->en)) {
            $taxa = $taxa->whereHas('eol', function (Builder $query) use ($request) {
                $query->where('en', 'LIKE', $request->en . "%");
            });
        }

        $taxa = $taxa->paginate(100)->withQueryString();
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
        while ($me->parent) {
            $me = $tree[] = $me->parent;
        }
        return view('taxon.show', compact('taxon', 'tree'));
    }

    public function recursive(Taxon $taxon)
    {
        return view('taxon.recursive', compact('taxon'));
    }

    public function represent(Taxon $taxon)
    {
        $img = $taxon->eol->img;
        $me = $taxon;
        while ($me->parent) {
            if (!$me->parent->eol) {
                $me->parent->eol = new Eol;
                $me->parent->eol->EOLid = $me->parent->EOLid;
            }
            $me->parent->eol->img = $img;
            $me->parent->eol->save();
            $me = $me->parent;
        }
        return redirect('/taxon/' . $taxon->taxonID);
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
