<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eol extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    protected $primaryKey = 'EOLid';

    public $timestamps = false;
    public $incrementing = false;
}
