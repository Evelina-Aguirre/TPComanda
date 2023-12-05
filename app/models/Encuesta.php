<?php
 class Encuesta
 {
     public $id;
     public $codigoPedido;
     public $codigoMesa;
     public $mesa;
     public $restaurante;
     public $mozo;
     public $cocinero;
     public $texto;

     public function CrearEncuesta()
     {
         $objetoAccesoDato = AccesoDatos::obtenerInstancia();
         $consulta = $objetoAccesoDato->prepararConsulta("INSERT INTO encuestas (codigoPedido,codigoMesa,mesa,restaurante,mozo,cocinero,texto)
          VALUES(:codigoPedido,:codigoMesa,:mesa,:restaurante,:mozo,:cocinero,:texto);");
         $consulta->bindValue(":codigoPedido",$this->codigoPedido,PDO::PARAM_STR);
         $consulta->bindValue(":codigoMesa",$this->codigoMesa,PDO::PARAM_STR);
         $consulta->bindValue(":mesa",$this->mesa,PDO::PARAM_INT);
         $consulta->bindValue(":restaurante",$this->restaurante,PDO::PARAM_INT);
         $consulta->bindValue(":mozo",$this->mozo,PDO::PARAM_INT);
         $consulta->bindValue(":cocinero",$this->cocinero,PDO::PARAM_INT);
         $consulta->bindValue(":texto",$this->texto,PDO::PARAM_STR);
         $consulta->execute();
         return $objetoAccesoDato->obtenerUltimoIdInsertado();
     }

     public static function TraerUnaEncuesta($codigoPedido)
     {
         $objetoAccesoDato = AccesoDatos::obtenerInstancia();
         $consulta = $objetoAccesoDato->prepararConsulta("SELECT id,mesa,restaurante,mozo,cocinero,texto,codigoPedido,codigoMesa
          FROM encuestas WHERE codigoPedido = '$codigoPedido'");
         $consulta->execute();
         return $consulta->fetchObject("Encuesta");
     }

     public static function TraerEncuestasConPromedio($minPromedio)
     {
         $objetoAccesoDato = AccesoDatos::obtenerInstancia();
 
         $consulta = $objetoAccesoDato->prepararConsulta("
         SELECT codigoPedido, codigoMesa, id, mesa, restaurante, mozo, cocinero, texto FROM encuestas");
 
         $consulta->execute();
         $encuestas = $consulta->fetchAll(PDO::FETCH_CLASS, "Encuesta");
 
         $encuestasFiltradas = array_filter($encuestas, function ($encuesta) use ($minPromedio) {
             $promedio = ($encuesta->restaurante + $encuesta->mozo + $encuesta->cocinero) / 3;
             return $promedio > $minPromedio;
         });
 
         return $encuestasFiltradas;
     }

 }
