<?php



class Mesa
{
    public $id;
    public $codigo;
    public $estado;
    public $puntaje;

    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (codigo, estado,puntaje) VALUES (:codigo, :estado,:puntaje)");
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado,PDO::PARAM_STR);
        $consulta->bindValue(':puntaje',$this->puntaje,PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigo, estado,puntaje FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }



    public static function ActualizarEstado($id,$estado){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "UPDATE mesas SET estado = :estado WHERE id = :id");
        $consulta->bindValue(":estado",$estado,PDO::PARAM_STR);
        $consulta->bindValue(":id",$id,PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function mensajeEstadoMesa($estadoPedido)
    {
        if ($estadoPedido == "pendiente" || $estadoPedido == "en preparacion") {
            return "con cliente esperando pedido";
        } else {
            return "con cliente comiendo”,"; 
        }
    }

 /**“con cliente esperando pedido” ,
*”con cliente comiendo”,
* “con cliente pagando” y
* “cerrada”.*/
    
}