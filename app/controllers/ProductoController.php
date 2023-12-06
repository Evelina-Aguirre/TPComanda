<?php

use \Slim\Http\ServerRequest;
use Psr\Http\Message\ResponseInterface;

require_once './interfaces/IApiUsable.php';
require_once './models/Producto.php';
require_once './utils/csv.php';


class ProductoController extends Producto
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $nombre = $parametros['nombre'];
    $sectorAsignado = $parametros['sectorAsignado'];
    $precio = $parametros['precio'];

    $prod = new Producto();
    $prod->nombre = $nombre;
    $prod->sectorAsignado = $sectorAsignado;
    $prod->precio = $precio;
    $prod->crearProducto();

    $payload = json_encode(array("mensaje" => "Producto creado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Producto::obtenerTodos();
    $payload = json_encode(array("listaProductos" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    $nombre = $args['nombre'];
    $producto = Producto::ObtenerProductoPorNombre($nombre);
    $payload = json_encode($producto);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  private function parseCSV($csv)
  {
    $parsedData = [];
    $lines = explode("\n", $csv);
    foreach ($lines as $line) {
      $parsedData[] = str_getcsv($line);
    }
    return $parsedData;
  }

  public function cargarProductosDesdeCSV($request, $response, $args)
{
    $uploadedFiles = $request->getUploadedFiles();
    $uploadedFile = $uploadedFiles['archivo'] ?? null;

    if ($uploadedFile === null || $uploadedFile->getError() !== UPLOAD_ERR_OK) {
        $response->getBody()->write(json_encode(['error' => 'El archivo CSV no está cargado']));
        return $response->withStatus(400);
    }

    $csvData = $this->parseCSV($uploadedFile->getStream()->getContents());

    if (empty($csvData)) {
        return $response->withStatus(400)->withJson(['error' => 'Archivo vacío o inexistente.']);
    }

    foreach ($csvData as $row) {
        $productoData = [];
        for ($i = 0; $i < count($row); $i += 2) {
            $key = $row[$i] ?? null;
            $value = $row[$i + 1] ?? null;

            if ($key !== null && $value !== null) {
                $productoData[trim($key)] = trim($value);
            }
        }

        if (
            isset($productoData['nombre']) &&
            isset($productoData['sectorAsignado']) &&
            isset($productoData['precio'])
        ) {
            $this->CargarUnoProductoCsv(
                $productoData['nombre'],
                $productoData['sectorAsignado'],
                $productoData['precio']
            );
        }
    }

    $response->getBody()->write(json_encode(['mensaje' => 'Productos agregados desde CSV correctamente']));

return $response
    ->withHeader('Content-Type', 'application/json')
    ->withStatus(201);

}



  public function descargarProductosDesdeCSV($request, $response, $args)
  {
    $archivoDestino = '../productos.csv';
    $directorio = pathinfo($archivoDestino, PATHINFO_DIRNAME);
    if (!file_exists($directorio)) {
      mkdir($directorio, 0777, true);
    }
    $productos = $this->obtenerTodos();
    $csvContent = "id,nombre,sectorAsignado,precio\n";

    foreach ($productos as $producto) {
      $productoArray = (array)$producto;
      $csvContent .= implode(',', $productoArray) . "\n";
    }

    $response = $response->withHeader('Content-Type', 'text/csv')
      ->withHeader('Content-Disposition', 'attachment; filename=productos.csv')
      ->withHeader('Pragma', 'no-cache')
      ->withHeader('Expires', '0')
      ->withStatus(200);

    $response->getBody()->write($csvContent);
    file_put_contents($archivoDestino, $csvContent);

    return $response;
  }

  public function CargarUnoProductoCsv($nombre, $sectorAsignado, $precio)
  {
      $prod = new Producto();
      $prod->nombre = $nombre;
      $prod->sectorAsignado = $sectorAsignado;
      $prod->precio = $precio;
      $prod->crearProducto();
  
      return json_encode(["mensaje" => "Producto creado con éxito"]);
  }
}
