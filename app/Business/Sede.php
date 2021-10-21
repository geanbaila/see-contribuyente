<?php

namespace App\Business;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Sede extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'sede';
    protected $primaryKey = '_id';

    public function agencia()
    {
        return $this->hasMany('App\Business\Agencia');
    }

}
