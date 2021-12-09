<?php

namespace App\Business;

use Illuminate\Database\Eloquent\Model;

class Adquiriente extends Model
{
    protected $table = 'adquiriente';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tipo_documento', // nuevo
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
