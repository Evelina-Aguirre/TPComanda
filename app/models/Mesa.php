<?php

/**“con cliente esperando pedido” ,
*”con cliente comiendo”,
* “con cliente pagando” y
* “cerrada”.*/

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

    public function agregarPedido(Pedido $pedido)
    {
        $this->listaPedidos[] = $pedido;

        $pedido->actualizarEstadoYHora();
    }

    public function actualizarEstadoMesa()
    {
        foreach ($this->listaPedidos as $pedido) {

            $pedido->actualizarEstadoYHora();
        }

        $this->estado = $this->calcularEstadoMesa();
    }

    private function calcularEstadoMesa()
    {
        if (count($this->listaPedidos) > 0 && $this->listaPedidos[0]->estado == "listo para servir") {
            return "con cliente esperando pedido";
        } else {
            return "con cliente comiendo”,"; 
        }
    }

    
    
}