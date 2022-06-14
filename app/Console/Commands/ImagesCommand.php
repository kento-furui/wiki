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
        ->doesntHave('images')
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
        $url = "https://eol.org/api/pages/1.0/$id.json?details=true&images_per_page=30";
        $response = @file_get_contents($url);
        $json = json_decode($response);
        if (!isset($json->taxonConcept->dataObjects)) return false;
        
        foreach ($json->taxonConcept->dataObjects as $do) {
            $image = new Image;
            $image->EOLid = $id;
            isset($do->title) ? $image->title = $do->title : null;
            isset($do->width) ? $image->width = $do->width : null;
            isset($do->height) ? $image->height = $do->height : null;
            isset($do->mediaURL) ? $image->mediaURL = $do->mediaURL : null;
            isset($do->identifier) ? $image->identifier = $do->identifier : null;
            isset($do->eolMediaURL) ? $image->eolMediaURL = $do->eolMediaURL : null;
            isset($do->description) ? $image->description = $do->description : null;
            isset($do->eolThumbnailURL) ? $image->eolThumbnailURL = $do->eolThumbnailURL : null;
            try {
                $image->save();
            } catch(Exception $e) {
                echo $e->getMessage();
            }
            echo $do->eolThumbnailURL . "\n";
        }
    }
}
