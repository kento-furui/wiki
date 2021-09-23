<?php

namespace App\Http\Controllers;

use App\Models\Eol;
use App\Models\Iucn;
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

        $jp = $request->jp;
        $en = $request->en;
        $rank = $request->rank;
        $name = $request->name;

        $taxa = $taxa->paginate(100)->withQueryString();
        return view('taxon.index', compact('taxa', 'jp', 'en', 'rank', 'name'));
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
        if ($taxon->taxonRank != "species") {
            $this->sum($taxon);
        }

        $tree[] = $me = $taxon;
        while ($me->parent) {
            $me = $tree[] = $me->parent;
        }
        return view('taxon.show', compact('taxon', 'tree'));
    }

    public function sum(Taxon $taxon)
    {
        $tmp = array('EX' => 0, 'EW' => 0, 'CR' => 0, 'EN' => 0, 'VU' => 0, 'NT' => 0, 'LC' => 0, 'DD' => 0, 'NE' => 0);
        $keys = array_keys($tmp);
        foreach ($taxon->children as $c) {
            if ($c->iucn) {
                foreach ($keys as $k) {
                    $tmp[$k] += $c->iucn->$k;
                }
            }
        }
        if (! $taxon->iucn) {
            $taxon->iucn = new Iucn;
            $taxon->iucn->taxonID = $taxon->taxonID;
        }
        foreach ($keys as $k) {
            $taxon->iucn->$k = $tmp[$k];
        }
        $taxon->iucn->save();
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
