<?php

namespace App\Business;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Catalogo extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'catalogo';
    protected $primaryKey = '_id';

    protected $fillable = [
        'documento_serie',
        'documento_numero',
        'encargo_id',
    ];
}
