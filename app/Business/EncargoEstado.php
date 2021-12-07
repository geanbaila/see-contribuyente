<?php

namespace App\Business;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class EncargoEstado extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'encargo_estado';
    protected $primaryKey = '_id';

    protected $fillable = [
        'nombre',
    ];
}
