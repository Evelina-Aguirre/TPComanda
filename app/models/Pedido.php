<?php
class Pedido
{
    public $id;
    public $idListaProductos; 
    public $idMesa;
    public $estado;
    public $codigoPedido;
    public $fotoMesa;
    public $tiempoEstimado;
    public $horaCreacion;
    public $horaFinalizacion;

    public function setFotoMesa($ruta)
    {
        $this->fotoMesa = $ruta;
    }

    public function AltaPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $horaCreacionFormatted = $this->horaCreacion ? date_format($this->horaCreacion, 'h:i:sa') : '00:00:00';

        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (idListaProductos, idMesa, estado, codigoPedido, fotoMesa, tiempoEstimado, horaCreacion,
         horaFinalizacion) VALUES (:idListaProductos, :idMesa, :estado, :codigoPedido, :fotoMesa, :tiempoEstimado, :horaCreacion, :horaFinalizacion)");

        $consulta->bindValue(':idListaProductos', $this->idListaProductos, PDO::PARAM_INT);
        $productosArray = explode(',', $this->idListaProductos);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':codigoPedido', $this->codigoPedido, PDO::PARAM_INT);
        $consulta->bindValue(':fotoMesa', $this->fotoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimado', $this->tiempoEstimado, PDO::PARAM_INT);
        $consulta->bindValue(':horaCreacion', $horaCreacionFormatted);
        $consulta->bindValue(':horaFinalizacion', $this->horaFinalizacion);//date_format($this->horaFinalizacion, 'H:i:sa'));
        
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

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT productos.id, productos.nombre, productos.precio FROM PedidoProducto
            JOIN productos ON PedidoProducto.idProducto = productos.id
            WHERE PedidoProducto.idPedido = :idPedido");
        $consulta->bindValue(':idPedido', $this->id, PDO::PARAM_INT);
        $consulta->execute();

        while ($producto = $consulta->fetch(PDO::FETCH_OBJ)) {
            $listaProductos[] = $producto;
        }

        return $listaProductos;
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idListaProductos, idMesa, estado, codigoPedido, fotoMesa, tiempoEstimado, horaCreacion,
        horaFinalizacion FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
}

