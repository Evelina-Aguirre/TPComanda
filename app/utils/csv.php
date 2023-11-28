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
}
?>