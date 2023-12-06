<?php
require_once './models/Encuesta.php';
class EncuestaController extends Encuesta
{
    public function CargarUno($request, $response, $args)
    {
        $encuesta = new Encuesta();

        $parsedBody = $request->getParsedBody();

        $encuesta->codigoPedido = $parsedBody['codigoPedido'] ?? null;
        $encuesta->codigoMesa = $parsedBody['codigoMesa'] ?? null;
        $encuesta->mesa = $parsedBody['puntuacionMesa'] ?? null;
        $encuesta->restaurante = $parsedBody['puntuacionRestaurante'] ?? null;
        $encuesta->mozo = $parsedBody['puntuacionMozo'] ?? null;
        $encuesta->cocinero = $parsedBody['puntuacionCocinero'] ?? null;
        $encuesta->texto = $parsedBody['texto'] ?? null;

        if (
            $encuesta->codigoPedido !== null &&
            $encuesta->codigoMesa !== null &&
            $encuesta->mesa !== null &&
            $encuesta->restaurante !== null &&
            $encuesta->mozo !== null &&
            $encuesta->cocinero !== null &&
            $encuesta->texto !== null
        ) {
            $encuesta->CrearEncuesta();
            $msj = "Encuesta creada con éxito.";
        } else {
            $msj = "No se pudo crear la encuesta. Faltan datos en la solicitud.";
        }

        $payload = json_encode(["mensaje" => $msj]);
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

    public static function TraerTotalEncuestas($request, $response, $args)
    {
        $totalEncuestas = Encuesta::TraerTodasLasEncuestas();
        $payload = json_encode(array("total" => $totalEncuestas));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerEstadisticasEncuestas($request, $response, $args)
    {
        $totalEncuestas = Encuesta::TraerTodasLasEncuestas();
        $encuestasPositivas = Encuesta::TraerEncuestasPositivas();
        $encuestasNegativas = Encuesta::TraerEncuestasNegativas();

        $payload = json_encode(array(
            "total" => $totalEncuestas,
            "positivas" => count($encuestasPositivas),
            "negativas" => count($encuestasNegativas)
        ));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
