<?php

require_once "./models/Mesa.php";
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
        $estadoMesa = Mesa::mensajeEstadoMesa($this->estado);
        Mesa::ActualizarEstado($this->idMesa, $estadoMesa);
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
        $consulta = $objAccesoDatos->prepararConsulta("SELECT *  FROM pedidos ");
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
        $consultaPedidoId = $objAccesoDatos->prepararConsulta("
            SELECT idPedido
            FROM listaproductosporpedido
            WHERE id = :id
        ");
        $consultaPedidoId->bindValue(':id', $id, PDO::PARAM_INT);
        $consultaPedidoId->execute();

        $idPedido = $consultaPedidoId->fetch(PDO::FETCH_ASSOC)['idPedido'];

        if (!$idPedido) {
            throw new Exception("El producto id $id no está en la lista de pendientes.");
        }

        $comandoUpdate = "UPDATE listaproductosporpedido SET ";
        $updateDato = array();

        if ($tiempoEstimado !== null) {
            $comandoUpdate .= "tiempoEstimado = :tiempoEstimado, ";
            $updateDato[':tiempoEstimado'] = $tiempoEstimado;
        }

        if ($empleadoACargo !== null) {
            $comandoUpdate .= "empleadoACargo = :empleadoACargo, ";
            $updateDato[':empleadoACargo'] = $empleadoACargo;
        }

        if ($estado !== null) {
            $comandoUpdate .= "estado = :estado, ";
            $updateDato[':estado'] = $estado;
        }

        $comandoUpdate = rtrim($comandoUpdate, ', ');

        $comandoUpdate .= " WHERE id = :id";

        $consulta = $objAccesoDatos->prepararConsulta($comandoUpdate);

        foreach ($updateDato as $key => $value) {
            $consulta->bindValue($key, $value);
        }

        $consulta->bindValue(':id', $id, PDO::PARAM_INT);

        $consulta->execute();

        $consultaMesaId = $objAccesoDatos->prepararConsulta("
        SELECT idMesa
        FROM pedidos
        WHERE id = :idPedido
    ");
        $consultaMesaId->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consultaMesaId->execute();

        $idMesa = $consultaMesaId->fetch(PDO::FETCH_ASSOC)['idMesa'];

        $this->recalcularTiempoEstimado($idPedido);
    }


    public static function listarPedidosPendientesPorRol($sector)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM listaproductosporpedido WHERE sector = :sector AND estado = 'pendiente' "
        );
        $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
        $consulta->execute();

        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

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

        if ($maxTiempoEstimado !== null) {

            $consulta = $objAccesoDatos->prepararConsulta("
                UPDATE pedidos
                SET tiempoEstimado = :maxTiempoEstimado
                WHERE id = :idPedido
            ");

            $consulta->bindValue(':maxTiempoEstimado', $maxTiempoEstimado, PDO::PARAM_INT);
            $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);

            $consulta->execute();
        }
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

    public static function consultarEstadoPedidoPorId($idPedido)
    {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("
            SELECT estado
            FROM listaproductosporpedido
            WHERE idPedido = :idPedido
        ");
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function verificarPedidosListos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("
            SELECT DISTINCT idPedido
            FROM listaproductosporpedido
        ");
        $consulta->execute();

        $pedidosListos = [];

        while ($idPedido = $consulta->fetchColumn()) {
            $estadosProductos = Pedido::consultarEstadoPedidoPorId($idPedido);

            if (is_array($estadosProductos)) {
                $productosListos = array_filter($estadosProductos, function ($producto) {
                    return $producto === 'listo para servir';
                });

                if (count($productosListos) === count($estadosProductos)) {
                    $pedidosListos[] = $idPedido;
                }
            } else {
                if ($estadosProductos === 'listo para servir') {
                    $pedidosListos[] = $idPedido;
                }
            }
        }

        return $pedidosListos;
    }

    public static function buscarIdMesadeunPedido($idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(" SELECT idMesa FROM pedidos WHERE id = :idPedido
    ");
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchColumn();
    }
}
