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
    public $taxa;

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

    public function rand()
    {
        $taxon = Taxon::whereIn('taxonomicStatus', ['valid', 'accepted'])
            //->where('taxonRank', 'species')
            //->where('taxonRank', 'class')
            //->doesntHave('iucn')
            ->doesntHave('images')
            ->has('eol')
            ->inRandomOrder()
            ->limit(1)
            ->first();
        
        $tree = array();
        $status = array();

        return view('taxon.show', compact('taxon', 'tree', 'status'));
    }

    public function recurse(Taxon $taxon)
    {
        $this->taxa = array();
        $this->_recurse($taxon);
        return view('taxon.recurse', ['taxon' => $taxon, 'taxa' => $this->taxa]);
    }
    
    private function _recurse(Taxon $taxon)
    {
        foreach ($taxon->children as $c) {
            array_push($this->taxa, $c);
            $this->_recurse($c);
        }
    }

    public function show(Taxon $taxon, IucnService $service)
    {
        $service->number($taxon);

        $me = $taxon;
        $tree = array();
        while ($me->parent) {
            $me = $tree[] = $me->parent;
        }

        $status = array();
        foreach ($taxon->children as $c) {
            if ($c->iucn) {
                if (array_key_exists($c->iucn->status, $status)) {
                    $status[$c->iucn->status]++;
                } else {
                    $status[$c->iucn->status] = 1;
                }
            }
        }

        $image = $taxon->images()->where("preferred", 1)->first();

        return view('taxon.show', compact('taxon', 'tree', 'status', 'image'));
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
        if (! $taxon->iucn) {
            $taxon->iucn = new Iucn;
            $taxon->iucn->taxonID = $taxon->taxonID;
        }
        $taxon->iucn->status = 'EX';
        $taxon->iucn->save();

        foreach ($taxon->children as $c) {
            $this->_extinct($c);
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
