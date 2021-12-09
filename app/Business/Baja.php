<?php

namespace App\Business;

use Illuminate\Database\Eloquent\Model;

class Baja extends Model
{
    protected $table = 'baja';
    protected $primaryKey = 'id';

    protected $fillable = [
        'url_documento_cdr',
    ];

    static function getNextSequence() {
        return 1;
    }
}
