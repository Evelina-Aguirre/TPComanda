<?php

use FPDF as GlobalFPDF;
use Psr\Http\Message\ResponseInterface as response;
use Psr\Http\Message\ServerRequestInterface as request;
use Fpdf\Fpdf;

require_once '../vendor/autoload.php';

class fpdfController
{
    public function descargarFPDF($request,  $response, array $args)
    {
        $encuestas = Encuesta::TraerEncuestasConPromedio(7);

        $pdf = new GlobalFPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(40, 10, 'Encuestas por Mesa');

        foreach ($encuestas as $encuesta) {
            $pdf->Ln(); 
        
            $pdf->Cell(0, 10, 'ID: ' . $encuesta->id . ', CodigoPedido: ' . $encuesta->codigoPedido .
                ', CódigoMesa: ' . $encuesta->codigoMesa . ', Mesa: ' . $encuesta->mesa .
                ', restaurante: ' . $encuesta->restaurante . ', mozo: ' . $encuesta->mozo .
                ', cocinero: ' . $encuesta->cocinero);
        
            $pdf->Ln(); 
        
            $pdf->Cell(0, 10, 'Opinión: ' . $encuesta->texto);
        }

        $pdf->Output("encuestas.pdf", "D");

        return $response;
    }


    public function mostrarPDF($request, $response, $args)
    {
        $encuestas = Encuesta::TraerEncuestasConPromedio(7);

        $pdf = new GlobalFPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(40, 10, 'Encuestas por Mesa');

        foreach ($encuestas as $encuesta) {
            $pdf->Ln(); 
        
            $pdf->Cell(0, 10, 'ID: ' . $encuesta->id . ', CodigoPedido: ' . $encuesta->codigoPedido .
                ', CódigoMesa: ' . $encuesta->codigoMesa . ', Mesa: ' . $encuesta->mesa .
                ', restaurante: ' . $encuesta->restaurante . ', mozo: ' . $encuesta->mozo .
                ', cocinero: ' . $encuesta->cocinero);
        
            $pdf->Ln(); 
        
            $pdf->Cell(0, 10, 'Opinión: ' . $encuesta->texto);
        }

        $pdf->Output("encuestas.pdf", "I");

        return $response;
    }


}






?>