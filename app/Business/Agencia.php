<?php

namespace App\Business;

use Illuminate\Database\Eloquent\Model;

class Agencia extends Model
{
    protected $table = 'agencia';
    protected $primaryKey = 'id';

    protected $fillable = [
        'sede_id', // nuevo
        'nombre',
        'direccion',
        'telefono',
    ];

    public function sedes()
    {
        return $this->belongsTo('App\Business\Sede', 'sede_id');
    }

}
