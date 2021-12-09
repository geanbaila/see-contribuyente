<?php

namespace App\Business;

use Illuminate\Database\Eloquent\Model;

class Talonario extends Model
{
    protected $table = 'talonario';
    protected $primaryKey = 'id';

    protected $fillable = [
        'documento_serie',
        'documento_correlativo',
        'encargo',
    ];
}
