<?php

namespace App\Business;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Vehiculo extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'vehiculo';
    protected $primaryKey = '_id';

}
