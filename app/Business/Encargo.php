<?php

namespace App\Business;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Encargo extends Model
{
    protected $table = 'encargo';
    protected $primaryKey = 'id';

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

        'doc_recibe_alternativo',
        'nombre_recibe_alternativo',
        'celular_recibe_alternativo',
        'email_recibe_alternativo',
        // 'origen',
        // 'destino',
        'agencia_origen',
        'agencia_destino',

        'agencia_id',
        'adquiriente_id',
        'medio_pago',
        'documento_id',
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

        'detraccion_codigo',
        'detraccion_medio_pago',
        'detraccion_cta_banco',
        'detraccion_porcentaje',
        'detraccion_monto',

        'monto_gravado', // valor de la venta
        'monto_exonerado', // valor de la venta
        'monto_inafecto', // valor de la venta
        'monto_gravado_gratuito', // valor de la venta
        'monto_inafecto_gratuito', // valor de la venta

        // para UI
        'subtotal', // importe_pagar_con_igv
        'oferta',
        'descuento',

        'importe_pagar_con_igv',
        'importe_pagar_sin_igv',
        'importe_pagar_igv',

        'url_documento_pdf',
        'url_documento_xml',
        'url_documento_cdr',
        'url_documento_baja',
        'cdr_id',
        'cdr_codigo',
        'cdr_descripcion',
        'cdr_notas',

        'estado',
    ];
    
    public function agenciasOrigen() {
        return $this->belongsTo('App\Business\Agencia', 'agencia_origen');
    }
    
    public function agenciasDestino() {
        return $this->belongsTo('App\Business\Agencia', 'agencia_destino');
    }

    public function documentos() {
        return $this->belongsTo('App\Business\Documento','documento_id');
    }
    
    public function agencias() {
        return $this->belongsTo('App\Business\Agencia', 'agencia_id');
    }
    
    public function adquirientes() {
        return $this->belongsTo('App\Business\Adquiriente', 'adquiriente_id');
    }

    public function estados() {
        return $this->belongsTo('App\Business\EncargoEstado', 'estado');
    }

    public function detalles()
    {
        return $this->hasMany('App\Business\EncargoDetalle');
    }

    static function buscarBoleta($encargo_id) {
        $encargo = Encargo::find($encargo_id);
        $data = [];
        if ($encargo->documentos->alias === 'B') {
            $data = [
                'titulo_documento' => $encargo->documentos->descripcion,
                'emisor_nombre_comercial' => env('EMPRESA_COMERCIAL', 'NO DEFINIDO'),
                'emisor_razon_social' => env('EMPRESA_RAZON_SOCIAL', 'NO DEFINIDO'),
                'emisor_ruc' => env('EMPRESA_RUC','NO DEFINIDO'),
                'emisor_ubigeo' => env('EMPRESA_UBIGEO', 'NO DEFINIDO'),
                'emisor_direccion_pais' => 'PE',
                'emisor_direccion_departamento' => env('EMPRESA_DEPARTAMENTO', 'NO DEFINIDO'),
                'emisor_direccion_provincia' => env('EMPRESA_PROVINCIA', 'NO DEFINIDO'),
                'emisor_direccion_distrito' => env('EMPRESA_DISTRITO', 'NO DEFINIDO'),
                'emisor_direccion_fiscal' => env('EMPRESA_DIRECCION', 'NO DEFINIDO'),

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
                'emisor_tipo_documento_electronico' => strtoupper($encargo->documentos->descripcion),
                'emisor_numero_documento_electronico' => $encargo->documento_serie . '-' . $encargo->documento_correlativo,
                'emisor_serie_documento_electronico' => $encargo->documento_serie,
                'emisor_correlativo_documento_electronico' => $encargo->documento_correlativo,
                'emisor_fecha_documento_electronico' => $encargo->documento_fecha, // yyyy-mm-dd
                'emisor_hora_documento_electronico' => $encargo->documento_hora,

                'consigna' => [
                    'nombre' => $encargo->doc_recibe . ' - ' . mb_strtoupper($encargo->nombre_recibe),
                ],
                'destino' => mb_strtoupper($encargo->agenciasDestino->sedes->nombre),
                'destino_direccion' => mb_strtoupper($encargo->agenciasDestino->direccion),
                'documento_fecha' => $encargo->documento_fecha,
                'documento_hora' => $encargo->documento_hora,
                
                'detraccion_codigo' => $encargo->detraccion_codigo,
                'detraccion_medio_pago' => $encargo->detraccion_medio_pago,
                'detraccion_cta_banco' => $encargo->detraccion_cta_banco,
                'detraccion_porcentaje' => $encargo->detraccion_porcentaje,
                'detraccion_monto' => $encargo->detraccion_monto,

                'detalle' => $encargo->detalles,

                'monto_gravado' => $encargo->monto_gravado,
                'monto_exonerado' => $encargo->monto_exonerado,
                'monto_inafecto' => $encargo->monto_inafecto,
                'monto_gravado_gratuito' => $encargo->monto_gravado_gratuito,
                'monto_inafecto_gratuito' => $encargo->monto_inafecto_gratuito,
                
                'subtotal' => $encargo->subtotal,
                'oferta' => $encargo->oferta,
                'descuento' => $encargo->descuento,

                'importe_pagar_con_igv' => $encargo->importe_pagar_con_igv,
                'importe_pagar_sin_igv' => $encargo->importe_pagar_sin_igv,
                'importe_pagar_igv' => $encargo->importe_pagar_igv,
            ]; 
        }
        return $data;
    }
    
    static function buscarFactura($encargo_id) {
        $encargo = Encargo::find($encargo_id);
        $data = [];
        if ($encargo->documentos->alias === 'F') {    
            $data = [
                'titulo_documento' => $encargo->documentos->descripcion,
                'emisor_nombre_comercial' => env('EMPRESA_COMERCIAL', 'NO DEFINIDO'),
                'emisor_razon_social' => env('EMPRESA_RAZON_SOCIAL', 'NO DEFINIDO'),
                'emisor_ruc' => env('EMPRESA_RUC','NO DEFINIDO'),
                'emisor_ubigeo' => env('EMPRESA_UBIGEO', 'NO DEFINIDO'),
                'emisor_direccion_pais' => 'PE',
                'emisor_direccion_departamento' => env('EMPRESA_DEPARTAMENTO', 'NO DEFINIDO'),
                'emisor_direccion_provincia' => env('EMPRESA_PROVINCIA', 'NO DEFINIDO'),
                'emisor_direccion_distrito' => env('EMPRESA_DISTRITO', 'NO DEFINIDO'),
                'emisor_direccion_fiscal' => env('EMPRESA_DIRECCION', 'NO DEFINIDO'),
                
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
                'emisor_tipo_documento_electronico' => strtoupper($encargo->documentos->descripcion),
                'emisor_numero_documento_electronico' => $encargo->documento_serie . '-' . $encargo->documento_correlativo,
                'emisor_serie_documento_electronico' => $encargo->documento_serie,
                'emisor_correlativo_documento_electronico' => $encargo->documento_correlativo,
                'emisor_fecha_documento_electronico' => $encargo->documento_fecha, // yyyy-mm-dd
                'emisor_hora_documento_electronico' => $encargo->documento_hora,

                'consigna' => [
                    'nombre' => $encargo->doc_recibe . ' - ' . mb_strtoupper($encargo->nombre_recibe),
                ],
                'destino' => mb_strtoupper($encargo->agenciasDestino->sedes->nombre),
                'destino_direccion' => mb_strtoupper($encargo->agenciasDestino->direccion),
                'documento_fecha' => $encargo->documento_fecha,
                'documento_hora' => $encargo->documento_hora,

                'detraccion_codigo' => $encargo->detraccion_codigo,
                'detraccion_medio_pago' => $encargo->detraccion_medio_pago,
                'detraccion_cta_banco' => $encargo->detraccion_cta_banco,
                'detraccion_porcentaje' => $encargo->detraccion_porcentaje,
                'detraccion_monto' => $encargo->detraccion_monto,

                'detalle' => $encargo->detalles,
                
                'monto_gravado' => $encargo->monto_gravado,
                'monto_exonerado' => $encargo->monto_exonerado,
                'monto_inafecto' => $encargo->monto_inafecto,
                'monto_gravado_gratuito' => $encargo->monto_gravado_gratuito,
                'monto_inafecto_gratuito' => $encargo->monto_inafecto_gratuito,
                
                'subtotal' => $encargo->subtotal,
                'oferta' => $encargo->oferta,
                'descuento' => $encargo->descuento,

                'importe_pagar_con_igv' => $encargo->importe_pagar_con_igv,
                'importe_pagar_sin_igv' => $encargo->importe_pagar_sin_igv,
                'importe_pagar_igv' => $encargo->importe_pagar_igv,
            ];
        }
        return $data;
    }

    static function buscarGuiaRemision($encargo_id) {
        $encargo = Encargo::find($encargo_id);
        if ($encargo->documentos->alias === 'G') {
            $data = [
                'titulo_documento' => $encargo->documentos->descripcion,
                'emisor_nombre_comercial' => env('EMPRESA_COMERCIAL', 'NO DEFINIDO'),
                'emisor_razon_social' => env('EMPRESA_RAZON_SOCIAL', 'NO DEFINIDO'),
                'emisor_ruc' => env('EMPRESA_RUC','NO DEFINIDO'),
                'emisor_ubigeo' => env('EMPRESA_UBIGEO', 'NO DEFINIDO'),
                'emisor_direccion_pais' => 'PE',
                'emisor_direccion_departamento' => env('EMPRESA_DEPARTAMENTO', 'NO DEFINIDO'),
                'emisor_direccion_provincia' => env('EMPRESA_PROVINCIA', 'NO DEFINIDO'),
                'emisor_direccion_distrito' => env('EMPRESA_DISTRITO', 'NO DEFINIDO'),
                'emisor_direccion_fiscal' => env('EMPRESA_DIRECCION', 'NO DEFINIDO'),
                 
                'emisor_agencia_direccion' => mb_strtoupper($encargo->agencias->direccion),
                'emisor_agencia_telefono' => $encargo->agencias->telefono,
                'emisor_tipo_documento_electronico' => strtoupper($encargo->documentos->descripcion),
                'emisor_numero_documento_electronico' => $encargo->documento_serie . '-' . $encargo->documento_correlativo,
                'emisor_serie_documento_electronico' => $encargo->documento_serie,
                'emisor_correlativo_documento_electronico' => $encargo->documento_correlativo,
                'emisor_fecha_documento_electronico' => $encargo->documento_fecha, // yyyy-mm-dd
                'emisor_hora_documento_electronico' => $encargo->documento_hora,

                'adquiriente_ruc' => $encargo->adquirientes->documento,

                'adquiriente_ruc_dni_ce' => $encargo->adquirientes->documento,
                'adquiriente_nombre_comerial' => mb_strtoupper($encargo->adquirientes->nombre_comercial),
                'adquiriente_razon_social' => mb_strtoupper($encargo->adquirientes->razon_social),
                'adquiriente_direccion_fiscal' => $encargo->adquirientes->direccion,
                'adquiriente_direccion_departamento' => $encargo->adquirientes->departamento,
                'adquiriente_direccion_provincia' => $encargo->adquirientes->provincia,
                'adquiriente_direccion_distrito' => $encargo->adquirientes->distrito,
                'adquiriente_direccion' => '',
                'adquiriente_direccion_pais' => 'PE',

                'consigna' => [
                    'nombre' => mb_strtoupper($encargo->nombre_recibe),
                ],
                'destino' => mb_strtoupper($encargo->agenciasDestino->sedes->nombre),
                'destino_direccion' => mb_strtoupper($encargo->agenciasDestino->direccion),
                'documento_fecha' => $encargo->documento_fecha,
                'documento_hora' => $encargo->documento_hora,

                'detraccion_codigo' => $encargo->detraccion_codigo,
                'detraccion_medio_pago' => $encargo->detraccion_medio_pago,
                'detraccion_cta_banco' => $encargo->detraccion_cta_banco,
                'detraccion_porcentaje' => $encargo->detraccion_porcentaje,
                'detraccion_monto' => $encargo->detraccion_monto,

                'detalle' => $encargo->detalles,

                'monto_gravado' => $encargo->monto_gravado,
                'monto_exonerado' => $encargo->monto_exonerado,
                'monto_inafecto' => $encargo->monto_inafecto,
                'monto_gravado_gratuito' => $encargo->monto_gravado_gratuito,
                'monto_inafecto_gratuito' => $encargo->monto_inafecto_gratuito,

                'subtotal' => $encargo->subtotal,
                'oferta' => $encargo->oferta,
                'descuento' => $encargo->descuento,

                'importe_pagar_con_igv' => $encargo->importe_pagar_con_igv,
                'importe_pagar_sin_igv' => $encargo->importe_pagar_sin_igv,
                'importe_pagar_igv' => $encargo->importe_pagar_igv,
            ];   
        }
        return $data;
    }

    static function getAllGuiaRemision($doc_recibe_envia, $documento) {
        $guia_remision = 3;
        $encargo = Encargo::select('id', 'doc_envia', 'doc_recibe','nombre_envia', 'nombre_recibe', 'agencia_destino', 'fecha_hora_envia', 'documento_fecha', 'documento_hora', 'oferta', DB::raw('(select direccion from agencia where id=agencia_destino) as direccion'));
        $encargo->where('documento_id', $guia_remision);
        if (strlen($doc_recibe_envia)>0) {
            $encargo->whereRaw('(doc_envia='. $doc_recibe_envia.' or doc_recibe=' . $doc_recibe_envia.')');
        }
        if (strlen($documento)>0) {
            $encargo->whereRaw("(concat(documento_serie,'-',documento_correlativo) like '%$documento%')");
        }
        
        $result = $encargo->get()->sortByDesc('fecha_hora_envia')->values();
        return $result;
    }

    
}
