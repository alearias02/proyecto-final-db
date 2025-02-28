<?php

require_once "conexion.php"; // Archivo que maneja la conexión a Oracle


// Función para obtener los detalles de un cliente
function obtenerDetallesDireccion($address_id) {
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

// Insertar un direccion
function IngresarDireccion($address_id, $address, $id_state, $id_city, $id_country, $id_customer, $zip_code ) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "INSERT INTO FIDE_SAMDESIGN.fide_address_tb (address_id, address, id_state, id_city, id_country, id_customer, zip_code)
                VALUES (:address_id, :address, :id_state, :id_city, :id_country, :id_customer, :zip_code)";

        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":address_id", $address_id);
        oci_bind_by_name($stmt, ":address", $address);
        oci_bind_by_name($stmt, ":id_state", $id_state);
        oci_bind_by_name($stmt, ":id_city", $id_city);
        oci_bind_by_name($stmt, ":id_country", $id_country);
        oci_bind_by_name($stmt, ":id_customer", $id_customer);
        oci_bind_by_name($stmt, ":zip_code", $zip_code);

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
// Eliminar una direccion
function eliminarDireccion($address_id) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "UPDATE FIDE_SAMDESIGN.fide_address_tb
                SET status_id = 0,
                    modified_on = SYSDATE
                WHERE address_id = :address_id";
        $stmt = oci_parse($oConexion, $sql);

        // Vincular el parámetro
        oci_bind_by_name($stmt, ":address_id", $address_id);

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

// Actualizar una direccion
function actualizarDireccion($address_id, $address, $id_state, $id_city, $id_country, $id_customer, $zip_code) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "UPDATE FIDE_SAMDESIGN.fide_address_tb 
                SET address_id = :address_id,
                    address = :address,
                    id_state = :id_state,
                    id_city = :id_city,
                    id_country = :id_country,
                    zip_code = :zip_code,
                    modified_on = SYSDATE,
                    id_customer = :id_customer
                WHERE address_id = :address_id";

        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":address_id", $address_id);
        oci_bind_by_name($stmt, ":address", $address);
        oci_bind_by_name($stmt, ":id_state", $id_state);
        oci_bind_by_name($stmt, ":id_city", $id_city);
        oci_bind_by_name($stmt, ":id_country", $id_country);
        oci_bind_by_name($stmt, ":id_customer", $id_customer);
        oci_bind_by_name($stmt, ":zip_code", $zip_code);

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

    if ($action == "eliminar" && isset($_POST["address_id"])) {
        $room_id = $_POST["address_id"];
        $eliminado = eliminarDireccion($address_id);
        echo $eliminado ? "La direccion ha sido eliminado exitosamente" : "Error al intentar eliminar la direccion";
    } elseif ($action == "obtenerDetalles" && isset($_POST["address_id"])) {
        $room_id = $_POST["address_id"];
        error_log("Obteniendo detalles para ID: " . $address_id); // Depuración

        $detalles = obtenerDetallesDireccion($address_id);

        if (isset($detalles['error'])) {
            http_response_code(404); // Devuelve error si no se encuentra el servicio
        }
        
        echo json_encode($detalles);
    } elseif ($action == "actualizar" && isset($_POST["ADDRESS_ID"])) {
        $address_id = $_POST["ADDRESS_ID"];
    
        // Registrar todos los datos recibidos para depuración
        error_log("Datos recibidos para actualizar: " . json_encode($_POST));
    
        // Verifica parámetros requeridos
        $required_fields = ["ADDRESS_ID", "ADDRESS", "ID_STATE", "ID_CITY", "ID_COUNTRY", "ID_CUSTOMER", "ZIP_CODE"];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                http_response_code(400); // Código de error 400: Solicitud Incorrecta
                echo "El campo $field es requerido.";
                exit;
            }
        }
    
        // Ejecuta la actualización
        $actualizado = actualizarDireccion(
            $address_id,
            $_POST["ADDRESS"],
            $_POST["ID_STATE"],
            $_POST["ID_CITY"],
            $_POST["ID_COUNTRY"],
            $_POST["ID_CUSTOMER"],
            $_POST["ZIP_CODE"]
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

        $insertado = IngresarDireccion(
            $_POST["ADDRESS_ID"],
            $_POST["ADDRESS"],
            $_POST["ID_STATE"],
            $_POST["ID_CITY"],
            $_POST["ID_COUNTRY"],
            $_POST["ID_CUSTOMER"],
            $_POST["ZIP_CODE"]
            );
        echo $insertado ? "Direccion insertada correctamente" : "Error al insertar direccion";
    } else {
        echo "Acción no válida";
    }
}
