<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Business\Sede;
use App\Business\Carga;
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
                if ($element[0] !== "--"){
                    $peso = $element[1];
                    $cantidad = $element[2];
                    $precio = $element[3];
                    $carga = Carga::find($element[0]);
                    if ($carga) {
                        $total = number_format($cantidad*$precio*$peso, 2, '.', '');
                        array_push($stack, [
                            'carga_id' => $carga->id,
                            'descripcion' => $carga->nombre,
                            'cantidad' => $cantidad,
                            'precio' => $precio,
                            'peso' => $peso,
                            'total' => $total,
                        ]);
                    }
                }
            }
            return [$stack];
        };
        if (!$var($data['encargo'])) {
            return \response()->json(['result' => ['status' => 'fails', 'message' => 'No ha ingresado el detalle de la encomienda.']]);
        }

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
            'encargo' => $var($data['encargo'])[0],
            'subtotal' => number_format($data['subtotal'], 2, '.', ''),
            'importe_pagar' => number_format($data['importePagar'], 2, '.', ''),
        ];
        
        if (strlen($data['encargoId']) > 0) {
            $ObjectId = new ObjectId($data['encargoId']);
            $encargo = Encargo::where('_id', $ObjectId)->update($insertEncargo, ['upsert' => true]);
            if (!$encargo) {
                return \response()->json(['result' => ['status' => 'fails', 'message' => 'No se pudo grabar los cambios, inténtalo otra vez.']]);
            }
            $encargoId = $data['encargoId']; 
            $documentoNumero = $data['documentoNumero'];
        } else {
            $encargo = Encargo::create($insertEncargo);
            $encargoId = $encargo['id'];
            $ObjectId = new ObjectId($encargoId);
            $documentoNumero = sprintf("%0".env('ZEROFILL',6)."d",Encargo::getNextSequence($encargoId, $data['documentoSerie']));
            $encargo = Encargo::where('_id', $ObjectId)->update(['documento_numero' => $documentoNumero]);
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
        
        return \response()->json([
            'result' => [
                'status' => 'OK', 
                'message' => 'Registro correctamente', 
                'encargoId' => $encargoId, 
                'clienteId' => $clienteId, 
                'documentoNumero' => $documentoNumero
            ]
        ]);
    }

    public function show() {
        $sede = Sede::all();
        $carga = Carga::orderBy('nombre','asc')->get();
        $agenciaOrigen = Agencia::all(); // sacar los valores de la sesión del usuario según los perfiles que tenga asignado
        $documento = Documento::all();
        return view('sale.show')->with([ 'agenciaOrigen' => $agenciaOrigen, 'sede' => $sede, 'documento' => $documento, 'carga' => $carga ]);
    }

    public function edit($encargoId) {
        $sede = Sede::all();
        $carga = Carga::all();
        $agenciaOrigen = Agencia::all(); // sacar los valores de la sesión del usuario según los perfiles que tenga asignado
        $documento = Documento::all();
        $encargo = Encargo::find($encargoId);
        return view('sale.edit')->with([ 'agenciaOrigen' => $agenciaOrigen, 'sede' => $sede, 'documento' => $documento, 'carga' => $carga, 'encargo' => $encargo ]);
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
            $fontSizeGigante = 10;
            $fontSizeRegular = 7;
            
            $border = 'B';
            $align_center = 'C';
            $align_left = 'L';
            $align_right = 'R';
            $height = '';
            $y = '';
            $x = ''; // 5
            $width = 60;
            PDF::setCellPaddings( $left = '', $top = '0.5', $right = '', $bottom = '0.5');
            
            PDF::SetFont('times', 'B', $fontSizeGrande);
            PDF::MultiCell($width, $height, $data['empresaComercial'], '', $align_center,  1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, $data['empresaRazonSocial'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, $data['empresaDireccionFiscal'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, $data['empresaRuc'], $border, $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::Cell($width, $height, "TELÉFONO: " . $data['emisorAgenciaTelefono'], 'T', 1, 'L', 0);
            PDF::MultiCell($width, $height, "TERMINAL: ".$data['emisorAgenciaDireccion'], $border, $align_left, 1, 0, $x, $y);
            PDF::Ln();
            // -------------
            
            PDF::SetFont('times', 'B', $fontSizeGigante);
            PDF::Cell($width, $height, $data['emisorTipoDocumentoElectronico'], 'T', 1, 'C', 0);
            PDF::MultiCell($width, $height, $data['emisorNumeroDocumentoElectronico'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, "OPERADOR: ".$data['clienteRazonSocial'], '', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            PDF::Cell($width/2, $height, "FECHA: " . $data['emisorFechaDocumentoElectronico'], 'B', 0, 'L', 0);
            PDF::Cell($width/2, $height, "HORA: 00:00:00" . $data['emisorHoraDocumentoElectronico'], 'B', 1, 'R', 0);
            // -------------
            
            $dniruc = (strlen($data['clienteDocumento']) === 8) ? 'DNI/CE' : 'RUC';
            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, "CLIENTE: ".$data['clienteRazonSocial'], '', $align_left, 1, 0, $x, $y);
            PDF::Ln();

            PDF::MultiCell($width, $height, "$dniruc: " . $data['clienteDocumento'], '', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            
            PDF::MultiCell($width, $height, "CONSIGNA:", '', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            // -------------

            PDF::SetFont('times', 'B', $fontSizeGrande);
            foreach($data['consigna'] as $nombre) {
                PDF::MultiCell($width, $height, $nombre, '', $align_left, 1, 0, $x, $y);
                PDF::Ln();
            }

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell(20, $height, "DESTINO:", '', $align_left, 1, 0, $x, $y);
            PDF::SetFont('times', 'B', $fontSizeGigante);
            PDF::MultiCell(40, $height, $data['destino'], $border, $align_right, 1, 0, $x, $y);
            PDF::Ln();
            PDF::Cell($width, $height, "", 'T', 1, 'L', 0, '', 10);
            
            PDF::SetFont('times', 'B', $fontSizeRegular);
            PDF::Cell(24, $height, "DESCRIPCIÓN", '', 0, 'L', 1);
            PDF::Cell(14, $height, "CANTIDAD", '', 0, 'L', 1);
            PDF::Cell(12, $height, "PRECIO", '', 0, 'R', 1);
            PDF::Cell(10, $height, "TOTAL", '', 0, 'R', 1);
            PDF::Ln();
            $importeTotal = 0.00;
            PDF::SetFont('times', '', $fontSizeRegular);
            foreach($data['encargoDetalle'] as $encargo){
                $importeTotal += $encargo['total'];
                PDF::MultiCell(24, $height, $encargo['descripcion'], '', $align_left, 1, 0, $x, $y);
                PDF::MultiCell(14, $height, $encargo['cantidad'], '', $align_center, 1, 0, $x, $y);
                PDF::MultiCell(12, $height, $encargo['precio'], '', $align_center, 1, 0, $x, $y);
                PDF::MultiCell(10, $height, $encargo['total'], '', $align_center, 1, 0, $x, $y);
                PDF::Ln();
            }
            
            $importePagar = number_format($data['importePagar'], 2, '.', '');
            $igv = number_format($importePagar * env('IGV', 0.18), 2, '.', '');
            $subtotal = number_format($importePagar - $igv, 2, '.', '');
            $descuentos = number_format($importePagar - $importeTotal, 2, '.', '');
         
            PDF::Cell(35, $height, "DESCUENTOS", 'T', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", 'T', 0, 'C', 1);
            PDF::Cell(20, $height, $descuentos, 'T', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell(35, $height, "SUBTOTAL", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(20, $height, $subtotal, '', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell(35, $height, "OP.GRAVADA", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(20, $height, $subtotal, '', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell(35, $height, "OP.EXONERADA", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(20, $height, "0", '', 0, 'R', 1);
            PDF::Ln();
            
            PDF::Cell(35, $height, "OP.GRATUITA", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(20, $height, "0", '', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell(35, $height, "IGV 18%", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(20, $height, $igv, '', 0, 'R', 1);
            PDF::Ln();
            
            PDF::SetFont('times', 'B', $fontSizeGrande);
            PDF::Cell(35, $height, "IMPORTE TOTAL", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::SetFont('times', 'B', $fontSizeGigante);
            PDF::Cell(20, $height, $importePagar, '', 0, 'R', 1);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeGrande);
            PDF::Cell($width, $height, "hash SUNAT:", 'T', 1, 'L', 1);
            PDF::Cell($width, $height, "1c7a92ae351d4e21ebdfb897508f59d6", '', 1, 'L', 1);
            PDF::Image(base_path('public/assets/media/logos/logo.jpeg'), '30', '', 20, 20, '', '', '', false, 300, '', false, false, 1, false, false, false);
            PDF::Ln(20);
            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, "Representación impresa de ".$data['emisorTipoDocumentoElectronico'].". Puede descargarlo y/o consultarlo desde www.enlacesbus.com.pe/see", $border, $align_center, 1, 0, $x, $y);
            PDF::Ln();
            PDF::MultiCell($width, $height, env('EMPRESA_DISCLAIMER',''), '', $align_left, 1, 0, $x, $y);
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
            PDF::Image(base_path('public/assets/media/logos/logo.jpeg'), '', '', 20, 20, '', '', '', false, 300, '', false, false, 1, false, false, false);
            PDF::Ln();
            PDF::MultiCell($width, $height, env('EMPRESA_DISCLAIMER',''), $border, $align_left, 1, 0, $x, $y);
            $year = substr($data['emisorFechaDocumentoElectronico'], -4);
            $filename = "pruebas/" . $year . "/" . $encargoId . ".pdf";
            if (file_exists(base_path($filename))) { unlink(base_path($filename)); }
            PDF::Output(public_path($filename), 'F');
            PDF::reset();
        }
    }
}
