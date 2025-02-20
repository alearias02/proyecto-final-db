<?php
require_once "conexion.php";

function IngresarFactura($pIdUsuario, $pFecha, $pTotal, $pEstado){
    $idFactura = null;
    $error = null;
    try {
        $oConexion = conectar();

        if (mysqli_set_charset($oConexion, "utf8")) {
            $stmt = $oConexion->prepare("INSERT INTO factura (id_usuario, fecha, total, estado) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isdi", $pIdUsuario, $pFecha, $pTotal, $pEstado);

            if ($stmt->execute()) {
                $idFactura = mysqli_insert_id($oConexion);
            }
        }
    } catch (\Throwable $th) {
        $error = $th->getMessage();
    } finally {
        desconectar($oConexion);
    }
    return $idFactura;
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


if(isset($_POST['idCliente'], $_POST['total'])) {
    $idCliente = $_POST['idCliente'];
    $fecha = date('Y-m-d H:i:s');
    $total = $_POST['total'];
    $estado = 1;

    $idFactura = IngresarFactura($idCliente, $fecha, $total, $estado);
    echo json_encode($idFactura);
}
?>
