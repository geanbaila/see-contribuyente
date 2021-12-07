<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Greenter\Data\DocumentGeneratorInterface;
use \Greenter\Data\GeneratorFactory;
use \Greenter\Data\SharedStore;
use \Greenter\Model\DocumentInterface;
use \Greenter\Model\Response\CdrResponse;
use \Greenter\Model\Sale\SaleDetail;
use \Greenter\Report\HtmlReport;
use \Greenter\Report\PdfReport;
use \Greenter\Report\Resolver\DefaultTemplateResolver;
use \Greenter\Report\XmlUtils;
use \Greenter\See;
use \Greenter\Model\Company\Company;
use \Greenter\Model\Company\Address;
use Illuminate\Support\Facades\Storage;

class Util
{
    
    /**
     * @var Util
     */
    private static $current;
    /**
     * @var SharedStore
     */
    public $shared;

    private function __construct()
    {
        $this->shared = new SharedStore();
    }

    public static function getInstance(): Util
    {
        if (!self::$current instanceof self) {
            self::$current = new self();
        }

        return self::$current;
    }

    public function getSee(?string $endpoint)
    {
        $see = new See();
        $see->setService($endpoint);
//        $see->setCodeProvider(new XmlErrorCodeProvider());
        $certificate = file_get_contents(env('PEM'));
        if ($certificate === false) {
            throw new Exception('No se pudo cargar el certificado');
        }
        $see->setCertificate($certificate);
        $see->setClaveSOL(env('SOL_RUC'), env('SOL_USUARIO'), env('SOL_CLAVE'));
        $see->setCachePath(storage_path('cache'));
        
        return $see;
    }

    public function showResponse(DocumentInterface $document, CdrResponse $cdr): void
    {
        $filename = $document->getName();

        require __DIR__.'/../views/response.php';
    }

    public function getErrorResponse(\Greenter\Model\Response\Error $error): string
    {
        $result = <<<HTML
        <h2 class="text-danger">Error:</h2><br>
        <b>Código:</b>{$error->getCode()}<br>
        <b>Descripción:</b>{$error->getMessage()}<br>
HTML;

        return $result;
    }

    public function writeXml(?string $folder, DocumentInterface $document, ?string $xml): ?string
    {
        return $this->writeFile($folder, $document->getName().'.xml', $xml);
    }

    public function writeCdr(?string $folder, DocumentInterface $document, ?string $zip): ?string
    {
        return $this->writeFile($folder, 'R-'.$document->getName().'.zip', $zip);
    }

    public function writeFile(?string $folder, ?string $filename, ?string $fileContents): ?string
    {
        if(Storage::disk('local')->put($folder .'/'. $filename, $fileContents)) {
            return $folder .'/'. $filename;
        }
    }

    public function getPdf(DocumentInterface $document): ?string
    {
        $html = new HtmlReport('', [
            'cache' => __DIR__ . '/../cache',
            'strict_variables' => true,
        ]);
        $resolver = new DefaultTemplateResolver();
        $template = $resolver->getTemplate($document);
        $html->setTemplate($template);

        $render = new PdfReport($html);
        $render->setOptions( [
            'no-outline',
            'print-media-type',
            'viewport-size' => '1280x1024',
            'page-width' => '21cm',
            'page-height' => '29.7cm',
            'footer-html' => __DIR__.'/../resources/footer.html',
        ]);
        $binPath = self::getPathBin();
        if (file_exists($binPath)) {
            $render->setBinPath($binPath);
        }
        $hash = $this->getHash($document);
        $params = self::getParametersPdf();
        $params['system']['hash'] = $hash;
        $params['user']['footer'] = '<div>consulte en <a href="https://github.com/giansalex/sufel">sufel.com</a></div>';

        $pdf = $render->render($document, $params);

        if ($pdf === null) {
            $error = $render->getExporter()->getError();
            echo 'Error: '.$error;
            exit();
        }

        // Write html
        $this->writeFile($document->getName().'.html', $render->getHtml());

        return $pdf;
    }

    public function getGenerator(string $type): ?DocumentGeneratorInterface
    {
        $factory = new GeneratorFactory();
        $factory->shared = $this->shared;

        return $factory->create($type);
    }

    /**
     * @param SaleDetail $item
     * @param int $count
     * @return array<SaleDetail>
     */
    public function generator(SaleDetail $item, int $count): array
    {
        $items = [];

        for ($i = 0; $i < $count; $i++) {
            $items[] = $item;
        }

        return $items;
    }

    public function showPdf(?string $content, ?string $filename): void
    {
        $this->writeFile($filename, $content);
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($content));

        echo $content;
    }

    private function getHash(DocumentInterface $document): ?string
    {
        $see = $this->getSee('');
        $xml = $see->getXmlSigned($document);

        return (new XmlUtils())->getHashSign($xml);
    }

    public static function getCompany() {
        return (new Company())
            ->setRuc(env('EMPRESA_RUC'))
            ->setNombreComercial(env('EMPRESA_COMERCIAL'))
            ->setRazonSocial(env('EMPRESA_RAZON_SOCIAL'))
            ->setAddress((new Address())
                ->setUbigueo(env('EMPRESA_UBIGEO'))
                ->setDepartamento(env('EMPRESA_DEPARTAMENTO'))
                ->setProvincia(env('EMPRESA_PROVINCIA'))
                ->setDistrito(env('EMPRESA_DISTRITO'))
                ->setUrbanizacion(env('EMPRESA_URBANIZACION'))
                ->setCodLocal('0000')
                ->setDireccion(env('EMPRESA_DIRECCION')))
            ->setEmail(env('EMPRESA_EMAIL'))
            ->setTelephone(env('EMPRESA_TELEFONO'));
    }
 


}
