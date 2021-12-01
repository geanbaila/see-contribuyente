<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use MongoDB\BSON\ObjectId;

class MailerController extends Controller
{
    public function enviarComprobante(Request $request) {
        require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true);     // Passing `true` enables exceptions
        $encargo_id = $request->encargo_id;
        $encargo = \App\Business\Encargo::find(new ObjectId($encargo_id));
        
        $body = env('MAIL_BODY');
        $body = str_replace('_RAZON_SOCIAL_',$encargo->adquirientes->razon_social, $body);
        $body = str_replace('_RUC_DNI_', $encargo->adquirientes->documento, $body);
        $body = str_replace('_NUMERO_COMPROBANTE_PAGO_', $encargo->documento_serie . '-' . $encargo->documento_correlativo, $body);
        $body = str_replace('_COMPROBANTE_PAGO_', $encargo->documentos->nombre, $body);
        $body = str_replace('_FECHA_EMISION_', $encargo->fecha_hora_envia, $body);
        $body = str_replace('_VALOR_VENTA_', $encargo->importe_pagar_con_igv, $body);
        $body = str_replace('_HASH_SUNAT_', $encargo->hash_sunat, $body);
        
        try {
            $mail->CharSet = 'UTF-8';
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = env('MAIL_ENCRYPTION'); // encryption - ssl/tls
            $mail->Port = env('MAIL_PORT'); // port - 587/465
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');

            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress($request->email_adquiriente);
            $mail->addCC(env('MAIL_CC'));
            $mail->addBCC(env('MAIL_CCO'));

            $mail->addReplyTo(env('MAIL_REPLY_ADDRESS'), env('MAIL_REPLY_NAME'));

            if (isset($encargo['url_documento_xml'])) {
                $mail->addAttachment(storage_path('app/' . $encargo['url_documento_xml']), $encargo['nombre_archivo'].'.xml');
            }

            if (isset($encargo['url_documento_pdf'])) {
                $mail->addAttachment(storage_path('app/' . $encargo['url_documento_pdf']), $encargo['nombre_archivo'].'.pdf');
            }

            $mail->Body    = $body;
            $mail->Subject = env('MAIL_SUBJECT');
            $mail->isHTML(true);
            // $mail->AltBody = plain text version of email body;

            if( !$mail->send() ) {
                $response = [
                    'result' =>[
                        'status' => 'fails',
                        'message' => 'No se pudo enviar el e-mail. '.$mail->ErrorInfo
                    ]
                ];
            }else {
                $response = [
                    'result' =>[
                        'status' => 'OK',
                        'message' => 'E-mail enviado.'
                    ]
                ];
            }
            return response()->json($response);

        } catch (Exception $e) {
            $response = [
                'result' =>[
                    'status' => 'fails',
                    'message' => 'No se pudo enviar el e-mail, ha ocurrido un error interno.' .  $e->getMessage()
                ]
            ];
            return response()-json($response);
        }
    }
}
