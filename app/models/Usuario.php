<?php
include_once  __DIR__ . '/../db/AccesoDatos.php';
class Usuario
{
    public $id;
    public $nombre;
    public $clave;
    public $roll;
    public $fechaAlta;
    public $fechaBaja;
    public $estado;

    public function crearUsuario()
    {
        if ($this->usuarioExistente()) {
            throw new Exception("El nombre de usuario ya existe.");
        }

        $fecha = new DateTime(); //('Y-m-d h:i:sa');
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (nombre, clave,roll,fechaAlta) VALUES (:nombre, :clave, :roll, :fechaAlta)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':roll', $this->roll, PDO::PARAM_STR);
        //$fechaFormateada = $fecha->format('d-m-Y');
        if (empty($fecha)) {
            $fecha = new DateTime();}
        $consulta->bindValue(':fechaAlta', $fecha->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
    private function usuarioExistente()
{
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $consulta = $objAccesoDatos->prepararConsulta("SELECT COUNT(*) FROM usuarios WHERE nombre = :nombre AND roll = :roll");
    $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
    $consulta->bindValue(':roll', $this->roll, PDO::PARAM_STR);
    $consulta->execute();

    return $consulta->fetchColumn() > 0;
}

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, clave, roll, fechaAlta, fechaBaja, estado FROM usuarios");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, clave, roll, fechaAlta, fechaBaja, estado FROM usuarios WHERE nombre = :nombre");
        $consulta->bindValue(':nombre', $usuario, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public static function ObtenerUsuarioPorId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM empleados WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject("usuario");
    }

    public function modificarUsuario($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET nombre = :nombre, clave = :clave , roll = :roll, fechaAlta=:fechaAlta,fechaBaja=:fechaBaja, 
        estado =:estado WHERE id = $id");
        var_dump("intento modificarlo");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        var_dump($this->clave);
        var_dump($this->nombre);
        var_dump($this->fechaAlta);
        var_dump($this->id);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':roll', $this->roll, PDO::PARAM_STR);
        $consulta->bindValue(':fechaAlta', $this->fechaAlta/*->format('Y-m-d H:i:s')*/, PDO::PARAM_STR);
        $consulta->bindValue(':fechaBaja', $this->fechaBaja ? $this->fechaBaja->format('Y-m-d H:i:s') : null, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();

    }


    public static function borrarUsuario($id)
    {
        $estado="INACTIVO";
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja,estado =:estado WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->execute();
    }
}
