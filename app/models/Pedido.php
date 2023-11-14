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

    public function AltaPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        //$horaCreacionFormatted = $this->horaCreacion ? date_format($this->horaCreacion, 'H:i:sa') : null;

        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (idListaProductos, idMesa, estado, codigoPedido, fotoMesa, tiempoEstimado, horaCreacion,
         horaFinalizacion) VALUES (:idListaProductos, :idMesa, :estado, :codigoPedido, :fotoMesa, :tiempoEstimado, :horaCreacion, :horaFinalizacion)");

        $consulta->bindValue(':idListaProductos', $this->idListaProductos, PDO::PARAM_INT);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado);
        $consulta->bindValue(':codigoPedido', $this->codigoPedido);
        $consulta->bindValue(':fotoMesa', $this->fotoMesa);
        $consulta->bindValue(':tiempoEstimado', $this->tiempoEstimado, PDO::PARAM_INT);
        $consulta->bindValue(':horaCreacion', $this->horaCreacion);//$horaCreacionFormatted);
        $consulta->bindValue(':horaFinalizacion', $this->horaFinalizacion);//date_format($this->horaFinalizacion, 'H:i:sa'));

        $consulta->execute();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idListaProductos, idMesa, estado, codigoPedido, fotoMesa, tiempoEstimado, horaCreacion,
        horaFinalizacion FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }
}

