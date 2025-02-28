<?php 
require_once "conexion.php";
function IngresarVenta($pIdFactura, $pIdProducto, $pDescripcion, $pPrecio, $pCantidad) {
    $retorno = false;

    try {
        $oConexion = conectar();

        if (mysqli_set_charset($oConexion, "utf8")) {
            $stmt = $oConexion->prepare("INSERT INTO venta (id_factura, id_producto, descripcion, precio, cantidad) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iisdi", $pIdFactura, $pIdProducto, $pDescripcion, $pPrecio, $pCantidad);

            if ($stmt->execute()) {
                $retorno = true;
            }
        }
    } catch (\Throwable $th) {
        echo $th;
    } finally {
        desconectar($oConexion);
    }
    return $retorno;
}


function getArray($sql){
    try {
        $oConexion = conectar();

        if (mysqli_set_charset($oConexion, "utf8")) {
            if(!$result = mysqli_query($oConexion, $sql)) die();//cancelar ejecucion

            $retorno = array();

            while ($row = mysqli_fetch_array($result)) {
                $retorno[] = $row;
            }
        }
    } catch (\Throwable $th) {
        //throw $th;
        echo $th;
    }finally{
        desconectar($oConexion);
    }
    return $retorno;
}


if(isset($_POST['idFactura'], $_POST['carrito'])) {
    $idFactura = $_POST['idFactura'];
    $carrito = json_decode($_POST['carrito'], true); 

    foreach ($carrito as $producto) {
        $idProducto = $producto['codigo'];
        $descripcion = $producto['descripcion'];
        $precio = $producto['precio'];
        $cantidad = $producto['cantidad'];

        IngresarVenta($idFactura, $idProducto, $descripcion, $precio, $cantidad);
    }
}
?>