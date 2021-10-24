<?php

namespace App\Business;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Cliente extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'cliente';
    protected $primaryKey = '_id';

    protected $fillable = [
        'documento',
        'razon_social',
        'direccion',
    ];

    public function encargo()
    {
        return $this->hasMany('App\Business\Encargo');
    }
}
