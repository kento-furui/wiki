<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

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
}
