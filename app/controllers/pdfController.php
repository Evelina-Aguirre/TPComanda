<?php

use Psr\Http\Message\ResponseInterface as response;
use Psr\Http\Message\ServerRequestInterface as request;
use Slim\Routing\RouteContext;
use Dompdf\Dompdf;

require_once '../vendor/autoload.php';


class pdfController
{

    public function descargarPDF($request, $response, $args)
    {

        $encuestas = Encuesta::TraerEncuestasConPromedio(7);

        $html = '<html lang="en">
                   <head>
                       <meta charset="UTF-8">
                       <meta name="viewport" content="width=device-width, initial-scale=1.0">
                       <title>Mesas</title>
                   </head>
                   <body>
                       <style>
                       </style>
                       <h1>Encuestas por Mesa</h1>';


        foreach ($encuestas as $encuesta) {
            $html .= '<p>ID: ' . $encuesta->id . ', CodigoPedido: ' . $encuesta->codigoPedido .
                ', CódigoMesa: ' . $encuesta->codigoMesa . ', Mesa: ' . $encuesta->mesa .
                ', restaurante: ' . $encuesta->restaurante . ', mozo: ' . $encuesta->mozo .
                ', cocinero: ' . $encuesta->cocinero . ', texto: ' . $encuesta->texto . '</p>';
            }
      
        $html .= '</body></html>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();


        $dompdf->stream("encuestas.pdf", array('Attachment' => 0));

    }
}
