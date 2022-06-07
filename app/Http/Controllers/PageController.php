<?php

namespace App\Http\Controllers;

use App\Models\Eol;
use App\Models\Taxon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class PageController extends Controller
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

    public function show($taxonID)
    {
        $taxon = Taxon::find($taxonID);
        
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
  
        return view('page.show', compact('taxon', 'tree', 'status'));
    }
}
