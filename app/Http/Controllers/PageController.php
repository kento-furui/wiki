<?php

namespace App\Http\Controllers;

use App\Models\Taxon;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $taxa = Taxon::whereIn('taxonomicStatus', ['valid', 'accepted'])
        ->where('taxonRank', 'phylum')
        ->get();

        return view('page.index', compact('taxa'));
    }
}
