<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImageController extends Controller
{
    public function store($id)
    {
        $url = "https://eol.org/api/pages/1.0/$id.json?details=true&images_per_page=1";
        $response = @file_get_contents($url);
        $json = json_decode($response);
        if (!isset($json->taxonConcept->dataObjects)) {
            return response()->json($json);
        }

        $do = $json->taxonConcept->dataObjects[0];
        
        $image = new Image;
        $image->EOLid = $id;
        $image->title = isset($do->title) ? $do->title : null;
        $image->width = isset($do->width) ? $do->width : null;
        $image->height = isset($do->height) ? $do->height : null;
        $image->mediaURL = isset($do->mediaURL) ? $do->mediaURL : null;
        $image->identifier = isset($do->identifier) ? $do->identifier : null;
        $image->eolMediaURL = isset($do->eolMediaURL) ? $do->eolMediaURL : null;
        $image->description = isset($do->description) ? $do->description : null;
        $image->eolThumbnailURL = isset($do->eolThumbnailURL) ? $do->eolThumbnailURL : null;
        $image->dataObjectVersionID = isset($do->dataObjectVersionID) ? $do->dataObjectVersionID : null;
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
