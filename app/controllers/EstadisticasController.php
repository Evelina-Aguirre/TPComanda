<?php
require_once './models/Estadisticas.php';


class EstadisticasController extends Estadisticas
{
    public function TraerTotalOperaciones($request, $response, $args)
    {
        $sectores = ["Mozo", "Cliente", "Cervecero", "Bartender", "Pastelero", "Cervecero", "Socio", "Admin"];
        $resultadosPorSector = [];

        foreach ($sectores as $sector) {
            $operacionesPorSector = $this->traerOperacionesPorSector($sector);
            $resultadosPorSector["Acciones $sector"] = $operacionesPorSector;
        }

        $payload = json_encode(array("operacionesPorSectores" => $resultadosPorSector));

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function traerCantidadTotalOperaciones($request, $response, $args)
    {
        $cantidadOperacionesPorSector = $this->traerCantidadOperacionesPorSector();

        $payload = json_encode(array("cantidadOperacionesPorSector" => $cantidadOperacionesPorSector));

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function traerCantidadOperacionesPorSector()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("
            SELECT sector, COUNT(*) as cantidad
            FROM registroacciones
            GROUP BY sector
        ");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function TraerOperacionesPorEmpleadoPorSector($request, $response, $args)
    {
        $OperacionesPorEmpleadoPorSector = $this->OperacionesPorEmpleadoPorSector();

        $payload = json_encode(array("OperacionesPorEmpleadoPorSector" => $OperacionesPorEmpleadoPorSector));

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerMasVendidoAMenosVendido($request, $response, $args)
    {
        $productosMasVendidos = Estadisticas::ObtenerProductosMasVendidos();

        $payload = json_encode(array("productosMasVendidos" => $productosMasVendidos));

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraercobroMasCaroMasBarato($request, $response, $args)
    {
        $mesasOrdenadasPorFactura = Estadisticas::ObtenerMesasOrdenadasPorFactura();

        $payload = json_encode(array("mesasOrdenadasPorFactura" => $mesasOrdenadasPorFactura));

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerfacturoEntreDosFechas($request, $response)
    {
        $params = $request->getParsedBody();

        $idMesa = $params['idMesa'];
        $fechaInicio = $params['fechaInicio'];
        $fechaFin = $params['fechaFin'];

        $fechaInicio = date('Y-m-d', strtotime($fechaInicio));
        $fechaFin = date('Y-m-d', strtotime($fechaFin));

        $facturacion = Estadisticas::FacturacionMesaEntreFechas($idMesa, $fechaInicio, $fechaFin);

        $payload = json_encode(["facturacion" => $facturacion]);
        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerlogIngresosSistema($request, $response, $args)
    {
        $ingresos = Estadisticas::obtenerIngresosSistema();

        $payload = json_encode(["ingresos" => $ingresos]);
        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}
