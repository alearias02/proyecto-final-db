<?php 

function obtenerRolUsuario($role) {
    $oConexion = conectar();
    $sql = "SELECT 
                address_id,
                address,
                id_state,
                id_city,
                id_country,
                id_customer,
                zip_code,
                status_id,
                created_by,
                created_on,
                modified_on,
                modified_by 
            FROM FIDE_SAMDESIGN.fide_address_tb 
            WHERE address_id = :address_id";
    $stmt = oci_parse($oConexion, $sql);
    oci_bind_by_name($stmt, ":address_id", $address_id);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
    oci_close($oConexion);
    if ($row === false) {
        return ['error' => 'No se encontró la direccion con el ID proporcionado.'];
    }

    return $row;
}

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