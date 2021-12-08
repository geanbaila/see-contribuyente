<?php

namespace App\Business;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Baja extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'baja';
    protected $primaryKey = '_id';

    protected $fillable = [
        'url_documento_cdr',
    ];

    static function getNextSequence() {
        $manager = new \MongoDB\Driver\Manager("mongodb://".env('DB_HOST').":".env('DB_PORT'));
        $cmd = new \MongoDB\Driver\Command([
            'findandmodify' => 'sequence',
            'query' => array('_id' => 'BAJA'),
            'update' => array('$inc' => array('seq'=> 1)),
        ]);
        $cursor = $manager->executeCommand(env('DB_DATABASE'), $cmd);
        $sequence = 0;
        foreach($cursor as $d){
            $sequence = $d->value->seq;
        }
        return $sequence;
    }
}
