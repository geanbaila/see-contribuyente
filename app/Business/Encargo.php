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
        'fecha_envia',

        'doc_recibe',
        'nombre_recibe',
        'celular_recibe',
        'email_recibe',
        'fecha_recibe',

        // 'origen',
        'destino',
        'agencia_origen',
        'agencia_destino',

        'emisor',
        'cliente_id',
        'medio_pago',
        'documento_id',
        'documento_serie',
        'documento_numero',
        'documento_fecha',        
        'encargo',
        'subtotal',
        'importePagar',
    ];

    public function sedes() {
        return $this->belongsTo('App\Business\Sede', 'destino');
    }

    public function documentos() {
        return $this->belongsTo('App\Business\Documento','documento_id');
    }
    
    public function emisores() {
        return $this->belongsTo('App\Business\Agencia', 'emisor');
    }
    
    public function clientes() {
        return $this->belongsTo('App\Business\Cliente', 'cliente_id');
    }

    static function findBill($encargoId) {
        $encargo = Encargo::find($encargoId);
        if ($encargo->documentos->alias === 'B' || $encargo->documentos->alias === 'F') {
            $fecha = explode("-", $encargo->documento_fecha);
            $documento_fecha_ddmmyyyy = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
            $documento_fecha_hhiiss = "";
            $data = [
                'tituloDocumento' => $encargo->documentos->nombre,

                'empresaComercial' => env('EMPRESA_COMERCIAL', 'NO DEFINIDO'),
                'empresaRazonSocial' => env('EMPRESA_RAZON_SOCIAL', 'NO DEFINIDO'),
                'empresaDireccionFiscal' => env('EMPRESA_DIRECCION', 'NO DEFINIDO'),
                'empresaRuc' => env('EMPRESA_RUC','NO DEFINIDO'),

                'emisorAgenciaDireccion' => mb_strtoupper($encargo->emisores->direccion),
                'emisorAgenciaTelefono' => $encargo->emisores->telefono,
                'emisorTipoDocumentoElectronico' => strtoupper($encargo->documentos->nombre) . ' DE VENTA ELECTRÓNICA',
                'emisorNumeroDocumentoElectronico' => $encargo->documento_serie . '-' . $encargo->documento_numero,
                'emisorFechaDocumentoElectronico' => $documento_fecha_ddmmyyyy,
                'emisorHoraDocumentoElectronico' => $documento_fecha_hhiiss,

                'clienteRazonSocial' => mb_strtoupper($encargo->clientes->razon_social),
                'clienteDireccion' => $encargo->clientes->direccion,
                'clienteDocumento' => $encargo->clientes->documento,
                'consigna' => [
                    'nombre' => $encargo->doc_recibe . ' - ' . mb_strtoupper($encargo->nombre_recibe),
                ],
                'destino' => mb_strtoupper($encargo->sedes->nombre),
                'encargoDetalle' => $encargo->encargo,
                'subtotal' => $encargo->subtotal,
                'importePagar' => $encargo->importe_pagar,
            ];
        } else {
            $data = [];
        }
        return $data;
    }
    
    static function findRemition($encargoId) {
        $encargo = Encargo::find($encargoId);
        if ($encargo->documentos->alias === 'G') {
            $fecha = explode("-", $encargo->documento_fecha);
            $documento_fecha_ddmmyyyy = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
            $documento_fecha_hhiiss = "00:00:00";
            $data = [
                'tituloDocumento' => $encargo->documentos->nombre,

                'empresaComercial' => env('EMPRESA_COMERCIAL', 'NO DEFINIDO'),
                'empresaRazonSocial' => env('EMPRESA_RAZON_SOCIAL', 'NO DEFINIDO'),
                'empresaDireccionFiscal' => env('EMPRESA_DIRECCION', 'NO DEFINIDO'),
                'empresaRuc' => env('EMPRESA_RUC','NO DEFINIDO'),

                'emisorAgenciaDireccion' => mb_strtoupper($encargo->emisores->direccion),
                'emisorAgenciaTelefono' => $encargo->emisores->telefono,
                'emisorTipoDocumentoElectronico' => strtoupper($encargo->documentos->nombre),
                'emisorNumeroDocumentoElectronico' => $encargo->documento_serie . '-' . $encargo->documento_numero,
                'emisorFechaDocumentoElectronico' => $documento_fecha_ddmmyyyy,
                'emisorHoraDocumentoElectronico' => $documento_fecha_hhiiss,

                'clienteRazonSocial' => mb_strtoupper($encargo->clientes->razon_social),
                'clienteDireccion' => $encargo->clientes->direccion,
                'clienteDocumento' => $encargo->clientes->documento, // dni o ruc
                'consigna' => [
                    'nombre' => mb_strtoupper($encargo->nombre_recibe),
                ],
                'destino' => mb_strtoupper($encargo->sedes->nombre),
                'encargoDetalle' => $encargo->encargo,
                'subtotal' => $encargo->subtotal,
                'importePagar' => $encargo->importe_pagar,
            ];
        } else {
            $data = [];
        }
        return $data;
    }

    static function getNextSequence($encargoId, $documentoSerie) {
        $encargo = Encargo::find($encargoId);
        if(!$encargo->documento_numero) {
            $manager = new \MongoDB\Driver\Manager("mongodb://".env('DB_HOST').":".env('DB_PORT'));
            $cmd = new \MongoDB\Driver\Command([
                "findandmodify" => "sequence",
                "query" => array("_id"=> $documentoSerie),
                "update" => array('$inc' => array("seq"=> 1)),
            ]);
            $cursor = $manager->executeCommand(env('DB_DATABASE'), $cmd);
            $sequence = 0;
            foreach($cursor as $d){
                $sequence = $d->value->seq;
            }
            Catalogo::insert(['documento_serie' => $documentoSerie, 'documento_numero' => $sequence, 'encargo_id' => $encargoId]);
        }
        return $sequence;
    }


    
}
