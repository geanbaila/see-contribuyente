<?php

namespace App\Business;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Documento extends Model
{
    protected $table = 'documento';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'descripcion',
        'alias',
    ];

    static function nuevoCorrelativo($encargo_id, String $serie, $correlativo = '') {
        $id = DB::table('z_' . strtolower($serie))->insertGetId([
            'id' => (trim($correlativo)=='')?0:$correlativo,
        ]);
        $correlativo = sprintf("%0".env('ZEROFILL', 8)."d",$id);
        return $correlativo;
    }

}
