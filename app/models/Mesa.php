<?php

class Mesa
{
    public $id;
    public $listaPedidos;
    public $estado;
    public $puntaje;

    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (listaPedidos, estado,puntaje) VALUES (:listaPedidos, :estado,:puntaje)");
        $consulta->bindValue(':listaPedidos', $this->listaPedidos, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado,PDO::PARAM_STR);
        $consulta->bindValue(':puntaje',$this->puntaje,PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, listaPedidos, estado,puntaje FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }
    
}