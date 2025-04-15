<?php

require_once "conexion.php"; // Archivo que maneja la conexión a Oracle


// Función para obtener los detalles de una habitación
function obtenerDetallesCartLines($cart_line_id) {
    $oConexion = conectar();
    $sql = "SELECT 
                cart_line_id,
                cart_id,
                product_id,
                qty_item,
                comments, 
                status_id, 
                total_price,
                created_by,
                created_on,
                modified_on,
                modified_by 
            FROM FIDE_SAMDESIGN.fide_cart_lines_tb 
            WHERE cart_line_id = :cart_line_id";
    $stmt = oci_parse($oConexion, $sql);
    oci_bind_by_name($stmt, ":cart_line_id", $cart_line_id);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
    oci_close($oConexion);
    if ($row === false) {
        return ['error' => 'No se encontró la linea del carrito con el ID proporcionado.'];
    }

    return $row;
}

// Insertar una linea de carrito
function IngresarCartLines($cart_id, $product_id, $total_price, $created_by) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "INSERT INTO FIDE_SAMDESIGN.fide_cart_lines_tb (cart_line_id, cart_id, product_id, qty_item, status_id, total_price, created_on, created_by)
                VALUES (FIDE_CART_LINE_SEQ.NEXTVAL, :cart_id, :product_id, 1, 1, :total_price, SYSTIMESTAMP, :created_by)";
           
        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":cart_id", $cart_id);
        oci_bind_by_name($stmt, ":product_id", $product_id);
        oci_bind_by_name($stmt, ":total_price", $total_price);
        oci_bind_by_name($stmt, ":qty_item", $qty_item);
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
function eliminarCartLines($cart_line_id) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "DELETE FROM FIDE_SAMDESIGN.fide_cart_lines_tb
                WHERE cart_line_id = :cart_line_id";
        $stmt = oci_parse($oConexion, $sql);

        oci_bind_by_name($stmt, ":cart_line_id", $cart_line_id);

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

// Actualizar una linea de carrito
function actualizarCartLines($cart_line_id, $cart_id, $product_id, $qty_item, $comments, $status_id, $total_price, $modified_by) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "UPDATE FIDE_SAMDESIGN.fide_cart_lines_tb 
                SET cart_id = :cart_id,
                    product_id = :product_id,
                    qty_item = :qty_item,
                    commments = :comments,
                    status_id = :status_id,
                    total_price = :total_price,
                    modified_on = SYSDATE,
                    modified_by = :modified_by 
                WHERE cart_line_id = :cart_line_id";

        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":cart_line_id", $cart_line_id);
        oci_bind_by_name($stmt, ":cart_id", $cart_id);
        oci_bind_by_name($stmt, ":product_id", $product_id);
        oci_bind_by_name($stmt, ":qty_item", $qty_item);
        oci_bind_by_name($stmt, ":comments", $comments);
        oci_bind_by_name($stmt, ":total_price", $total_price);
        oci_bind_by_name($stmt, ":status_id", $status_id);
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

// Manejar acciones desde solicitudes POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    $action = $_POST["action"];

    if ($action == "eliminar" && isset($_POST["cart_line_id"])) {
        $cart_line_id = $_POST["cart_line_id"];
        $eliminado = eliminarCartLines($cart_line_id);
        if ($eliminado) {
            echo "success";
        } else {
            echo "Error al eliminar el producto";
        }
    } elseif ($action == "obtenerDetalles" && isset($_POST["cart_line_id"])) {
        $cart_line_id = $_POST["cart_line_id"];
        error_log("Obteniendo detalles para ID: " . $cart_line_id); // Depuración

        $detalles = obtenerDetallesCartLines($cart_line_id);

        if (isset($detalles['error'])) {
            http_response_code(404); // Devuelve error si no se encuentra el servicio
        }
        
        echo json_encode($detalles);
    } elseif ($action == "actualizar" && isset($_POST["CART_LINE_ID"])) {
        $cart_line_id = $_POST["CART_LINE_ID"];
    
        // Registrar todos los datos recibidos para depuración
        error_log("Datos recibidos para actualizar: " . json_encode($_POST));
    
        // Verifica parámetros requeridos
        $required_fields = ["CART_LINE_ID", "CART_ID", "PRODUCT_ID", "QTY_ITEM", "STATUS_ID", "modified_by"];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                http_response_code(400); // Código de error 400: Solicitud Incorrecta
                echo "El campo $field es requerido.";
                exit;
            }
        }
    
        // Ejecuta la actualización
        $actualizado = actualizarCartLines(
            $cart_line_id,
            $_POST["CART_ID"],
            $_POST["PRODUCT_ID"],
            $_POST["QTY_ITEM"],
            $_POST["COMMENTS"],
            $_POST["STATUS_ID"],
            $_POST["TOTAL_PRICE"],
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

        $insertado = IngresarCartLines(
            $_POST["cart_id"],
            $_POST["product_id"],
            $_POST["total_price"],
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
