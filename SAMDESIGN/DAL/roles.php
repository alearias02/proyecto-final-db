<?php 
function IngresarRol($pRol, $pId) {
    $retorno = false;

    try {
        $oConexion = conectar();
        if (mysqli_set_charset($oConexion, "utf8")) {
           
            $stmt = $oConexion->prepare("INSERT INTO rol (nombre , id_usuario) VALUES (?, ?)");
            $stmt->bind_param("si", $vRol, $vId);
            
            $vRol = $pRol;
            $vId = $pId; 
           

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

function getObjectR($sql){
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