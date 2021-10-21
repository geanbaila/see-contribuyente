<?php

namespace App\Business;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Persona extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'persona';
    protected $primaryKey = '_id';

}
