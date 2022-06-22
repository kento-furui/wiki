<?php

namespace App\Console\Commands;

use App\Models\Taxon;
use App\Models\Number;
use Illuminate\Console\Command;

class NumbersCommand extends Command
{
    public $ranks;

    protected $signature = 'numbers:store {taxonID}';
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $taxa = Taxon::whereIn('taxonomicStatus', ['valid', 'accepted'])
        //->where('taxonID', $this->argument('taxonID'))
        ->where('taxonID', '>', $this->argument('taxonID'))
        //->where('taxonRank', $this->argument('taxonID'))
        ->has('children')
        ->doesntHave('number')
        ->orderBy('taxonID')
        //->limit(10000)
        ->get();

        foreach ($taxa as $taxon) {
            echo $taxon->taxonID . " " . $taxon->canonicalName . "\n";
            $this->getNumber($taxon->taxonID);
        }
    }

    public function sum(Taxon $taxon)
    {
        $temp = array();
        foreach ($taxon->children as $c) {
            $key = $c->taxonRank;
            if (array_key_exists($key, $temp)) {
                $temp[$key]++;
            } else {
                $temp[$key] = 1;
            }

            if ($c->number) {
                $json = json_decode($c->number->json);
                foreach ($json as $key => $val) {
                    if (array_key_exists($key, $temp)) {
                        $temp[$key] += $val;
                    } else {
                        $temp[$key] = $val;
                    }
                }
            }
        }

        $number = new Number;
        $number->taxonID = $taxon->taxonID;
        $number->json = json_encode($temp);
        $number->save();

        return redirect('/page/' . $taxon->taxonID);
    }

    public function getNumber($taxonID)
    {
        $taxon = Taxon::find($taxonID);

        $this->ranks = array();

        $this->_ranks($taxon);
        
        $number = new Number;
        $number->taxonID = $taxonID;
        $number->json = json_encode($this->ranks);
        $number->save();

        echo $number->json . "\n";
    }

    private function _ranks(Taxon $taxon)
    {
        foreach ($taxon->children as $c) {
            $key = $c->taxonRank;
            if (array_key_exists($key, $this->ranks)) {
                $this->ranks[$key]++;
            } else {
                $this->ranks[$key] = 1;
            }
            $this->_ranks($c);
        }
    }
}
