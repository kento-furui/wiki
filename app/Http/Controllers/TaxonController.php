<?php

namespace App\Http\Controllers;

use App\Models\Eol;
use App\Models\Iucn;
use App\Models\Taxon;
use App\Services\IucnService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TaxonController extends Controller
{
    public function index(Request $request)
    {
        $taxa = Taxon::whereIn('taxonomicStatus', ['valid', 'accepted']);

        if (isset($request->name)) {
            $taxa = $taxa->where('canonicalName', 'LIKE', $request->name . "%");
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

    public function recurse(Taxon $taxon)
    {
        return view('taxon.recurse', compact('taxon'));
    }

    public function show(Taxon $taxon, IucnService $service)
    {
        $service->number($taxon);

        $me = $taxon;
        $tree = array();
        while ($me->parent) {
            $me = $tree[] = $me->parent;
        }
        return view('taxon.show', compact('taxon', 'tree'));
    }

    public function sumall(Taxon $taxon, IucnService $service)
    {
        if ($taxon->children) {
            foreach ($taxon->children as $c) {
                $service->number($c);
            }
        }

        $service->number($taxon);
        return redirect('/taxon/' . $taxon->taxonID);
    }

    public function extinct(Taxon $taxon)
    {
        $this->_extinct($taxon);
        return redirect('/taxon/' . $taxon->taxonID);
    }

    private function _extinct(Taxon $taxon)
    {
        foreach ($taxon->children as $c) {
            if ($c->taxonRank == 'species') {
                if (! $c->iucn) {
                    $c->iucn = new Iucn;
                    $c->iucn->taxonID = $c->taxonID;
                }
                $c->iucn->status = 'EX';
                $c->iucn->save();
            } else {
                $this->_extinct($c);
            }
        }
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
}
