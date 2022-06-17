<?php

namespace App\Console\Commands;

use App\Models\Taxon;
use App\Models\Image;
use Exception;
use Illuminate\Console\Command;

class ImagesCommand extends Command
{
    protected $signature = 'images:store {eol}';
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $taxa = Taxon::whereIn('taxonomicStatus', ['valid', 'accepted'])
        ->where('EOLid', '>', $this->argument('eol'))
        ->doesntHave('image')
        ->orderBy('EOLid')
        ->limit(50000)
        ->get();

        foreach ($taxa as $taxon) {
            echo $taxon->EOLid . " " . $taxon->canonicalName . "\n";
            $this->getImages($taxon->EOLid);
        }
    }

    public function getImages($id)
    {
        $url = "https://eol.org/api/pages/1.0/$id.json?details=true&images_per_page=1";
        $response = @file_get_contents($url);
        $json = json_decode($response);
        if (!isset($json->taxonConcept->dataObjects)) return false;
        
        foreach ($json->taxonConcept->dataObjects as $do) {
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
            try {
                $image->save();
            } catch(Exception $e) {
                echo $e->getMessage();
            }
            echo $do->eolThumbnailURL . "\n";
        }
    }
}
