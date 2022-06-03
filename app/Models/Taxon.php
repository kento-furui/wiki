<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taxon extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    protected $primaryKey = 'taxonID';

    public $timestamps = false;
    public $incrementing = false;

    public function eol()
    {
        return $this->hasOne(Eol::class, 'EOLid', 'EOLid');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'EOLid', 'EOLid');
    }

    public function iucn()
    {
        return $this->hasOne(Iucn::class, 'taxonID', 'taxonID');
    }

    public function number()
    {
        return $this->hasOne(Number::class, 'taxonID', 'taxonID');
    }

    public function parent()
    {
        return $this->belongsTo(Taxon::class, 'parentNameUsageID');
    }

    public function children()
    {
        return $this->hasMany(Taxon::class, 'parentNameUsageID');
    }

    public function image()
    {
        return $this->hasOne(Image::class, 'EOLid', 'EOLid');
}
