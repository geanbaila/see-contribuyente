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
        $resultado = Encargo::find($encargoId);
        if ($resultado->documentos->alias === 'B' || $resultado->documentos->alias === 'F') {
            $fecha = explode("-", $resultado->documento_fecha);
            $documento_fecha_ddmmyyyy = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
            $data = [
                'empresaComercial' => env('EMPRESA_COMERCIAL', 'ACME'),
                'empresaRazonSocial' => env('EMPRESA_RAZON_SOCIAL', 'ACME'),
                'empresaDireccionFiscal' => env('EMPRESA_DIRECCION', 'ACME'),
                'empresaRuc' => env('EMPRESA_RUC','ACME'),

                'emisorAgenciaDireccion' => mb_strtoupper($resultado->emisores->direccion),
                'emisorAgenciaTelefono' => $resultado->emisores->telefono,
                'emisorTipoDocumentoElectronico' => strtoupper($resultado->documentos->nombre) . ' DE VENTA ELECTRÓNICA',
                'emisorNumeroDocumentoElectronico' => $resultado->documento_serie . ' - ' . $resultado->documento_numero,
                'emisorFechaDocumentoElectronico' => $documento_fecha_ddmmyyyy,

                'clienteRazonSocial' => mb_strtoupper($resultado->clientes->razon_social),
                'clienteDireccion' => $resultado->clientes->direccion,
                'clienteDocumento' => $resultado->clientes->documento,
                'consigna' => [
                    'nombre' => mb_strtoupper('nombre de prueba'.$resultado->nombre_recibe),
                ],
                'destino' => mb_strtoupper($resultado->sedes->nombre),
                'encargoDetalle' => $resultado->encargo,
            ];
        }
        
        return $data;
    }
}
