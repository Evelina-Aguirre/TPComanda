<?php

class Csv
{
    public static function CargarCsv($request, $response,$args)
    {

        $files = $request->getUploadedFiles();   

        $csv = $files['csv'];
        $destino = $request->getAttribute('destino');
        $csv->moveTo($destino);

        $payload = json_encode(array("mensaje" => 'Se cargo exitosamente'));
        $response->getBody()->write($payload);

        return $response;

    }

    public static function LeerCsv($request, $response,$args)
    {
        $destino = $request->getAttribute('destino');

        $entidad = array();

        if(file_exists($destino) &&
         ($archivo = fopen($destino,"r")) !== FALSE)
        {
            $primerIteracion = false; 
            while (($fila = fgetcsv($archivo,1000,',')) !== FALSE) 
            {
                if($primerIteracion)
                {
                    array_push($entidad,$fila);
                }
                $primerIteracion = true;
            }
        }

        fclose($archivo);

        $payload = json_encode(array("mensaje" => $entidad));
        $response->getBody()->write($payload);

        return $response;

    }

    public static function DescargarCsv($request,$response,$args)
    {
        $archivo_a_descargar = $request->getAttribute('destino');

    
        if (file_exists($archivo_a_descargar)) 
        {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($archivo_a_descargar) . '"');
            header('Content-Length: ' . filesize($archivo_a_descargar));


            readfile($archivo_a_descargar);
            $msj = "Descargado...";
        } 
        else 
        {
            $msj = 'El archivo no existe';
        }

        $payload = json_encode(array("mensaje" => $msj));
        $response->getBody()->write($payload);

        return $response;

    }

}

?>