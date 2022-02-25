<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Number extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    protected $primaryKey = 'taxonID';
    protected $fillable = ['taxonID', 'kingdom', 'phylum', 'class', 'order', 'family', 'genus', 'species'];

    public $timestamps = false;
    public $incrementing = false;

    public function inline()
    {
        foreach(['kingdom', 'phylum', 'class', 'order', 'family', 'genus', 'species'] as $rank) {
            if (! empty($this->$rank)) {
                printf('<div class="number %s">%s %s</div>', $rank, $rank, number_format($this->$rank));
            }
        }
    }

    public function names()
    {
        if (! empty($this->jp))
            printf('<div class="number">和名 %s</div>', $this->jp);
        if (! empty($this->en))
            printf('<div class="number">英名 %s</div>', $this->en);
        if (! empty($this->img))
            printf('<div class="number">画像 %s</div>', $this->img);
    }
}
