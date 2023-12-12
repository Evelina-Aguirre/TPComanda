<?php
class Estadisticas
{


    public function traerOperacionesPorSector($sector)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("
            SELECT nombre, metodo, ruta, fecha
            FROM registroacciones
            WHERE sector = :sector
        ");
        $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerCantidadOperacionesPorEmpleadoPorSector()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("
            SELECT sector, nombre, COUNT(*) as cantidad
            FROM registroacciones
            GROUP BY sector, nombre
        ");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function OperacionesPorEmpleadoPorSector()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("
        SELECT sector, nombre, GROUP_CONCAT(CONCAT(metodo, ' ', ruta) SEPARATOR ', ') as acciones
        FROM registroacciones
        GROUP BY sector, nombre, id;
        ");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function ObtenerProductosMasVendidos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("
            SELECT idProducto, nombre, COUNT(idProducto) as cantidadVentas
            FROM listaproductosporpedido
            WHERE estado = 'pendiente'
            GROUP BY idProducto, nombre
            ORDER BY cantidadVentas DESC
        ");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerIngresosSistema()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("
            SELECT idUsuario, nombre, sector, fechaHoraLogueo
            FROM usuarioslogueo
        ");

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerMesasOrdenadasPorFactura()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("
            SELECT idMesa, codigoPedido, precioTotal
            FROM pedidos
            ORDER BY precioTotal DESC
        ");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function FacturacionMesaEntreFechas($idMesa, $fechaInicio, $fechaFin)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("
        SELECT SUM(precioTotal) as totalFacturado
        FROM pedidos
        WHERE idMesa = :idMesa
        AND DATE(horaCreacion) BETWEEN :fechaInicio AND :fechaFin
        ");
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':fechaInicio', $fechaInicio, PDO::PARAM_STR);
        $consulta->bindValue(':fechaFin', $fechaFin, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }
}
