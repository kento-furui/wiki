<?php

namespace App\Http\Controllers;

use App\Models\Iucn;
use App\Models\Image;
use App\Models\Taxon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

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

    public function rand()
    {  
        $taxa = Taxon::whereIn('taxonomicStatus', ['valid', 'accepted'])
        ->where('taxonRank', 'species')
        //->where('taxonRank', 'genus')
        ->doesntHave('iucn')
        //->doesntHave('image')
        ->inRandomOrder()
        ->limit(16)
        ->get();

        return view('page.rand', compact('taxa'));
    }

    public function show(Taxon $taxon)
    {
        $tree = $this->_tree($taxon);
        return view('page.show', compact('taxon', 'tree',));
    }

    public function tree(Taxon $taxon)
    {
        $tree = $this->_tree($taxon);
        return view('page.tree', compact('taxon', 'tree'));
    }

    public function taxon(Taxon $taxon)
    {
        $tree = $this->_tree($taxon);
        return view('page.taxon', compact('taxon', 'tree'));
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

    public function extinct(Taxon $taxon)
    {
        if (!$taxon->iucn) {
            $taxon->iucn = new Iucn;
            $taxon->iucn->taxonID = $taxon->taxonID;
        }
        $taxon->iucn->status = "EX";
        $taxon->iucn->save();

        foreach ($taxon->children as $c) {
            $this->extinct($c);
        }

        return redirect('/taxon/' . $taxon->taxonID);
    }

    public function represent(Taxon $taxon)
    {
        if ($taxon->image && $taxon->parent) {
            $image = $taxon->image;
            $taxon->parent->image->title = $image->title;
            $taxon->parent->image->width = $image->width;
            $taxon->parent->image->height = $image->height;
            $taxon->parent->image->mediaURL = $image->mediaURL;
            $taxon->parent->image->identifier = $image->identifier;
            $taxon->parent->image->description = $image->description;
            $taxon->parent->image->eolMediaURL = $image->eolMediaURL;
            $taxon->parent->image->eolThumbnailURL = $image->eolThumbnailURL;
            $taxon->parent->image->dataObjectVersionID = $image->dataObjectVersionID;
            $taxon->parent->image->save();
        }
        return redirect('/taxon/' . $taxon->taxonID);
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
}
