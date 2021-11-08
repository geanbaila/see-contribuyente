<?php

namespace App\Business;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Adquiriente extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'adquiriente';
    protected $primaryKey = '_id';

    protected $fillable = [
        'documento',
        'razon_social',
        'nombre_comercial',
        'direccion',
    ];

    public function encargo()
    {
        return $this->hasMany('App\Business\Encargo');
    }
}
