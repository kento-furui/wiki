<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iucn2 extends Model
{
    use HasFactory;
    protected $table = 'iucns2';
    protected $keyType = 'string';

    public $timestamps = false;
    public $incrementing = false;
}
