<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iucn extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    protected $primaryKey = 'taxonID';

    public $timestamps = false;
    public $incrementing = false;

    public function show()
    {
        $html = "";
        foreach(['EX', 'EW', 'CR', 'EN', 'VU', 'CD', 'NT', 'LC', 'DD', 'NE'] as $key) {
            if ($this->$key == 1) {
                $html .= "<div class=$key>$key</div>";
            }
        }
        return $html;
    }
}
