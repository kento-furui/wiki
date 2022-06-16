<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Iucn;
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

        return redirect('/page/' . $taxon->taxonID);
    }

    public function represent(Taxon $taxon)
    {
        $me = $taxon;
    
        if ($taxon->image) {
            $image = $taxon->image;

            while ($taxon->parent) {
                $taxon = $taxon->parent;
                if (!$taxon->image) {
                    $taxon->image = new Image;
                    $taxon->image->EOLid = $taxon->EOLid;
                }
                $taxon->image->title = $image->title;
                $taxon->image->width = $image->width;
                $taxon->image->height = $image->height;
                $taxon->image->mediaURL = $image->mediaURL;
                $taxon->image->identifier = $image->identifier;
                $taxon->image->description = $image->description;
                $taxon->image->eolMediaURL = $image->eolMediaURL;
                $taxon->image->eolThumbnailURL = $image->eolThumbnailURL;
                $taxon->image->save();
            }
        }

        return redirect('/page/' . $me->taxonID);
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
