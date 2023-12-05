<?php

class Csv
{
    public static function ExportarCSV($path, $lista)
    {
        $file = fopen($path, "w");
        foreach($lista as $item)
        {
            $separado= implode(",", (array)$item);  
            if($file)
            {
                fwrite($file, $separado.",\r\n"); 
            }                           
        }
        fclose($file);  
        return $path;     
    }

    public static function ImportarCSV($path)
    {
        $aux = fopen($path, "r");
        $array = [];
        if(isset($aux))
        {
            try
            {
                while(!feof($aux))
                {
                    $datos = fgets($aux);                        
                    if(!empty($datos))
                    {          
                        array_push($array, $datos);                                                
                    }
                }
            }
            catch(Exception $e)
            {
                echo "Error:";
                echo $e;
            }
            finally
            {
                fclose($aux);
                return $array;
            }
        }
    }

    public static function DescargarCsv($request,$response,$args)
    {
        $archivoAdescargar = $request->getAttribute('destino');

        if (file_exists($archivoAdescargar)) 
        {
            //encabezados para la descarga
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($archivoAdescargar) . '"');
            header('Content-Length: ' . filesize($archivoAdescargar));

            // Lee el archivo y lo envía al navegador
            readfile($archivoAdescargar);
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