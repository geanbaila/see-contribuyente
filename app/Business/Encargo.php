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

        'agencia',
        'adquiriente',
        'medio_pago',
        'documento',
        'documento_serie',
        'documento_correlativo',
        'documento_fecha',        
        'encargo',
        'subtotal',
        'importe_pagar_con_igv',
        'importe_pagar_sin_igv',
        'importe_pagar_igv',
        'importe_pagar',
    ];

    public function sedes() {
        return $this->belongsTo('App\Business\Sede', 'destino');
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

    static function findBill($encargoId) {
        $encargo = Encargo::find($encargoId);
        if ($encargo->documentos->alias === 'F' || $encargo->documentos->alias === 'B') {
            $fecha = explode("-", $encargo->documento_fecha);
            $documento_fecha_ddmmyyyy = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
            $documento_fecha_hhiiss = "";
            
            $data = [
                'tituloDocumento' => $encargo->documentos->nombre,
                'emisorNombreComercial' => env('EMPRESA_COMERCIAL', 'NO DEFINIDO'),
                'emisorRazonSocial' => env('EMPRESA_RAZON_SOCIAL', 'NO DEFINIDO'),
                'emisorDireccionFiscal' => env('EMPRESA_DIRECCION', 'NO DEFINIDO'),
                'emisorRUC' => env('EMPRESA_RUC','NO DEFINIDO'),
                'emisorUbigeo' =>'150101',
                'emisorDireccionPais' => 'PE',
                'emisorDireccionDepartamento' => 'LIMA',
                'emisorDireccionProvincia' => 'LIMA',
                'emisorDireccionDistrito' => 'SAN BORJA',
                'emisorDireccion' => 'DIRECCIÓN DE LA AGENCIA DONDE SE EMITE..?',

                'adquirienteRazonSocial' => mb_strtoupper($encargo->adquirientes->razon_social),
                'adquirienteDireccionFiscal' => $encargo->adquirientes->direccion,
                'adquirienteDireccionDepartamento' => $encargo->adquirientes->departamento,
                'adquirienteDireccionProvincia' => $encargo->adquirientes->provincia,
                'adquirienteDireccionDistrito' => $encargo->adquirientes->distrito,
                'adquirienteDireccion' => '',
                'adquirienteDireccionPais' => 'PE',
                
                'emisorAgenciaDireccion' => mb_strtoupper($encargo->agencias->direccion),
                'emisorAgenciaTelefono' => $encargo->agencias->telefono,
                'emisorTipoDocumentoElectronico' => strtoupper($encargo->documentos->nombre) . ' DE VENTA ELECTRÓNICA',
                'emisorNumeroDocumentoElectronico' => $encargo->documento_serie . '-' . $encargo->documento_correlativo,
                'emisorFechaDocumentoElectronico' => $encargo->documento_fecha, // yyyy-mm-dd
                'emisorHoraDocumentoElectronico' => $documento_fecha_hhiiss,

                'consigna' => [
                    'nombre' => $encargo->doc_recibe . ' - ' . mb_strtoupper($encargo->nombre_recibe),
                ],
                'destino' => mb_strtoupper($encargo->sedes->nombre),
                'encargoDetalle' => $encargo->encargo,

                'importePagarConIGV' => $encargo->importe_pagar_con_igv,
                'importePagarSinIGV' => $encargo->importe_pagar_sin_igv,
                'importePagarIGV' => $encargo->importe_pagar_igv,
                'subtotal' => $encargo->subtotal,
            ];
            if($encargo->documentos->alias === 'F') {
                $data['adquirienteRUC'] = $encargo->adquirientes->documento;
                $data['adquirienteNombreComerial'] = mb_strtoupper($encargo->adquirientes->nombre_comercial);
            }
            if($encargo->documentos->alias === 'B') {
                $data['adquirienteDNI'] = $encargo->adquirientes->documento;
                $data['adquirienteNombreComerial'] = mb_strtoupper($encargo->adquirientes->razon_social);
            }
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
                'emisorNombreComercial' => env('EMPRESA_COMERCIAL', 'NO DEFINIDO'),
                'emisorRazonSocial' => env('EMPRESA_RAZON_SOCIAL', 'NO DEFINIDO'),
                'emisorDireccionFiscal' => env('EMPRESA_DIRECCION', 'NO DEFINIDO'),
                'emisorRUC' => env('EMPRESA_RUC','NO DEFINIDO'),
                'emisorUbigeo' =>'150101',
                'emisorDireccionPais' => 'PE',
                'emisorDireccionDepartamento' => 'LIMA',
                'emisorDireccionProvincia' => 'LIMA',
                'emisorDireccionDistrito' => 'SAN BORJA',
                'emisorDireccion' => 'DIRECCIÓN DE LA AGENCIA DONDE SE EMITE..?',

                'emisorAgenciaDireccion' => mb_strtoupper($encargo->emisores->direccion),
                'emisorAgenciaTelefono' => $encargo->emisores->telefono,
                'emisorTipoDocumentoElectronico' => strtoupper($encargo->documentos->nombre),
                'emisorNumeroDocumentoElectronico' => $encargo->documento_serie . '-' . $encargo->documento_correlativo,
                'emisorFechaDocumentoElectronico' => $encargo->documento_fecha,
                'emisorHoraDocumentoElectronico' => $documento_fecha_hhiiss,

                'adquirienteRazonSocial' => mb_strtoupper($encargo->adquirientes->razon_social),
                'adquirienteDireccionFiscal' => $encargo->adquirientes->direccion,
                'adquirienteRUC' => $encargo->adquirientes->documento, // dni o ruc
                'consigna' => [
                    'nombre' => mb_strtoupper($encargo->nombre_recibe),
                ],
                'destino' => mb_strtoupper($encargo->sedes->nombre),
                'encargoDetalle' => $encargo->encargo,
                'subtotal' => $encargo->subtotal,
                'importePagar' => $encargo->importe_pagar,
            ];
            if(strlen($encargo->adquirientes->documento) === 11) {
                $data['adquirienteRUC'] = $encargo->adquirientes->documento;
                $data['adquirienteNombreComerial'] = mb_strtoupper($encargo->adquirientes->nombre_comercial);
            } else {
                $data['adquirienteDNI'] = $encargo->adquirientes->documento;
                $data['adquirienteNombreComerial'] = mb_strtoupper($encargo->adquirientes->razon_social);
            }
        } else {
            $data = [];
        }
        return $data;
    }

    static function getNextSequence($encargoId, $documentoSerie) {
        $encargo = Encargo::find($encargoId);
        if(!$encargo->documento_correlativo) {
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
            Talonario::insert(['documento_serie' => $documentoSerie, 'documento_correlativo' => $sequence, 'encargo_id' => $encargoId]);
        }
        return $sequence;
    }


    
}
