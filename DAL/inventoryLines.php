<?php

require_once "conexion.php"; // Archivo que maneja la conexión a Oracle


// Función para obtener los detalles de la linea de inventario
function obtenerDetallesLineasInventario($inventory_lines_id) {
    $oConexion = conectar();
    $sql = "SELECT 
                inventory_lines_id,
                inventory_id,
                product_id,
                comments, 
                quantity_stocked,
                quantity_reserved,
                status_id, 
                last_restocked,
                created_by,
                created_on,
                modified_on,
                modified_by
            FROM FIDE_SAMDESIGN.fide_inventory_lines_tb 
            WHERE inventory_lines_id = :inventory_lines_id";
    $stmt = oci_parse($oConexion, $sql);
    oci_bind_by_name($stmt, ":inventory_lines_id", $inventory_lines_id);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
    oci_close($oConexion);
    if ($row === false) {
        return ['error' => 'No se encontró el inventario con el ID proporcionado.'];
    }

    return $row;
}

// Insertar una linea
function IngresarLineasInventario($inventory_id, $product_id, $comments, $quantity_stocked, $created_by) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "INSERT INTO FIDE_SAMDESIGN.fide_inventory_lines_tb ( inventory_id, product_id, comments, quantity_stocked, status_id, last_restocked, created_by, created_on)
                VALUES (:inventory_id, :product_id, :comments, :quantity_stocked, 1, SYSDATE, :created_by, SYSDATE)";

        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":inventory_id", $inventory_id);
        oci_bind_by_name($stmt, ":product_id", $product_id);
        oci_bind_by_name($stmt, ":comments", $comments);
        oci_bind_by_name($stmt, ":quantity_stocked", $quantity_stocked);
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
function eliminarLineaInventario($inventory_lines_id, $modified_by) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "UPDATE FIDE_SAMDESIGN.fide_inventory_lines_tb
                SET status_id = 0,
                    modified_on = SYSDATE,
                    modified_by = :modified_by 
                WHERE inventory_lines_id = :inventory_lines_id";
        $stmt = oci_parse($oConexion, $sql);

        // Vincular el parámetro
        oci_bind_by_name($stmt, ":inventory_lines_id", $inventory_lines_id);
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
function actualizarLineaInventario($inventory_lines_id, $inventory_id, $product_id, $comments, $quantity_stocked, $quantity_reserved,  $status_id, $modified_by) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "UPDATE FIDE_SAMDESIGN.fide_inventory_lines_tb 
                SET inventory_id = :inventory_id,
                    product_id = :product_id,
                    comments = :comments,
                    quantity_stocked = :quantity_stocked,
                    quantity_reserved = :quantity_reserved,
                    status_id = :status_id,
                    modified_on = SYSDATE,
                    modified_by = :modified_by 
                WHERE inventory_lines_id = :inventory_lines_id";

        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":inventory_id", $inventory_id);
        oci_bind_by_name($stmt, ":product_id", $product_id);
        oci_bind_by_name($stmt, ":comments", $comments);
        oci_bind_by_name($stmt, ":quantity_stocked", $quantity_stocked);
        oci_bind_by_name($stmt, ":quantity_reserved", $quantity_reserved);
        oci_bind_by_name($stmt, ":status_id", $status_id);
        oci_bind_by_name($stmt, ":modified_by", $modified_by);
        oci_bind_by_name($stmt, ":inventory_lines_id", $inventory_lines_id);

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

    if ($action == "eliminar" && isset($_POST["inventory_lines_id"])) {
        $inventory_lines_id = $_POST["inventory_lines_id"];

        $modified_by = $_POST['modified_by'];
        $eliminado = eliminarLineaInventario($inventory_lines_id, $modified_by);

        if ($eliminado) {
            echo  "success" ;
        } else {
            echo  "Error al intentar eliminar la linea de inventario";
        }
       
    } elseif ($action == "obtenerDetalles" && isset($_POST["inventory_lines_id"])) {
        $inventory_lines_id = $_POST["inventory_lines_id"];
        error_log("Obteniendo detalles para ID: " . $inventory_lines_id); // Depuración

        $detalles = obtenerDetallesLineasInventario($inventory_lines_id);

        if (isset($detalles['error'])) {
            http_response_code(404); // Devuelve error si no se encuentra el servicio
        }
        
        echo json_encode($detalles);
    } elseif ($action == "actualizar" && isset($_POST["inventory_lines_id"])) {
        $inventory_lines_id = $_POST["inventory_lines_id"];
    
        // Registrar todos los datos recibidos para depuración
        error_log("Datos recibidos para actualizar: " . json_encode($_POST));
    
        // Verifica parámetros requeridos
        $required_fields = [
            "inventory_lines_id",
            "INVENTORY_ID",
            "PRODUCT_ID",
            "COMMENTS",
            "QUANTITY_STOCKED",
            "QUANTITY_RESERVED",
            "STATUS_ID",
            "modified_by"
        ];
    
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                http_response_code(400);
                echo "El campo $field es requerido.";
                exit;
            }
        }
    
        // Ejecuta la actualización
        $actualizado = actualizarLineaInventario(
            $_POST["inventory_lines_id"],
            $_POST["INVENTORY_ID"],
            $_POST["PRODUCT_ID"],
            $_POST["COMMENTS"],
            $_POST["QUANTITY_STOCKED"],
            $_POST["QUANTITY_RESERVED"],
            $_POST["STATUS_ID"],
            $_POST["modified_by"]
        );
    
        if ($actualizado) {
            echo "success";
        } else {
            http_response_code(500);
            echo "Error updating service. Check logs for details.";
        }
        exit;
    } elseif ($action == "insertar") {

        $insertado = IngresarLineasInventario(
            $_POST["inventory_id"],
            $_POST["PRODUCT_ID"],
            $_POST["comments"],
            $_POST["quantity_stocked"],
            $_POST["created_by"]
        );

        if ($insertado) {
            echo  "success" ;
        } else {
            echo "Error al insertar a el inventario";
        }
       
    } else {
        echo "Acción no válida";
    }
}
