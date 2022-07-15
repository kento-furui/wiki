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

    public function taxon()
    {
        return $this->hasOne(Taxon::class, 'taxonID', 'taxonID');
    }
}
