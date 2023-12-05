<?php
class Pedido
{
    public $id;
    public $listaProductos;
    public $idMesa;
    public $estado;
    public $codigoPedido;
    public $fotoMesa;
    public $tiempoEstimado;
    public $horaCreacion;
    public $horaFinalizacion;
    public $idProducto;
    public $precioTotal;

    public function setFotoMesa($ruta)
    {
        $this->fotoMesa = $ruta;
    }

    public function AltaPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $horaCreacionFormatted = $this->horaCreacion ? date_format($this->horaCreacion, 'h:i:sa') : '00:00:00';

        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (idMesa, estado, codigoPedido,listaProductos, fotoMesa, tiempoEstimado, horaCreacion,
         horaFinalizacion) VALUES (:idMesa, :estado, :codigoPedido,:listaProductos, :fotoMesa, :tiempoEstimado, :horaCreacion, :horaFinalizacion)");
        $productosArray = explode(',', $this->listaProductos);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':codigoPedido', $this->codigoPedido, PDO::PARAM_INT);
        $consulta->bindValue(':listaProductos', $this->listaProductos, PDO::PARAM_STR);
        $consulta->bindValue(':fotoMesa', $this->fotoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimado', '00:00:00', PDO::PARAM_STR);
        $consulta->bindValue(':horaCreacion', $horaCreacionFormatted);
        $consulta->bindValue(':horaFinalizacion', $this->horaFinalizacion); //date_format($this->horaFinalizacion, 'h:i:sa'));
        $consulta->execute();
        $this->id = $objAccesoDatos->obtenerUltimoIdInsertado();
        foreach ($productosArray as $idProducto) {
            $productoDetalles = Producto::obtenerProductoPorId($idProducto);

            if ($productoDetalles !== null) {
                $this->agregarProductoAPedido($productoDetalles);
            }
        }
    }

    public function agregarProductoAPedido($productoDetalles)
    {
        if ($productoDetalles !== null) {
            $idProducto = $productoDetalles->id;
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO listaproductosporpedido (idPedido, idProducto, nombre,sector, precio)
             VALUES (:idPedido, :idProducto, :nombre,:sector, :precio)");
            $consulta->bindValue(':idPedido', $this->id, PDO::PARAM_INT);
            $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
            $consulta->bindValue(':nombre', $productoDetalles->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':sector', $productoDetalles->sectorAsignado, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $productoDetalles->precio, PDO::PARAM_STR);
            $consulta->execute();
        }
    }

    public function listarProductos()
    {
        $listaProductos = array();
        $precioTotal = 0;

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("
        SELECT  idProducto,nombre,precio,tiempoEstimado,empleadoACargo,estado
        FROM listaproductosporpedido 
        WHERE idPedido = :idPedido
    ");
        $consulta->bindValue(':idPedido', $this->id, PDO::PARAM_INT);
        $consulta->execute();

        while ($producto = $consulta->fetch(PDO::FETCH_OBJ)) {
            $listaProductos[] = $producto;
            $precioTotal += $producto->precio;
        }
        $this->precioTotal = $precioTotal;
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("
        UPDATE pedidos SET precioTotal = :precio
        WHERE id = :idPedido
    ");
        $consulta->bindValue(':idPedido', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':precio',  $this->precioTotal, PDO::PARAM_STR);
        $consulta->execute();

        return $listaProductos;
    }



    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta( "SELECT *  FROM pedidos ");
        $consulta->execute();

        $pedidos = $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');

        foreach ($pedidos as $pedido) {
           
            $pedido->idProducto = $pedido->listarProductos();
        }

        return $pedidos;
    }
    public static function listarPedidoPorId($idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("
        SELECT id, idMesa, estado, codigoPedido, fotoMesa, tiempoEstimado, horaCreacion, horaFinalizacion
        FROM pedidos
        WHERE id = :idPedido");

        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->execute();

        $pedido = $consulta->fetch(PDO::FETCH_CLASS, 'Pedido');

        if ($pedido) {
            $pedido->idProductos = $pedido->listarProductos();
        }

        return $pedido;
    }


    public function modificarPedido($id, $tiempoEstimado, $empleadoACargo, $estado = null)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("
        SELECT COUNT(*) as existe
        FROM listaproductosporpedido
        WHERE id = :id
    ");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        $existeProducto = $consulta->fetch(PDO::FETCH_ASSOC)['existe'];

        if (!$existeProducto) {
            throw new Exception("El producto con id $id no existe.");
        }

        $updateStatement = "UPDATE listaproductosporpedido SET ";
        $updateData = array();

        if ($tiempoEstimado !== null) {
            $updateStatement .= "tiempoEstimado = :tiempoEstimado, ";
            $updateData[':tiempoEstimado'] = $tiempoEstimado;
        }

        if ($empleadoACargo !== null) {
            $updateStatement .= "empleadoACargo = :empleadoACargo, ";
            $updateData[':empleadoACargo'] = $empleadoACargo;
        }

        if ($estado !== null) {
            $updateStatement .= "estado = :estado, ";
            $updateData[':estado'] = $estado;
        }


        $updateStatement = rtrim($updateStatement, ', ');

        $updateStatement .= " WHERE id = :id";

        $consulta = $objAccesoDatos->prepararConsulta($updateStatement);

        foreach ($updateData as $key => $value) {
            $consulta->bindValue($key, $value);
        }

        $consulta->bindValue(':id', $id, PDO::PARAM_INT);

        $consulta->execute();

        $this->recalcularTiempoEstimado($id);
    }

    public static function listarPedidosPorRol($roll)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM listaproductosporpedido WHERE empleadoACargo = :roll"
        );
        $consulta->bindValue(':roll', $roll, PDO::PARAM_STR);
        $consulta->execute();

        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);


        /*  if (count($resultados) > 0) {
            $nombreEmpleado = $resultados[0]['empleadoACargo'];
    
            $consultaRol = $objAccesoDatos->prepararConsulta(
                "SELECT roll FROM usuarios WHERE usuario = :nombreEmpleado"
            );
            $consultaRol->bindValue(':nombreEmpleado', $nombreEmpleado, PDO::PARAM_STR);
            $consultaRol->execute();
    
            $rolEmpleado = $consultaRol->fetch(PDO::FETCH_ASSOC)['roll'];
    
            foreach ($resultados as &$pedido) {
                $pedido['rolEmpleado'] = $rolEmpleado;
            }
        }*/

        return $resultados;
    }

    private function recalcularTiempoEstimado($idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("
        SELECT MAX(tiempoEstimado) as maxTiempoEstimado
        FROM listaproductosporpedido
        WHERE idPedido = :idPedido
    ");

        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->execute();

        $maxTiempoEstimado = $consulta->fetch(PDO::FETCH_ASSOC)['maxTiempoEstimado'];

        $consulta = $objAccesoDatos->prepararConsulta("
        UPDATE pedidos
        SET tiempoEstimado = :maxTiempoEstimado
        WHERE id = :idPedido
    ");

        $consulta->bindValue(':maxTiempoEstimado', $maxTiempoEstimado, PDO::PARAM_INT);
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);

        $consulta->execute();
    }

    public function actualizarHoraFinalización($idPedido, $horaFinalizacion = null)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        if ($horaFinalizacion === null) {
            $horaActual = new DateTime();
            $horaFinalizacion = $horaActual->format('h:i:sa');
        }

        $consulta = $objAccesoDatos->prepararConsulta("
        UPDATE pedidos
        SET estado = 'listo para servir', horaFinalizacion = :horaFinalizacion
        WHERE id = :idPedido
    ");

        $consulta->bindValue(':horaFinalizacion', $horaFinalizacion);
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);

        $consulta->execute();
    }
}
