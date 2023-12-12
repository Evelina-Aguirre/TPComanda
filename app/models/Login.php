<?php

class Login
{
    public static function verificarCredenciales($usuario, $clave)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, clave FROM usuarios WHERE nombre = :nombre");
        $consulta->bindValue(':nombre', $usuario, PDO::PARAM_STR);
        $consulta->execute();

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($resultado && password_verify($clave, $resultado['clave'])) {
            return $resultado['id'];
        } else {
            return false;
        }
    }

    public static function obtenerToken($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("SELECT token FROM jwtusuarios WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        return ($resultado) ? $resultado['token'] : null;
    }

    public static function obtenerRoll($token)
    {
        try {
            $payload = AutentificadorJWT::ObtenerData($token);
            return $payload->roll;
        } catch (Exception $e) {
            return $e;
        }
    }

    public static function obtenerNombreUsuario($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT nombre FROM usuarios WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        return ($resultado) ? $resultado['nombre'] : null;
    }
}
