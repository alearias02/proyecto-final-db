<?php 
require_once "conexion.php";
// Obtener un array de resultados
function getArray($sql) {
    $retorno = [];
    try {
        $oConexion = conectar();
        $stid = oci_parse($oConexion, $sql);

        if (!oci_execute($stid)) {
            $e = oci_error($stid);
            throw new Exception($e['message']);
        }

        while ($row = oci_fetch_assoc($stid)) {
            $retorno[] = $row;
        }
    } catch (\Throwable $th) {
        echo $th;
    } finally {
        oci_free_statement($stid);
        oci_close($oConexion);
    }
    return $retorno;
}

// Obtener un único objeto
function getObject($sql) {
    $retorno = null;
    try {
        $oConexion = conectar();
        $stid = oci_parse($oConexion, $sql);

        if (!oci_execute($stid)) {
            $e = oci_error($stid);
            throw new Exception($e['message']);
        }

        $retorno = oci_fetch_assoc($stid);
    } catch (\Throwable $th) {
        echo $th;
    } finally {
        oci_free_statement($stid);
        oci_close($oConexion);
    }
    return $retorno;
}


function fetchAll($connection, $query) {
    $statement = oci_parse($connection, $query);
    if (!oci_execute($statement)) {
        $error = oci_error($statement);
        die("Error en la consulta: " . $error['message']);
    }

    $results = [];
    while ($row = oci_fetch_assoc($statement)) {
        $results[] = $row;
    }

    oci_free_statement($statement);
    return $results;
}
?>