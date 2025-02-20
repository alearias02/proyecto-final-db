<?php

require_once "conexion.php";

function IngresarImpermeable($pIdCategoria ,$pDescripcion, $pDetalle, $pTalla, $pPrecio, $pExistencias,$pRutaImagen, $pActivo){
    $retorno = false;

    try {
        $oConexion = conectar();

        if (mysqli_set_charset($oConexion, "utf8")) {
            $stmt = $oConexion->prepare("insert into impermeable (id_categoria,descripcion, detalle, talla, precio, existencias, ruta_imagen, activo) values (?,?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssdisi",$vIdCategoria, $vDescripcion, $vDetalle, $vTalla, $vPrecio, $vExistencias, $vRutaImagen, $vActivo);

            //asignacion de param y ejecutar
            $vIdCategoria=$pIdCategoria;
            $vDescripcion = $pDescripcion;
            $vDetalle = $pDetalle;
            $vTalla = $pTalla;
            $vPrecio = $pPrecio;
            $vExistencias = $pExistencias;
            $vRutaImagen = $pRutaImagen;
            $vActivo = $pActivo;

            if ($stmt->execute()) {
                $retorno = true;
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

function getObject($sql){
    $retorno = null;
    try {
        $oConexion = conectar();
        if (mysqli_set_charset($oConexion, "utf8")) {
            if ($result = mysqli_query($oConexion, $sql)) {
                $retorno = mysqli_fetch_assoc($result);  // Cambio aquí para devolver un solo objeto
            }
        }
    } catch (\Throwable $th) {
        echo $th;
    } finally {
        desconectar($oConexion);
    }
    return $retorno;
}

function eliminarImpermeable($pId) {
    $retorno = false;
    try {
        $oConexion = conectar();
        // formato de utf8
        if(mysqli_set_charset($oConexion, "utf8")){
            $stmt = $oConexion->prepare("delete from impermeable where id_impermeable = ?");
            $stmt->bind_param("i", $iId);
            // set parameter y ejecutar
            $iId = $pId;
            if ($stmt->execute()){
                $retorno = true;
            }
        }
    } catch (\Throwable $th) {
        //almacenar en bitacora (Apache)
        //throw $th;
        echo $th;
    }finally{
        desconectar($oConexion);
    }

    return $retorno;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    $action = $_POST["action"];
    if ($action == "eliminar" && isset($_POST["id"])) {
        $id_impermeable = $_POST["id"];
        $eliminado = eliminarImpermeable($id_impermeable);
        echo $eliminado ? "El impermeable ha sido eliminado exitosamente" : "Error al intentar eliminar el impermeable";
    } elseif ($action == "obtenerDetalles" && isset($_POST["id"])) {
        $id = $_POST["id"];
        $impermeable = getObject("SELECT * FROM impermeable WHERE id_impermeable = $id");
        echo json_encode($impermeable);
    } elseif ($action == "actualizar" && isset($_POST["id"])) {
        // Asumimos que todos los campos necesarios son proporcionados
        $actualizado = actualizarImpermeable($_POST["id"], $_POST["id_categoria"], $_POST["descripcion"], $_POST["detalle"], $_POST["talla"], $_POST["precio"], $_POST["existencias"], $_POST["ruta_imagen"], $_POST["activo"]);
        echo $actualizado ? "Impermeable actualizado correctamente" : "Error al actualizar el impermeable";
    } else {
        echo "Acción no válida";
    }
}


function actualizarImpermeable($pId, $pIdCategoria, $pDescripcion, $pDetalle, $pTalla, $pPrecio, $pExistencias, $pRutaImagen, $pActivo){
    $retorno = false;
    try {
        $oConexion = conectar();
        if (mysqli_set_charset($oConexion, "utf8")) {
            $stmt = $oConexion->prepare("UPDATE impermeable SET id_categoria, descripcion, detalle, talla, precio, existencias, ruta_imagen, activo values (?,?,?,?,?,?,?,?) WHERE id_impermeable=?");
            $stmt->bind_param("isssdisii", $pIdCategoria, $pDescripcion, $pDetalle, $pTalla, $pPrecio, $pExistencias, $pRutaImagen, $pActivo, $pId);

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



