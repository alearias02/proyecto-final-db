<?php

require_once "conexion.php"; // Archivo que maneja la conexión a Oracle


// Función para obtener los detalles de una habitación
function obtenerDetallesInventario($inventory_id) {
    $oConexion = conectar();
    $sql = "SELECT 
                inventory_id,
                description, 
                status_id, 
                created_by,
                created_on,
                modified_on,
                modified_by 
            FROM FIDE_SAMDESIGN.fide_inventory_tb 
            WHERE inventory_id = :inventory_id";
    $stmt = oci_parse($oConexion, $sql);
    oci_bind_by_name($stmt, ":inventory_id", $inventory_id);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
    oci_close($oConexion);
    if ($row === false) {
        return ['error' => 'No se encontró el inventario con el ID proporcionado.'];
    }

    return $row;
}

// Insertar una habitación
function IngresarInventario($description, $created_by) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "INSERT INTO FIDE_SAMDESIGN.fide_inventory_tb (description, status_id, created_by, created_on)
                VALUES (:description, 1, :created_by, SYSDATE)";

        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":description", $description);
        oci_bind_by_name($stmt, ":created_by", $created_by);

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
function eliminarInventario($inventory_id, $modified_by) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "UPDATE FIDE_SAMDESIGN.fide_inventory_tb
                SET status_id = 0,
                    modified_on = SYSDATE,
                    modified_by = :modified_by 
                WHERE inventory_id = :inventory_id";
        $stmt = oci_parse($oConexion, $sql);

        // Vincular el parámetro
        oci_bind_by_name($stmt, ":inventory_id", $inventory_id);
        oci_bind_by_name($stmt, ":modified_by", $modified_by);

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
function actualizarInventario($inventory_id, $description, $status_id, $modified_by) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "UPDATE FIDE_SAMDESIGN.fide_inventory_tb 
                SET description = :description,
                    status_id = :status_id,
                    modified_on = SYSDATE,
                    modified_by = :modified_by 
                WHERE inventory_id = :inventory_id";

        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":description", $description);
        oci_bind_by_name($stmt, ":status_id", $status_id);
        oci_bind_by_name($stmt, ":modified_by", $modified_by);
        oci_bind_by_name($stmt, ":inventory_id", $inventory_id);

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

    if ($action == "eliminar" && isset($_POST["inventory_id"])) {
        $inventory_id = $_POST["inventory_id"];
        $modified_by = $_POST['modified_by'];
        $eliminado = eliminarInventario($inventory_id, $modified_by);
        if ($eliminado) {
            echo "success";
        } else {
            echo "Error al eliminar el producto";
        }
    } elseif ($action == "obtenerDetalles" && isset($_POST["inventory_id"])) {
        $inventory_id = $_POST["inventory_id"];
        error_log("Obteniendo detalles para ID: " . $inventory_id); // Depuración

        $detalles = obtenerDetallesInventario($inventory_id);

        if (isset($detalles['error'])) {
            http_response_code(404); // Devuelve error si no se encuentra el servicio
        }
        
        echo json_encode($detalles);
    } elseif ($action == "actualizar" && isset($_POST["INVENTORY_ID"])) {
        $inventory_id = $_POST["INVENTORY_ID"];
    
        // Registrar todos los datos recibidos para depuración
        error_log("Datos recibidos para actualizar: " . json_encode($_POST));
    
        // Verifica parámetros requeridos
        $required_fields = ["INVENTORY_ID", "DESCRIPTION", "STATUS_ID", "modified_by"];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                http_response_code(400); // Código de error 400: Solicitud Incorrecta
                echo "El campo $field es requerido.";
                exit;
            }
        }
    
        // Ejecuta la actualización
        $actualizado = actualizarInventario(
            $inventory_id,
            $_POST["DESCRIPTION"],
            $_POST["STATUS_ID"],
            $_POST["modified_by"]
        );
        if ($actualizado) {
            echo "success";
        } else {
            http_response_code(500); // Código de error 500: Error Interno
            echo "Error updating service. Check logs for details.";
        }
        exit;
    } elseif ($action == "insertar") {

        $insertado = IngresarInventario(
            $_POST["description"],
            $_POST["created_by"]
        );
        if ($insertado) {
            echo "success";
        } else {
            echo "Error al insertar el inventario";
        }
         
    } else {
        echo "Acción no válida";
    }
}
