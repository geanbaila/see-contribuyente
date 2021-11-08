<?php

namespace App\Business;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Talonario extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'talonario';
    protected $primaryKey = '_id';

    protected $fillable = [
        'documento_serie',
        'documento_correlativo',
        'encargo',
    ];
}
