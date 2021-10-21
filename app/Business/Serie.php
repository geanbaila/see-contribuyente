<?php

namespace App\Business;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Serie extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'serie';
    protected $primaryKey = '_id';
    
}
