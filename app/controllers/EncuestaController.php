<?php
require_once './models/Encuesta.php';
class EncuestaController extends Encuesta 
    {
        public function CargarUno($request, $response, $args)
        {

            $encuesta = new Encuesta();
            $encuesta->codigoPedido= $request->getAttribute('codigoPedido');
            $encuesta->codigoMesa= $request->getAttribute('codigoMesa');
            $encuesta->mesa = $request->getAttribute('puntuacionMesa');
            $encuesta->restaurante = $request->getAttribute('puntuacionRestaurante');
            $encuesta->mozo= $request->getAttribute('puntuacionMozo');
            $encuesta->cocinero= $request->getAttribute('puntuacionCocinero');
            $encuesta->texto= $request->getAttribute('texto');
            $encuesta->CrearEncuesta();

            $msj = $encuesta->CrearEncuesta() ? "Encuesta creada con exito." : "No se pudo crear la encuesta. ";

            $payload = json_encode(array("mensaje" => $msj));
            $response->getBody()->write($payload);

            return $response;
        }

        public function TraerEncuestas($request, $response, $args)
        {
            $encuestas = Encuesta::TraerEncuestasConPromedio(7);
            $payload = json_encode($encuestas);
            $response->getBody()->write($payload);
    
            return $response->withHeader('Content-Type', 'application/json');
        }
        

    }