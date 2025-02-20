<?php

require_once "conexion.php";

function IngresarRepuesto($pIdCategoria ,$pDescripcion, $pDetalle, $pTalla, $pPrecio, $pExistencias,$pRutaImagen, $pActivo){
    $retorno = false;

    try {
        $oConexion = conectar();

        if (mysqli_set_charset($oConexion, "utf8")) {
            $stmt = $oConexion->prepare("insert into repuesto (id_categoria,descripcion, detalle, talla, precio, existencias, ruta_imagen, activo) values (?,?, ?, ?, ?, ?, ?, ?)");
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
                $retorno = mysqli_fetch_assoc($result);  //devolver un solo objeto
            }
        }
    } catch (\Throwable $th) {
        echo $th;
    } finally {
        desconectar($oConexion);
    }
    return $retorno;
}

function eliminarRepuesto($pId) {
    $retorno = false;
    try {
        $oConexion = conectar();
        // formato de utf8
        if(mysqli_set_charset($oConexion, "utf8")){
            $stmt = $oConexion->prepare("delete from repuesto where id_repuesto = ?");
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
        $id_repuesto = $_POST["id"];
        $eliminado = eliminarRepuesto($id_repuesto);
        echo $eliminado ? "El repuesto ha sido eliminado exitosamente" : "Error al intentar eliminar el repuesto";
    } elseif ($action == "obtenerDetalles" && isset($_POST["id"])) {
        $id = $_POST["id"];
        $repuesto = getObject("SELECT * FROM repuesto WHERE id_repuesto = $id");
        echo json_encode($repuesto);
    } elseif ($action == "actualizar" && isset($_POST["id"])) {
        // Asumimos que todos los campos necesarios son proporcionados
        $actualizado = actualizarRepuesto($_POST["id"], $_POST["id_categoria"], $_POST["descripcion"], $_POST["detalle"], $_POST["talla"], $_POST["precio"], $_POST["existencias"], $_POST["ruta_imagen"], $_POST["activo"]);
        echo $actualizado ? "Repuesto actualizado correctamente" : "Error al actualizar el repuesto";
    } else {
        echo "Acción no válida";
    }
}


function actualizarRepuesto($pId, $pIdCategoria, $pDescripcion, $pDetalle, $pTalla, $pPrecio, $pExistencias, $pRutaImagen, $pActivo){
    $retorno = false;
    try {
        $oConexion = conectar();
        if (mysqli_set_charset($oConexion, "utf8")) {
            $stmt = $oConexion->prepare("UPDATE repuesto SET id_categoria, descripcion, detalle, talla, precio, existencias, ruta_imagen, activo values (?,?,?,?,?,?,?,?) WHERE id_repuesto=?");
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