<?php

namespace App\Business;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Reparacion extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'reparacion';
    protected $primaryKey = '_id';

}
