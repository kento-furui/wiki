<?php

namespace App\Http\Controllers;

use App\Models\Eol;
use App\Models\Image;
use App\Models\Iucn;
use App\Models\Number;
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
        ->doesntHave('iucn')
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
                $taxon->image->dataObjectVersionID = $image->dataObjectVersionID;
                $taxon->image->save();
            }
        }

        return redirect('/taxon/' . $me->taxonID);
    }

    // public $ranks = array();
    // public $status = array();

    // public function ranks(Taxon $taxon)
    // {
    //     $this->_ranks($taxon);
    //     return response()->json( $this->ranks );
    // }

    // public function status(Taxon $taxon)
    // {
    //     $this->_status($taxon);
    //     return response()->json( $this->status );
    // }

    // private function _status(Taxon $taxon)
    // {
    //     //if ($depth > 100) return;

    //     foreach ($taxon->children as $c) {
    //         if ($c->taxonRank == 'species' && $c->iucn) {
    //             $key = $c->iucn->status;
    //             if (array_key_exists($key, $this->status)) {
    //                 $this->status[$key]++;
    //             } else {
    //                 $this->status[$key] = 1;
    //             }
    //         }
    //         $this->_status($c);
    //     }
    // }

    // private function _ranks(Taxon $taxon)
    // {
    //     //if ($depth > 100) return;

    //     foreach ($taxon->children as $c) {
    //         $key = $c->taxonRank;
    //         if (array_key_exists($key, $this->ranks)) {
    //             $this->ranks[$key]++;
    //         } else {
    //             $this->ranks[$key] = 1;
    //         }
    //         $this->_ranks($c);
    //     }
    // }

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
