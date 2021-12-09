<?php

namespace App\Business;

use Illuminate\Database\Eloquent\Model;

class TipoAfectacion extends Model
{
    protected $table = 'tipo_afectacion'; // catálogo 7
    protected $primaryKey = 'id';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
    ];
}
