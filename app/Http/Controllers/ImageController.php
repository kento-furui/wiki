<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        $image = new Image;
        $image->EOLid = $request->id;
        $image->title = $request->title;
        $image->width = $request->width;
        $image->height = $request->height;
        $image->mediaURL = $request->mediaURL;
        $image->identifier = $request->identifier;
        $image->description = $request->description;
        $image->eolMediaURL = $request->eolMediaURL;
        $image->eolThumbnailURL = $request->eolThumbnailURL;
        $image->dataObjectVersionID = $request->dataObjectVersionID;
        $image->save();

        return response()->json($image);
    }

    public function update(Request $request, $id)
    {
        if (!$image = Image::find($id)) {
            $image = new Image;
            $image->EOLid = $id;
        }
        $image->title = $request->title;
        $image->width = $request->width;
        $image->height = $request->height;
        $image->mediaURL = $request->mediaURL;
        $image->identifier = $request->identifier;
        $image->description = $request->description;
        $image->eolMediaURL = $request->eolMediaURL;
        $image->eolThumbnailURL = $request->eolThumbnailURL;
        $image->dataObjectVersionID = $request->dataObjectVersionID;
        $image->save();

        return response()->json($image);
    }
}
