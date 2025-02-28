<?php

require_once "conexion.php"; // Archivo que maneja la conexión a Oracle


// Función para obtener los detalles de un cliente
function obtenerDetallesCliente($customer_id) {
    $oConexion = conectar();
    $sql = "SELECT 
                customer_id,
                customer_name,
                customer_email,
                customer_phone_number, 
                status_id, 
                created_by,
                created_on,
                modified_on,
                modified_by 
            FROM FIDE_SAMDESIGN.fide_customer_tb 
            WHERE customer_id = :customer_id";
    $stmt = oci_parse($oConexion, $sql);
    oci_bind_by_name($stmt, ":customer_id", $customer_id);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
    oci_close($oConexion);
    if ($row === false) {
        return ['error' => 'No se encontró el cliente con el ID proporcionado.'];
    }

    return $row;
}

// Insertar un cliente
function IngresarCliente($customer_id, $customer_name, $customer_email, $customer_phone_number) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "INSERT INTO FIDE_SAMDESIGN.fide_users_tb (customer_id, customer_name, customer_email, customer_phone_number)
                VALUES (:customer_id, :customer_name, :customer_email, :customer_phone_number)";

        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":customer_id", $customer_id);
        oci_bind_by_name($stmt, ":customer_name", $customer_name);
        oci_bind_by_name($stmt, ":customer_email", $customer_email);
        oci_bind_by_name($stmt, ":customer_phone_number", $customer_phone_number);

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
function eliminarCliente($customer_id) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "UPDATE FIDE_SAMDESIGN.fide_customer_tb
                SET status_id = 0,
                    modified_on = SYSDATE
                WHERE customer_id = :customer_id";
        $stmt = oci_parse($oConexion, $sql);

        // Vincular el parámetro
        oci_bind_by_name($stmt, ":customer_id", $customer_id);

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
function actualizarCliente($customer_id, $customer_name, $customer_email, $customer_phone_number) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "UPDATE FIDE_SAMDESIGN.fide_customer_tb 
                SET customer_id = :customer_id,
                    customer_name = :customer_name,
                    customer_email = customer_email,
                    customer_phone_number = :customer_phone_number,
                    modified_on = SYSDATE
                WHERE customer_id = :customer_id";

        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":customer_id", $customer_id);
        oci_bind_by_name($stmt, ":customer_name", $customer_name);
        oci_bind_by_name($stmt, ":customer_email", $customer_email);
        oci_bind_by_name($stmt, ":customer_phone_number", $customer_phone_number);

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

    if ($action == "eliminar" && isset($_POST["customer_id"])) {
        $room_id = $_POST["customer_id"];
        $eliminado = eliminarCliente($customer_id);
        echo $eliminado ? "El cliente ha sido eliminado exitosamente" : "Error al intentar eliminar el cliente";
    } elseif ($action == "obtenerDetalles" && isset($_POST["customer_id"])) {
        $room_id = $_POST["customer_id"];
        error_log("Obteniendo detalles para ID: " . $customer_id); // Depuración

        $detalles = obtenerDetallesCliente($customer_id);

        if (isset($detalles['error'])) {
            http_response_code(404); // Devuelve error si no se encuentra el servicio
        }
        
        echo json_encode($detalles);
    } elseif ($action == "actualizar" && isset($_POST["CUSTOMER_ID"])) {
        $customer_id = $_POST["CUSTOMER_ID"];
    
        // Registrar todos los datos recibidos para depuración
        error_log("Datos recibidos para actualizar: " . json_encode($_POST));
    
        // Verifica parámetros requeridos
        $required_fields = ["CUSTOMER_ID", "CUSTOMER_NAME", "CUSTOMER_EMAIL", "CUSTOMER_PHONE_NUMBER"];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                http_response_code(400); // Código de error 400: Solicitud Incorrecta
                echo "El campo $field es requerido.";
                exit;
            }
        }
    
        // Ejecuta la actualización
        $actualizado = actualizarCliente(
            $customer_id,
            $_POST["CUSTOMER_NAME"],
            $_POST["CUSTOMER_EMAIL"],
            $_POST["CUSTOMER_PHONE_NUMBER"]
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

        $insertado = IngresarCliente(
            $_POST["CUSTOMER_ID"],
            $_POST["CUSTOMER_NAME"],
            $_POST["CUSTOMER_EMAIL"],
            $_POST["CUSTOMER_PHONE_NUMBER"]
        );
        echo $insertado ? "Cliente insertado correctamente" : "Error al insertar el cliente";
    } else {
        echo "Acción no válida";
    }
}
