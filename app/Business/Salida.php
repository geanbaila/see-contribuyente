<?php

namespace App\Business;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Salida extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'salida';
    protected $primaryKey = '_id';

}
