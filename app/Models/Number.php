<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Number extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    protected $primaryKey = 'taxonID';

    public $timestamps = false;
    public $incrementing = false;

    public function inline()
    {
        foreach(['kingdom', 'phylum', 'class', 'order', 'family', 'genus', 'species'] as $rank) {
            if (! empty($this->$rank)) {
                echo "<div class=\"number $rank\">$rank " . number_format($this->$rank) . '</div>';
            }
        }
    }
}
