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
    public $idProductos;
    public $precioTotal;

    public function setFotoMesa($ruta)
    {
        $this->fotoMesa = $ruta;
    }

    public function AltaPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $horaCreacionFormatted = $this->horaCreacion ? date_format($this->horaCreacion, 'h:i:sa') : '00:00:00';

        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (idMesa, estado, codigoPedido, fotoMesa, tiempoEstimado, horaCreacion,
         horaFinalizacion) VALUES (:idMesa, :estado, :codigoPedido, :fotoMesa, :tiempoEstimado, :horaCreacion, :horaFinalizacion)");
        $productosArray = explode(',', $this->listaProductos);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':codigoPedido', $this->codigoPedido, PDO::PARAM_INT);
        $consulta->bindValue(':fotoMesa', $this->fotoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimado', '00:00:00', PDO::PARAM_STR);
        $consulta->bindValue(':horaCreacion', $horaCreacionFormatted);
        $consulta->bindValue(':horaFinalizacion', $this->horaFinalizacion); //date_format($this->horaFinalizacion, 'h:i:sa'));

        $consulta->execute();
        $this->id = $objAccesoDatos->obtenerUltimoIdInsertado();
        foreach ($productosArray as $idProducto) {
            $this->agregarProductoAPedido($idProducto);
        }
    }

    public function agregarProductoAPedido($idProducto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO listaproductosporpedido (idPedido, idProducto) VALUES (:idPedido, :idProducto)");
        $consulta->bindValue(':idPedido', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
        $consulta->execute();
    }

    public function listarProductos()
    {
        $listaProductos = array();
        $precioTotal = 0;

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("
        SELECT productos.id, productos.nombre, productos.precio, lp.tiempoPreparacion
        FROM listaproductosporpedido lp
        JOIN productos ON lp.idProducto = productos.id
        WHERE lp.idPedido = :idPedido
    ");
        $consulta->bindValue(':idPedido', $this->id, PDO::PARAM_INT);
        $consulta->execute();

        while ($producto = $consulta->fetch(PDO::FETCH_OBJ)) {
            $listaProductos[] = $producto;
            $precioTotal += $producto->precio;
        }
        $this->precioTotal = $precioTotal;

        return $listaProductos;
    }


    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(" 
        SELECT p.id, p.idMesa, p.estado, p.codigoPedido, p.fotoMesa, p.tiempoEstimado, p.horaCreacion, p.horaFinalizacion,
           GROUP_CONCAT(lp.idProducto) as idProductos,
           SUM(p.precioTotal) as precioTotal
        FROM pedidos p
        LEFT JOIN listaproductosporpedido lp ON p.id = lp.idPedido
        GROUP BY p.id");
        $consulta->execute();

        $pedidos = $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');

        foreach ($pedidos as $pedido) {
            $pedido->idProductos = $pedido->listarProductos();
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

    public function modificarPedido($idPedido, $idProducto, $tiempoEstimado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("
            SELECT COUNT(*) as existe
            FROM listaproductosporpedido
            WHERE idPedido = :idPedido AND idProducto = :idProducto
        ");
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
        $consulta->execute();

        $existeProducto = $consulta->fetch(PDO::FETCH_ASSOC)['existe'];

        if (!$existeProducto) {
            throw new Exception("El producto con id $idProducto no pertenece al pedido con id $idPedido.");
        }

        $consulta = $objAccesoDatos->prepararConsulta("
            UPDATE listaproductosporpedido
            SET tiempoPreparacion = :tiempoEstimado
            WHERE idPedido = :idPedido AND idProducto = :idProducto
        ");

        $consulta->bindValue(':tiempoEstimado', $tiempoEstimado, PDO::PARAM_INT);
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);

        $consulta->execute();

        $this->recalcularTiempoEstimado($idPedido);
    }


    private function recalcularTiempoEstimado($idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("
        SELECT MAX(tiempoPreparacion) as maxTiempoPreparacion
        FROM listaproductosporpedido
        WHERE idPedido = :idPedido
    ");

        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->execute();

        $maxTiempoPreparacion = $consulta->fetch(PDO::FETCH_ASSOC)['maxTiempoPreparacion'];

        $consulta = $objAccesoDatos->prepararConsulta("
        UPDATE pedidos
        SET tiempoEstimado = :maxTiempoPreparacion
        WHERE id = :idPedido
    ");

        $consulta->bindValue(':maxTiempoPreparacion', $maxTiempoPreparacion, PDO::PARAM_INT);
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);

        $consulta->execute();
    }


    public function actualizarEstadoYHora($idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $horaActual = new DateTime();

        $consulta = $objAccesoDatos->prepararConsulta("
        SELECT tiempoEstimado
        FROM pedidos
        WHERE id = :idPedido
    ");

        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->execute();

        $tiempoEstimado = $consulta->fetch(PDO::FETCH_ASSOC)['tiempoEstimado'];

       // $tiempoEstimado = new DateTime($tiempoEstimado);

        if ($horaActual >= $tiempoEstimado) {
            $consulta = $objAccesoDatos->prepararConsulta("
            UPDATE pedidos
            SET estado = 'listo para servir', horaFinalizacion = :horaFinalizacion
            WHERE id = :idPedido
        ");

            $consulta->bindValue(':horaFinalizacion', $horaActual->format('h:i:sa'));
            $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);

            $consulta->execute();



            return true;
        }

        return false;
    }
}
