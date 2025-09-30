<?php

namespace App\Service;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Response;

class PdfService
{
    private $dompdf;
    private $pdfOptions;


    public function __construct()
    {
        $this->dompdf                   = new Dompdf();
        $this->pdfOptions                = new Options();
        $this->pdfOptions->set('debugKeepTemp', true); 
        $this->pdfOptions->set('ssl', [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ]);
    }

    /**
     * Génère un PDF à partir des données sur la page
     *
     * @param string $html Le contenu html qui sera converti en pdf
     * @param string $filename Le nom du fichier PDF.
     *
     * @return Response
    */
    public function generatePdf(string $html, string $filename)
    {
        $this->pdfOptions->set('defaultFont', 'Arial');
        $this->pdfOptions->setIsRemoteEnabled(true);
        $this->dompdf->setPaper('a4,', 'landscape');
        $this->dompdf->setOptions($this->pdfOptions);

        $context = stream_context_create([
            'ssl' => [
                'verify_peer'       => FALSE,
                'verify_peer_name'  => FALSE,
                'allow_self_signed' => TRUE,
            ]
        ]);

        $this->dompdf->setHttpContext($context);
        $this->dompdf->loadHtml($html);
        $this->dompdf->render();

        $response = new Response($this->dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}