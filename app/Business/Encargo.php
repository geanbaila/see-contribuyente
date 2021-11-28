<?php

namespace App\Business;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Encargo extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'encargo';
    protected $primaryKey = '_id';

    protected $fillable = [
        'doc_envia',
        'nombre_envia',
        'celular_envia',
        'email_envia',
        'fecha_hora_envia',
        
        'doc_recibe',
        'nombre_recibe',
        'celular_recibe',
        'email_recibe',
        'fecha_recibe',
        'hora_recibe',

        // 'origen',
        // 'destino',
        'agencia_origen',
        'agencia_destino',

        'agencia',
        'adquiriente',
        'medio_pago',
        'documento',
        'documento_serie',
        'documento_correlativo',
        'documento_fecha',
        'documento_hora',

        'detalle_gravado',
        'detalle_exonerado',
        'detalle_inafecto',
        'detalle_gravado_gratuito',
        'detalle_inafecto_gratuito',
        'cantidad_item',

        'monto_gravado',
        'monto_exonerado',
        'monto_inafecto',
        'monto_gravado_gratuito',
        'monto_inafecto_gratuito',

        'subtotal',
        'oferta',
        'importe_pagar',
        'importe_pagar_con_igv',
        'importe_pagar_sin_igv',
        'importe_pagar_igv',

        'url_documento_pdf',
        'url_documento_xml',
        'url_documento_cdr',
        'cdr_id',
        'cdr_codigo',
        'cdr_descripcion',
        'cdr_notas',
    ];
    
    public function agenciasOrigen() {
        return $this->belongsTo('App\Business\Agencia', 'agencia_origen');
    }
    
    public function agenciasDestino() {
        return $this->belongsTo('App\Business\Agencia', 'agencia_destino');
    }

    public function documentos() {
        return $this->belongsTo('App\Business\Documento','documento');
    }
    
    public function agencias() {
        return $this->belongsTo('App\Business\Agencia', 'agencia');
    }
    
    public function adquirientes() {
        return $this->belongsTo('App\Business\Adquiriente', 'adquiriente');
    }

    static function buscarBoleta($encargo_id) {
        $encargo = Encargo::find($encargo_id);
        if ($encargo->documentos->alias === 'B') {
            $fecha = explode("-", $encargo->documento_fecha);
            $documento_fecha_ddmmyyyy = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
            
            $data = [
                'titulo_documento' => $encargo->documentos->nombre,
                'emisor_nombre_comercial' => env('EMPRESA_COMERCIAL', 'NO DEFINIDO'),
                'emisor_razon_social' => env('EMPRESA_RAZON_SOCIAL', 'NO DEFINIDO'),
                'emisor_direccion_fiscal' => env('EMPRESA_DIRECCION', 'NO DEFINIDO'),
                'emisor_ruc' => env('EMPRESA_RUC','NO DEFINIDO'),
                'emisor_ubigeo' =>'150101',
                'emisor_direccion_pais' => 'PE',
                'emisor_direccion_departamento' => 'LIMA',
                'emisor_direccion_provincia' => 'LIMA',
                'emisor_direccion_distrito' => 'SAN BORJA',
                'emisor_direccion' => 'DIRECCIÓN DE LA AGENCIA DONDE SE EMITE..?',

                'adquiriente_ruc_dni_ce' => $encargo->adquirientes->documento,
                'adquiriente_nombre_comerial' => mb_strtoupper($encargo->adquirientes->razon_social),
                'adquiriente_razon_social' => mb_strtoupper($encargo->adquirientes->razon_social),
                'adquiriente_direccion_fiscal' => $encargo->adquirientes->direccion,
                'adquiriente_direccion_departamento' => $encargo->adquirientes->departamento,
                'adquiriente_direccion_provincia' => $encargo->adquirientes->provincia,
                'adquiriente_direccion_distrito' => $encargo->adquirientes->distrito,
                'adquiriente_direccion' => '',
                'adquiriente_direccion_pais' => 'PE',
                
                'emisor_agencia_direccion' => mb_strtoupper($encargo->agencias->direccion),
                'emisor_agencia_telefono' => $encargo->agencias->telefono,
                'emisor_tipo_documento_electronico' => strtoupper($encargo->documentos->nombre) . ' DE VENTA ELECTRÓNICA',
                'emisor_numero_documento_electronico' => $encargo->documento_serie . '-' . $encargo->documento_correlativo,
                'emisor_fecha_documento_electronico' => $encargo->documento_fecha, // yyyy-mm-dd
                'emisor_fecha_documento_electronico_pe' => $documento_fecha_ddmmyyyy,
                'emisor_hora_documento_electronico' => $encargo->documento_hora,

                'consigna' => [
                    'nombre' => $encargo->doc_recibe . ' - ' . mb_strtoupper($encargo->nombre_recibe),
                ],
                // 'destino' => mb_strtoupper($encargo->sedes->nombre),
                'destino' => mb_strtoupper($encargo->agenciasDestino->nombre),
                
                'detalle_gravado' =>$encargo->detalle_gravado,
                'detalle_exonerado' =>$encargo->detalle_exonerado,
                'detalle_inafecto' =>$encargo->detalle_inafecto,
                'detalle_gravado_gratuito' =>$encargo->detalle_gravado_gratuito,
                'detalle_inafecto_gratuito' =>$encargo->detalle_inafecto_gratuito,

                'monto_gravado' => $encargo->monto_gravado,
                'monto_exonerado' => $encargo->monto_exonerado,
                'monto_inafecto' => $encargo->monto_inafecto,
                'monto_gravado_gratuito' => $encargo->monto_gravado_gratuito,
                'monto_inafecto_gratuito' => $encargo->monto_inafecto_gratuito,

                'subtotal' => $encargo->subtotal,
                'oferta' => $encargo->oferta,
                'importe_pagar_con_igv' => $encargo->importe_pagar_con_igv,
                'importe_pagar_sin_igv' => $encargo->importe_pagar_sin_igv,
                'importe_pagar_igv' => $encargo->importe_pagar_igv,
            ]; 
        } else {
            $data = [];
        }
        return $data;
    }
    
    static function buscarFactura($encargo_id) {
        $encargo = Encargo::find($encargo_id);

        if ($encargo->documentos->alias === 'F') {
            $fecha = explode("-", $encargo->documento_fecha);
            $documento_fecha_ddmmyyyy = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
            
            $data = [
                'titulo_documento' => $encargo->documentos->nombre,
                'emisor_nombre_comercial' => env('EMPRESA_COMERCIAL', 'NO DEFINIDO'),
                'emisor_razon_social' => env('EMPRESA_RAZON_SOCIAL', 'NO DEFINIDO'),
                'emisor_direccion_fiscal' => env('EMPRESA_DIRECCION', 'NO DEFINIDO'),
                'emisor_ruc' => env('EMPRESA_RUC','NO DEFINIDO'),
                'emisor_ubigeo' =>'150101',
                'emisor_direccion_pais' => 'PE',
                'emisor_direccion_departamento' => 'LIMA',
                'emisor_direccion_provincia' => 'LIMA',
                'emisor_direccion_distrito' => 'SAN BORJA',
                'emisor_direccion' => 'DIRECCIÓN DE LA AGENCIA DONDE SE EMITE..?',

                'adquiriente_ruc' => $encargo->adquirientes->documento,
                'adquiriente_nombre_comerial' => mb_strtoupper($encargo->adquirientes->nombre_comercial),
                'adquiriente_razon_social' => mb_strtoupper($encargo->adquirientes->razon_social),
                'adquiriente_direccion_fiscal' => $encargo->adquirientes->direccion,
                'adquiriente_direccion_departamento' => $encargo->adquirientes->departamento,
                'adquiriente_direccion_provincia' => $encargo->adquirientes->provincia,
                'adquiriente_direccion_distrito' => $encargo->adquirientes->distrito,
                'adquiriente_direccion' => '',
                'adquiriente_direccion_pais' => 'PE',
                
                'emisor_agencia_direccion' => mb_strtoupper($encargo->agencias->direccion),
                'emisor_agencia_telefono' => $encargo->agencias->telefono,
                'emisor_tipo_documento_electronico' => strtoupper($encargo->documentos->nombre) . ' DE VENTA ELECTRÓNICA',
                'emisor_numero_documento_electronico' => $encargo->documento_serie . '-' . $encargo->documento_correlativo,
                'emisor_serie_documento_electronico' => $encargo->documento_serie,
                'emisor_correlativo_documento_electronico' => $encargo->documento_correlativo,
                'emisor_fecha_documento_electronico' => $encargo->documento_fecha, // yyyy-mm-dd
                'emisor_fecha_documento_electronico_pe' => $documento_fecha_ddmmyyyy,
                'emisor_hora_documento_electronico' => $encargo->documento_hora,

                'consigna' => [
                    'nombre' => $encargo->doc_recibe . ' - ' . mb_strtoupper($encargo->nombre_recibe),
                ],
                'destino' => mb_strtoupper($encargo->agenciasDestino->nombre),
                'documento_fecha' => $encargo->documento_fecha,
                'documento_hora' => $encargo->documento_hora,

                'detalle_gravado' => $encargo->detalle_gravado,
                'detalle_exonerado' => $encargo->detalle_exonerado,
                'detalle_inafecto' => $encargo->detalle_inafecto,
                'detalle_gravado_gratuito' => $encargo->detalle_gravado_gratuito,
                'detalle_inafecto_gratuito' => $encargo->detalle_inafecto_gratuito,

                'monto_gravado' => $encargo->monto_gravado,
                'monto_exonerado' => $encargo->monto_exonerado,
                'monto_inafecto' => $encargo->monto_inafecto,
                'monto_gravado_gratuito' => $encargo->monto_gravado_gratuito,
                'monto_inafecto_gratuito' => $encargo->monto_inafecto_gratuito,
                
                'subtotal' => $encargo->subtotal,
                'oferta' => $encargo->oferta,

                'importe_pagar_con_igv' => $encargo->importe_pagar_con_igv,
                'importe_pagar_sin_igv' => $encargo->importe_pagar_sin_igv,
                'importe_pagar_igv' => $encargo->importe_pagar_igv,
            ];
        } else {
            $data = [];
        }
        return $data;
    }

    static function buscarGuiaRemision($encargo_id) {
        $encargo = Encargo::find($encargo_id);
        if ($encargo->documentos->alias === 'G') {
            $fecha = explode("-", $encargo->documento_fecha);
            $documento_fecha_ddmmyyyy = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
            $data = [
                'titulo_documento' => $encargo->documentos->nombre,
                'emisor_nombre_comercial' => env('EMPRESA_COMERCIAL', 'NO DEFINIDO'),
                'emisor_razon_social' => env('EMPRESA_RAZON_SOCIAL', 'NO DEFINIDO'),
                'emisor_direccion_fiscal' => env('EMPRESA_DIRECCION', 'NO DEFINIDO'),
                'emisor_ruc' => env('EMPRESA_RUC','NO DEFINIDO'),
                'emisor_ubigeo' =>'150101',
                'emisor_direccion_pais' => 'PE',
                'emisor_direccion_departamento' => 'LIMA',
                'emisor_direccion_provincia' => 'LIMA',
                'emisor_direccion_distrito' => 'SAN BORJA',
                'emisor_direccion' => 'DIRECCIÓN DE LA AGENCIA DONDE SE EMITE..?',

                'emisor_agencia_direccion' => mb_strtoupper($encargo->emisores->direccion),
                'emisor_agencia_telefono' => $encargo->emisores->telefono,
                'emisor_tipo_documento_electronico' => strtoupper($encargo->documentos->nombre),
                'emisor_numero_documento_electronico' => $encargo->documento_serie . '-' . $encargo->documento_correlativo,
                'emisor_fecha_documento_electronico' => $encargo->documento_fecha,
                'emisor_fecha_documento_electronico_pe' => $documento_fecha_ddmmyyyy,
                'emisor_hora_documento_electronico' => $encargo->documento_hora,

                'adquiriente_razon_social' => mb_strtoupper($encargo->adquirientes->razon_social),
                'adquiriente_direccion_fiscal' => $encargo->adquirientes->direccion,
                'adquiriente_ruc' => $encargo->adquirientes->documento, // dni o ruc
                'consigna' => [
                    'nombre' => mb_strtoupper($encargo->nombre_recibe),
                ],
                // 'destino' => mb_strtoupper($encargo->sedes->nombre),
                'destino' => mb_strtoupper($encargo->agenciasDestino->nombre),

                'detalle_gravado' =>$encargo->detalle_gravado,
                'detalle_exonerado' =>$encargo->detalle_exonerado,
                'detalle_inafecto' =>$encargo->detalle_inafecto,
                'detalle_gravado_gratuito' =>$encargo->detalle_gravado_gratuito,
                'detalle_inafecto_gratuito' =>$encargo->detalle_inafecto_gratuito,

                'monto_gravado' => $encargo->monto_gravado,
                'monto_exonerado' => $encargo->monto_exonerado,
                'monto_inafecto' => $encargo->monto_inafecto,
                'monto_gravado_gratuito' => $encargo->monto_gravado_gratuito,
                'monto_inafecto_gratuito' => $encargo->monto_inafecto_gratuito,

                'subtotal' => $encargo->subtotal,
                'importe_pagar' => $encargo->importe_pagar,
            ];
            if(strlen($encargo->adquirientes->documento) === 11) {
                $data['adquiriente_ruc'] = $encargo->adquirientes->documento;
                $data['adquiriente_nombre_comerial'] = mb_strtoupper($encargo->adquirientes->nombre_comercial);
            } else {
                $data['adquiriente_dni'] = $encargo->adquirientes->documento;
                $data['adquiriente_nombre_comerial'] = mb_strtoupper($encargo->adquirientes->razon_social);
            }
        } else {
            $data = [];
        }
        return $data;
    }

    static function getNextSequence($encargo_id, $documento_serie) {
        $encargo = Encargo::find($encargo_id);
        if(!$encargo->documento_correlativo) {
            $manager = new \MongoDB\Driver\Manager("mongodb://".env('DB_HOST').":".env('DB_PORT'));
            $cmd = new \MongoDB\Driver\Command([
                "findandmodify" => "sequence",
                "query" => array("_id"=> $documento_serie),
                "update" => array('$inc' => array("seq"=> 1)),
            ]);
            $cursor = $manager->executeCommand(env('DB_DATABASE'), $cmd);
            $sequence = 0;
            foreach($cursor as $d){
                $sequence = $d->value->seq;
            }
            Talonario::insert(['documento_serie' => $documento_serie, 'documento_correlativo' => $sequence, 'encargo_id' => $encargo_id]);
        }
        return $sequence;
    }

}
