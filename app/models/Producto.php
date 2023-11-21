<?php

class Producto
{
    public $id;
    public $nombre;
    public $sectorAsignado;
    public $precio;

    public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (nombre, sectorAsignado,precio) VALUES (:nombre, :sectorAsignado, :precio)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':sectorAsignado',  $this->sectorAsignado, PDO::PARAM_STR);
        $consulta->bindValue(':precio',$this->precio,PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, sectorAsignado, precio FROM productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function ObtenerProductoPorNombre($nombre)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos WHERE nombre = :nombre");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject("producto");
    }

    private function obtenerProductoPorId($productoId)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, precio,  FROM productos WHERE id = :id");
        $consulta->bindValue(':id', $productoId, PDO::PARAM_INT);
        $consulta->execute();

        $producto = $consulta->fetch(PDO::FETCH_OBJ);

        return $producto;
    }
    
}
