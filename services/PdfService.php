<?php
namespace services;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    private $dompdf;

    public function __construct()
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $this->dompdf = new Dompdf($options);
    }

    public function generarPDF($html, $nombreArchivo = "documento.pdf", $descargar = true)
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();

        $resultado = $descargar
            ? $this->dompdf->stream($nombreArchivo, ["Attachment" => true])
            : $this->dompdf->output();

        return $resultado;
    }
}