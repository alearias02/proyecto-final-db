<?php 

function obtenerDetallesRol($rol_id) {
    $oConexion = conectar();
    $sql = "SELECT 
                rol_id,
                rol_name,
                status_id,
                created_by,
                created_on,
                modified_on,
                modified_by 
            FROM FIDE_SAMDESIGN.fide_rol_tb 
            WHERE rol_id = :rol_id";
    $stmt = oci_parse($oConexion, $sql);
    oci_bind_by_name($stmt, ":rol_id", $rol_id);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
    oci_close($oConexion);
    if ($row === false) {
        return ['error' => 'No se encontró la direccion con el ID proporcionado.'];
    }

    return $row;
}

function IngresarRol($rol_name) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "INSERT INTO FIDE_SAMDESIGN.fide_rol_tb (rol_name, status_id, created_by)
                VALUES (:rol_name, 1, SYSDATE)";
        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":rol_name", $rol_name);

        // Ejecutar la consulta
        if (oci_execute($stmt)) {
            $retorno = true;
        }

    } catch (\Throwable $th) {
        echo $th;
    } finally {
        oci_free_statement($stmt);
        oci_close($oConexion);
    }

    return $retorno;
}
// Eliminar un producto
function eliminarRol($rol_id) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "UPDATE FIDE_SAMDESIGN.fide_rol_tb
                SET status_id = 0,
                    modified_on = SYSDATE
                WHERE rol_id = :rol_id";
        $stmt = oci_parse($oConexion, $sql);

        // Vincular el parámetro
        oci_bind_by_name($stmt, ":rol_id", $rol_id);

        // Ejecutar la consulta
        if (oci_execute($stmt)) {
            $retorno = true;
        }
    } catch (\Throwable $th) {
        echo $th;
    } finally {
        oci_free_statement($stmt);
        oci_close($oConexion);
    }

    return $retorno;
}

// Actualizar un producto
function actualizarRol($rol_id, $rol_name, $status_id) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "UPDATE FIDE_SAMDESIGN.fide_rol_tb 
                SET rol_id = :rol_id,
                    rol_name = :rol_name,
                    status_id = :status_id,
                    modified_on = SYSDATE
                WHERE rol_id = :rol_id";

        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":rol_id", $rol_id);
        oci_bind_by_name($stmt, ":rol_name", $rol_name);
        oci_bind_by_name($stmt, ":status_id", $status_id);

        // Ejecutar la consulta
        if (oci_execute($stmt)) {
            $retorno = true;
        }

    } catch (\Throwable $th) {
        echo $th;
    } finally {
        oci_free_statement($stmt);
        oci_close($oConexion);
    }

    return $retorno;
}

// Manejar acciones desde solicitudes POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    $action = $_POST["action"];

    if ($action == "eliminar" && isset($_POST["rol_id"])) {
        $rol_id = $_POST["rol_id"];
        $eliminado = eliminarRol($rol_id);
        echo $eliminado ? "El rol ha sido eliminado exitosamente" : "Error al intentar eliminar el rol";
    } elseif ($action == "obtenerDetalles" && isset($_POST["rol_id"])) {
        $rol_id = $_POST["rol_id"];
        error_log("Obteniendo detalles para ID: " . $rol_id); // Depuración

        $detalles = obtenerDetallesRol($rol_id);

        if (isset($detalles['error'])) {
            http_response_code(404); // Devuelve error si no se encuentra el servicio
        }
        
        echo json_encode($detalles);
    } elseif ($action == "actualizar" && isset($_POST["ROL_ID"])) {
        $rol_id = $_POST["ROL_ID"];
    
        // Registrar todos los datos recibidos para depuración
        error_log("Datos recibidos para actualizar: " . json_encode($_POST));
    
        // Verifica parámetros requeridos
        $required_fields = ["ROL_NAME"];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                http_response_code(400); // Código de error 400: Solicitud Incorrecta
                echo "El campo $field es requerido.";
                exit;
            }
        }
    
        // Ejecuta la actualización
        $actualizado = actualizarRol(
            $rol_id,
            $_POST["ROL_NAME"],
            $_POST["STATUS_ID"]
        );
    
        // Envía la respuesta adecuada
        if ($actualizado) {
            echo "success";
        } else {
            http_response_code(500); // Código de error 500: Error Interno
            echo "Error updating service. Check logs for details.";
        }
        exit;
    } elseif ($action == "insertar") {

        $insertado = IngresarRol(
            $_POST["ROL_NAME"]
        );
        echo $insertado ? "Rol insertado correctamente" : "Error al insertar el rol";
    } else {
        echo "Acción no válida";
    }
}
