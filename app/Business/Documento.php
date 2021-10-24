<?php

namespace App\Business;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Documento extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'documento';
    protected $primaryKey = '_id';

    protected $fillable = [
        'nombre',
        'alias',
    ];


}
