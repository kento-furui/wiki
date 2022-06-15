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
        $taxa = array();

        if (isset($request->search)) {
            $taxa = Taxon::whereIn('taxonomicStatus', ['valid', 'accepted']);

            if (preg_match("/^[ぁ-んァ-ヶー一-龠]+$/u", $request->search)) {
                $taxa = $taxa->whereHas('eol', function (Builder $query) use ($request) {
                    $query->where('jp', 'LIKE', $request->search . "%");
                })->get();
            } else {
                $taxa = $taxa->where('canonicalName', 'LIKE', $request->search . "%")->get();
            }
        }

        return view('page.index', compact('taxa', 'request'));
    }

    public function show(Taxon $taxon)
    {
        $ranks = array();
        $status = array();
        $tree = $this->_tree($taxon);
        
        foreach ($taxon->children as $child) {
            if ($child->iucn) {
                $key = $child->iucn->status;
                if (array_key_exists($key, $status)) {
                    $status[$key]++;
                } else {
                    $status[$key] = 1;
                }
            }

            $key = $child->taxonRank;
            if (array_key_exists($key, $ranks)) {
                $ranks[$key]++;
            } else {
                $ranks[$key] = 1;
            }
        }

        return view('page.show', compact('taxon', 'tree', 'ranks', 'status'));
    }

    public function tree(Taxon $taxon)
    {
        $tree = $this->_tree($taxon);
        return view('page.tree', compact('taxon', 'tree'));
    }

    public function media(Taxon $taxon)
    {
        $tree = $this->_tree($taxon);
        return view('page.media', compact('taxon', 'tree'));
    }

    public function ja(Taxon $taxon)
    {
        $tree = $this->_tree($taxon);
        return view('page.ja', compact('taxon', 'tree'));
    }

    public function en(Taxon $taxon)
    {
        $tree = $this->_tree($taxon);
        return view('page.en', compact('taxon', 'tree'));
    }

    private function _tree(Taxon $taxon)
    {
        $me = $taxon;
        $tree = array();
        while ($me->parent) {
            $me = $tree[] = $me->parent;
        }
        return $tree;
    }

    public function ranks(Taxon $taxon)
    {
        $ranks = array();

        foreach ($taxon->children as $c) {
            $key = $c->taxonRank;
            if (array_key_exists($key, $this->ranks)) {
                $ranks[$key]++;
            } else {
                $ranks[$key] = 1;
            }
        }

        return response()->json($ranks);
    }
}
