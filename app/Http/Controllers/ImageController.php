<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        $image = new Image;
        $image->EOLid = $request->EOLid;
        $image->title = $request->title;
        $image->width = $request->width;
        $image->height = $request->height;
        $image->mediaURL = $request->mediaURL;
        $image->identifier = $request->identifier;
        $image->description = $request->description;
        $image->eolMediaURL = $request->eolMediaURL;
        $image->eolThumbnailURL = $request->eolThumbnailURL;
        $image->save();

        return response()->json($image);
    }

    public function preferred(Request $request)
    {
        DB::table('images')
        ->where('EOLid', $request->EOLid)
        ->update(['preferred' => null]);

        DB::table('images')
        ->where('EOLid', $request->EOLid)
        ->where('identifier', $request->identifier)
        ->update(['preferred' => 1]);

        return response()->json($request);
    }
}
