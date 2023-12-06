<?php
class CsvController
{
    public function cargarCsv($request,  $response, $args)
    {
        $destino = __DIR__ . "/uploads"; 
        $request = $request->withAttribute('destino', $destino);
        
        return Csv::CargarCsv($request, $response, $args);
    }

    public function leerCsv($request, $response, $args)
    {
        $destino = __DIR__ . "/uploads"; 
        $request = $request->withAttribute('destino', $destino);
        
        return Csv::LeerCsv($request, $response, $args);
    }

    public function descargarCsv( $request,  $response, $args)
    {
        $destino = __DIR__ . "/uploads"; 
        $request = $request->withAttribute('destino', $destino);
        
        return Csv::DescargarCsv($request, $response, $args);
    }
}