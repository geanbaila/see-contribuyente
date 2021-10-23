<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Business\Sede;
use App\Business\Documento;
use App\Business\Encargo;
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

            'origen' => $data['origen'],
            'destino' => $data['destino'],
            'agencia_origen' => $data['agenciaOrigen'],
            'agencia_destino' => $data['agenciaDestino'],
            'medio_pago' => $data['medioPago'],
            'documento' => $data['documento'],
            'documento_serie' => $data['documentoSerie'],
            'documento_numero' => $data['documentoNumero'],

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
        $this->writeBill($encargoId);
        return \response()->json([ 'status' => 'OK', 'result' => ['encargoId' => $encargoId] ]);
    }

    public function show() {
        $sede = Sede::all();
        $documento = Documento::all();
        return view('sale.show')->with([ 'sede' => $sede, 'documento' => $documento ]);
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
        $encargo = Encargo::find($encargoId);
        if ($encargo) {
            $empresa = "M&C ENLACES S.A";
            $direccion = "AV JAVIER PRADO ESTE NRO Sit sunt enim sunt laborum adipisicing esse officia consequat.";
            $ruc = "RUC: 20193185060";
            $agencia = "T.T. Atocongo";
            $telefono = "Teléfonos: 999 999 999";
            $transaccion = "BOLETA DE VENTA ELECTRÓNICA B007 -0005";
            $fechaTransaccion = "08/10/2021"; 
            $clienteRazonSocial = "Razon Social:\nCALAVERA DE MIRANDA FELICITAS";
            $clienteDireccion = "Dirección:\nALGUNA DIRECCION";
            $clienteDoc = "RUC/DNI: 47134373";
            $clienteConsigna = "GEAN\nVAL JEAN";
            

            PDF::SetTitle('Hello World');
            PDF::setPrintHeader(false);
            PDF::setPrintFooter(false);
            PDF::SetFont('times', 'B', 10);
            PDF::AddPage();
            $width = 50;
            
            // Color and font restoration
            PDF::SetFillColor(255, 255, 255);
            PDF::SetTextColor(0);
            
            $border = false;
            $align_center = 'C';
            $align_left = 'L';
            $height = '';
            $y = '';
            $x = ''; // 5
            $width = 60;

            PDF::SetFont('', 'B');
            PDF::MultiCell($width, $height, $empresa, $border, $align_center, 'LR', 'LR', $x, $y, true);
            PDF::Ln();
            PDF::SetFont('');
            PDF::MultiCell($width, $height, $agencia, $border, $align_center, 1, 0, $x, $y);
            PDF::Ln();
            PDF::SetFont('', 'B');
            PDF::Cell($width, $height, $ruc, 'B', 1, 'C', 0);

            PDF::SetFont('');
            PDF::MultiCell($width, $height, "Terminal:", $border, $align_left, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('', 'B');
            PDF::MultiCell($width, $height, $agencia, $border, $align_left, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('');
            PDF::Cell($width, $height, $telefono, 'B', 1, 'L', 0);

            PDF::SetFont('', 'B');
            PDF::MultiCell($width, $height, $transaccion, $border, $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('');
            PDF::Cell($width, $height, $fechaTransaccion, 'B', 1, 'L', 0);

            PDF::MultiCell($width, $height, $clienteRazonSocial, $border, $align_left, 1, 0, $x, $y);
            PDF::Ln();

            PDF::MultiCell($width, $height, $clienteDireccion, $border, $align_left, 1, 0, $x, $y);
            PDF::Ln();
            PDF::Cell($width, $height, $clienteDoc, 'B', 1, 'L', 0);

            PDF::MultiCell($width, $height, "Consigna:", $border, $align_left, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('', 'B');
            PDF::MultiCell($width, $height, $clienteConsigna, $border, $align_left, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('');
            PDF::MultiCell($width/3, $height, "Destino:", $border, $align_left, 1, 0, $x, $y);
            PDF::SetFont('', 'B');
            PDF::MultiCell($width/2, $height, "AREQUIPA", $border, $align_left, 1, 0, 35, $y);
            PDF::Ln();
            PDF::Cell($width, $height, "", 'T', 1, 'L', 0, '', 10);
            


            PDF::Output(public_path('hello_world.pdf'), 'F');
            PDF::reset();
        }
    }
}
