<?php

class Pedido
{
    public $id;
    public $listaProductos;
    public $nombreCliente;
    public $fotoMesa;
    public $estado;
    public $tiempoEstimado;
    public $total;

    public function agregarProducto(Producto $producto)
    {
        $this->listaProductos[] = $producto;
    }

    public function calcularPrecioTotal()
    {
        $precioTotal = 0;

        foreach ($this->listaProductos as $producto) {
            $precioTotal += $producto->precio;
        }

        return $precioTotal;
    }

    
}
