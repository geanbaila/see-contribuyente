<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Business\Sede;
use App\Business\Agencia;
use App\Business\Documento;
use App\Business\Encargo;
use App\Business\Cliente;
use MongoDB\BSON\ObjectId;
use PDF;

class SaleController extends Controller
{

    public function register(Request $request) {
        $data = $request->all();
        $var = function() {     
            $data = func_get_arg(0);
            $stack = [];
            foreach($data as $item) {
                $element = explode(",",$item);
                array_push($stack, [
                    'descripcion' => $element[0],
                    'cantidad' => $element[1],
                    'precio' => $element[2],
                    'peso' => $element[3],
                    'total' => 0,
                ]);
            }
            return $stack;
        };
        $documento = Documento::find($data['documento']);

        // registrar o actualizar el cliente
        $documento_fecha = date('Y-m-d');
        if ($documento->alias === 'G') {
            // guía de remisión
            $insertCliente = [
                'documento' => $data['docRecibe'],
                'razon_social' => $data['nombreRecibe'],
                'direccion' => '',
            ];
        } else if ($documento->alias === 'B' || $documento->alias === 'F') {
            // boletas y facturas
            $insertCliente = [
                'documento' => $data['docEnvia'],
                'razon_social' => $data['nombreEnvia'],
                'direccion' => '',
            ];
        } else {
            // hey, no deberías estar aquí, el validador dice que hay campos obligatorios!
            $documento_fecha = "";
            $insertCliente = [];
        }
        if (strlen($data['encargoId']) > 0) {
            $clienteId = $data['clienteId'];
        } else {
            $cliente = (Cliente::create($insertCliente));
            $clienteId = $cliente->id;
        }
        
        // registrar o actualizar el encargo
        $emisorId = $data['agenciaOrigen']; // agencia que está en sesión. hacer luego
        $insertEncargo = [
            'doc_envia' => $data['docEnvia'],
            'nombre_envia' => $data['nombreEnvia'],
            'celular_envia' => $data['celularEnvia'],
            'email_envia' => $data['emailEnvia'],
            'fecha_envia' => $data['fechaEnvia'],

            'doc_recibe' => $data['docRecibe'],
            'nombre_recibe' => $data['nombreRecibe'],
            'celular_recibe' => $data['celularRecibe'],
            'email_recibe' => $data['emailRecibe'],
            'fecha_recibe' => $data['fechaRecibe'],

            // 'origen' => $data['origen'],
            'destino' => new ObjectId($data['destino']),
            'agencia_origen' => new ObjectId($data['agenciaOrigen']),
            'agencia_destino' => new ObjectId($data['agenciaDestino']),
            
            'emisor' => new ObjectId($emisorId),
            'cliente_id' => new ObjectId($clienteId),
            'medio_pago' => $data['medioPago'],
            'documento_id' => new ObjectId($data['documento']),
            'documento_serie' => $data['documentoSerie'],
            'documento_numero' => $data['documentoNumero'],
            'documento_fecha' => $documento_fecha,
            'encargo' => $var($data['encargo']),
        ];
        if (strlen($data['encargoId']) > 0) {
            $ObjectId = new ObjectId($data['encargoId']);
            $encargo = Encargo::where('_id', $ObjectId)->update($insertEncargo, ['upsert' => true]);
            $encargoId = $data['encargoId']; 
        } else {
            $encargo = Encargo::create($insertEncargo);
            $encargoId = $encargo['id'];
        }

        // registrar o actualizar el PDF
        if ($documento->alias === 'G') {
            $this->writeRemition($encargoId);
        } else if ($documento->alias === 'B' || $documento->alias === 'F') {
            $this->writeBill($encargoId);
        } else {
            // no escribir PDF
        }
        // hacer la asociacion de PDF en la BD. hacer luego
        
        return \response()->json(['result' => ['encargoId' => $encargoId, 'clienteId' => $clienteId] ]);
    }

    public function show() {
        $sede = Sede::all();
        $agenciaOrigen = Agencia::all(); // sacar los valores de la sesión del usuario según los perfiles que tenga asignado
        $documento = Documento::all();
        return view('sale.show')->with([ 'agenciaOrigen' => $agenciaOrigen, 'sede' => $sede, 'documento' => $documento ]);
    }

    public function edit($encargoId) {
        $encargo = Encargo::find($encargoId);
        return view('sale.show')->with([ 'sede' => $sede, 'documento' => $documento, 'encargo' => $encargo ]);

    }

    public function list() {
        $encargo = Encargo::all();
        return view('sale.list')->with([ 'encargo' => $encargo ]);
    }

    public function writeBill($encargoId) {
        $data = Encargo::findBill($encargoId);
        if ($data) {
            PDF::SetTitle($data['tituloDocumento']);
            PDF::setPrintHeader(false);
            PDF::setPrintFooter(false);
            PDF::AddPage();
            $width = 50;
            
            PDF::SetFillColor(255, 255, 255);
            PDF::SetTextColor(0);
            $fontSizeGrande = 9;
            $fontSizeRegular = 7;
            
            $border = false;
            $align_center = 'C';
            $align_left = 'L';
            $align_right = 'R';
            $height = '';
            $y = '';
            $x = ''; // 5
            $width = 60;
            
            PDF::SetFont('times', 'B', $fontSizeGrande);
            PDF::MultiCell($width, $height, $data['empresaComercial'], $border, $align_center,  1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, $data['empresaRazonSocial'], $border, $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, $data['empresaDireccionFiscal'], $border, $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, $data['empresaRuc'], $border, $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::Cell($width, $height, "Teléfono: " . $data['emisorAgenciaTelefono'], 'T', 1, 'L', 0);
            PDF::MultiCell($width, $height, "Terminal: ".$data['emisorAgenciaDireccion'], $border, $align_left, 1, 0, $x, $y);
            PDF::Ln();
            
            PDF::SetFont('times', 'B', $fontSizeGrande);
            PDF::Cell($width, $height, $data['emisorTipoDocumentoElectronico'], 'T', 1, 'C', 0);
            PDF::MultiCell($width, $height, $data['emisorNumeroDocumentoElectronico'], $border, $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::Cell($width/2, $height, "Fecha: " . $data['emisorFechaDocumentoElectronico'], 'B', 0, 'C', 0);
            PDF::Cell($width/2, $height, "Hora: " . $data['emisorHoraDocumentoElectronico'], 'B', 1, 'C', 0);

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, "Cliente: ".$data['clienteRazonSocial'], $border, $align_left, 1, 0, $x, $y);
            PDF::Ln();

            /* PDF::MultiCell($width, $height, "Dirección: " . $data['clienteDireccion'], $border, $align_left, 1, 0, $x, $y);
            PDF::Ln(); */
            PDF::Cell($width, $height, "DNI/RUC: " . $data['clienteDocumento'], 'B', 1, 'L', 0);

            PDF::MultiCell($width, $height, "Consigna:", $border, $align_left, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('', 'B');
            foreach($data['consigna'] as $nombre) {
                PDF::MultiCell($width, $height, $nombre, $border, $align_left, 1, 0, $x, $y);
                PDF::Ln();
            }

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell(20, $height, "Destino:", $border, $align_left, 1, 0, $x, $y);
            PDF::SetFont('times', 'B', $fontSizeGrande);
            PDF::MultiCell(40, $height, $data['destino'], $border, $align_right, 1, 0, $x, $y);
            PDF::Ln();
            PDF::Cell($width, $height, "", 'T', 1, 'L', 0, '', 10);
            
            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::Cell(24, $height, "DESCRIPCION", '', 0, 'L', 1);
            PDF::Cell(14, $height, "CANTIDAD", '', 0, 'L', 1);
            PDF::Cell(12, $height, "PRECIO", '', 0, 'R', 1);
            PDF::Cell(10, $height, "TOTAL", '', 0, 'R', 1);
            PDF::Ln();
            foreach($data['encargoDetalle'] as $encargo){
                PDF::MultiCell(24, $height, $encargo['descripcion'], $border, $align_left, 1, 0, $x, $y);
                PDF::MultiCell(14, $height, $encargo['cantidad'], $border, $align_center, 1, 0, $x, $y);
                PDF::MultiCell(12, $height, $encargo['precio'], $border, $align_center, 1, 0, $x, $y);
                PDF::MultiCell(10, $height, $encargo['total'], $border, $align_center, 1, 0, $x, $y);
                PDF::Ln();
            }
            PDF::Ln();
            PDF::Cell($width/3, $height, "SUBTOTAL", 'T', 0, 'L', 1);
            PDF::Cell($width/3, $height, "S/.", 'T', 0, 'C', 1);
            PDF::Cell($width/3, $height, "100.00", 'T', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell($width/3, $height, "OP.GRAVADA", '', 0, 'L', 1);
            PDF::Cell($width/3, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell($width/3, $height, "100.00", '', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell($width/3, $height, "OP.EXONERADA", '', 0, 'L', 1);
            PDF::Cell($width/3, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell($width/3, $height, "100.00", '', 0, 'R', 1);
            PDF::Ln();
            
            PDF::Cell($width/3, $height, "OP.GRATUITA", '', 0, 'L', 1);
            PDF::Cell($width/3, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell($width/3, $height, "100.00", '', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell($width/3, $height, "IGV 18%", '', 0, 'L', 1);
            PDF::Cell($width/3, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell($width/3, $height, "100.00", '', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell($width/3, $height, "IMPORTE TOTAL", '', 0, 'L', 1);
            PDF::Cell($width/3, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell($width/3, $height, "100.00", '', 0, 'R', 1);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeGrande);
            PDF::Cell($width, $height, "hash SUNAT:", 'T', 1, 'L', 1);
            PDF::Cell($width, $height, "1c7a92ae351d4e21ebdfb897508f59d6", '', 1, 'L', 1);
            PDF::Ln();
            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell(40, $height, "Representación impresa de ".$data['emisorTipoDocumentoElectronico'].".", $border, $align_left, 1, 0, $x, $y);
            PDF::MultiCell(30, $height, "imagenQR", true, $align_left, 1, 0, $x, $y);
            PDF::Ln(30);
            PDF::MultiCell($width, $height, env('EMPRESA_DISCLAIMER',''), $border, $align_left, 1, 0, $x, $y);
            $year = substr($data['emisorFechaDocumentoElectronico'], -4);
            $filename = "pruebas/" . $year . "/" . $encargoId . ".pdf";
            if (file_exists(base_path("public/" . $filename))) { unlink(base_path("public/" . $filename)); }
            PDF::Output(public_path($filename), 'F');
            PDF::reset();
        }
    }

    public function writeRemition($encargoId) {
        $data = Encargo::findRemition($encargoId);
        if ($data) {
            PDF::SetTitle($data['tituloDocumento']);
            PDF::setPrintHeader(false);
            PDF::setPrintFooter(false);
            PDF::AddPage();
            $width = 50;
            
            PDF::SetFillColor(255, 255, 255);
            PDF::SetTextColor(0);
            $fontSizeGrande = 9;
            $fontSizeRegular = 7;
            
            $border = false;
            $align_center = 'C';
            $align_left = 'L';
            $align_right = 'R';
            $height = '';
            $y = '';
            $x = ''; // 5
            $width = 60;
            PDF::MultiCell($width, $height, env('EMPRESA_DISCLAIMER',''), $border, $align_left, 1, 0, $x, $y);
            $year = substr($data['emisorFechaDocumentoElectronico'], -4);
            $filename = "pruebas/" . $year . "/" . $encargoId . ".pdf";
            if (file_exists(base_path($filename))) { unlink(base_path($filename)); }
            PDF::Output(public_path($filename), 'F');
            PDF::reset();
        }
    }
}
