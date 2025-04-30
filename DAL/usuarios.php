<?php
require_once "conexion.php";

function usuarioExiste($pUsername) {
    try {
        $oConexion = conectar();

        // Verificar si el nombre de usuario ya existe
        $stmt = $oConexion->prepare("SELECT COUNT(*) FROM usuario WHERE username = ?");
        $stmt->bind_param("s", $pUsername);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        
        return $count > 0;
    } catch (\Throwable $th) {
        // Manejar el error
        echo $th;
        return false;
    } finally {
        desconectar($oConexion);
    }
}

function correoExiste($pCorreo) {
    try {
        $oConexion = conectar();

        // Verificar si el correo ya existe
        $stmt = $oConexion->prepare("SELECT COUNT(*) FROM usuario WHERE correo = ?");
        $stmt->bind_param("s", $pCorreo);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count > 0;
    } catch (\Throwable $th) {
        // Manejar el error
        echo $th;
        return false;
    } finally {
        desconectar($oConexion);
    }
}


function IngresarUsuario($pUsername, $pPassword, $pNombre, $pApellidos, $pCorreo, $pTelefono, $pRutaImagen, $pActivo) {
    $retorno = false;

    try {
    
        $oConexion = conectar();

        if (mysqli_set_charset($oConexion, "utf8")) {
           
            $hashedPassword = password_hash($pPassword, PASSWORD_DEFAULT);

            $stmt = $oConexion->prepare("INSERT INTO usuario (username, password, nombre, apellidos, correo, telefono, ruta_imagen, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssi", $vUsername, $vPassword, $vNombre, $vApellidos, $vCorreo, $vTelefono, $vRutaImagen, $vActivo);
            
            $vUsername = $pUsername;
            $vPassword = $hashedPassword; 
            $vNombre = $pNombre;
            $vApellidos = $pApellidos;
            $vCorreo = $pCorreo;
            $vTelefono = $pTelefono;
            $vRutaImagen = $pRutaImagen;
            $vActivo = $pActivo;

            if ($stmt->execute()) {
                $retorno = true;
            }
        }
    } catch (\Throwable $th) {
        // Manejar el error
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

function getObject($sql){
    try {
        $oConexion = conectar();

        if (mysqli_set_charset($oConexion, "utf8")) {
            if(!$result = mysqli_query($oConexion, $sql)) die();

            $retorno = null;

            while ($row = mysqli_fetch_array($result)) {
                $retorno = $row;
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