<?php

require_once "conexion.php"; // Archivo que maneja la conexión a Oracle


// Función para obtener los detalles de una habitación
function obtenerDetallesOrderLines($order_line_id) {
    $oConexion = conectar();
    $sql = "SELECT 
                order_line_id,
                order_id,
                product_id,
                address_id,
                qty_item,
                comments, 
                status_id, 
                total_price, 
                created_on,
                created_by,
                modified_on,
                modified_by 
            FROM FIDE_SAMDESIGN.fide_order_lines_tb 
            WHERE order_line_id = :order_line_id";
    $stmt = oci_parse($oConexion, $sql);
    oci_bind_by_name($stmt, ":order_line_id", $order_line_id);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
    oci_close($oConexion);
    if ($row === false) {
        return ['error' => 'No se encontró la linea de orden con el ID proporcionado.'];
    }

    return $row;
}

// Insertar una OrderLines
function IngresarOrderLines($order_id, $product_id, $comments, $total_price, $created_by) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "INSERT INTO FIDE_SAMDESIGN.fide_order_lines_tb (order_line_id, order_id, product_id, qty_item, comments, status_id, total_price, created_on, created_by)
                VALUES (FIDE_ORDER_LINE_SEQ.NEXTVAL, :order_id, :product_id, 1, :comments, 1, :total_price, SYSDATE, :created_by)";

        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":order_id", $order_id);
        oci_bind_by_name($stmt, ":product_id", $product_id);
        oci_bind_by_name($stmt, ":total_price", $total_price);
        oci_bind_by_name($stmt, ":comments", $comments);
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
// Eliminar un linea de orden
function eliminarOrderLines($order_line_id, $modified_by) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql_cart = "UPDATE FIDE_SAMDESIGN.fide_order_lines_tb 
                     SET 
                        status_id = 0,
                        modified_on = SYSDATE,
                        modified_by = :modified_by 
                     WHERE order_line_id = :order_line_id";
        $stmt_cart = oci_parse($oConexion, $sql_cart);
        oci_bind_by_name($stmt_cart, ":order_line_id", $order_line_id);
        oci_bind_by_name($stmt_cart, ":modified_by", $modified_by);

        if (oci_execute($stmt_cart)) {
            $retorno = true;
        }
    } catch (\Throwable $th) {
        echo $th;
    } finally {
        if (isset($stmt_cart)) oci_free_statement($stmt_cart);
        oci_close($oConexion);
    }

    return $retorno;
}


// Actualizar un carrito
function actualizarOrderLines($order_line_id, $order_id, $product_id, $qty_item, $comments, $status_id, $total_price, $modified_by) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "UPDATE FIDE_SAMDESIGN.fide_order_lines_tb 
                SET 
                    order_id = :order_id,
                    product_id = :product_id,
                    qty_item = :qty_item,
                    comments = :comments,
                    status_id = :status_id,
                    total_price = :total_price,
                    modified_on = SYSDATE,
                    modified_by = :modified_by 
                WHERE order_line_id = :order_line_id";

        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":order_id", $order_id);
        oci_bind_by_name($stmt, ":product_id", $product_id);
        oci_bind_by_name($stmt, ":qty_item", $qty_item);
        oci_bind_by_name($stmt, ":comments", $comments);
        oci_bind_by_name($stmt, ":total_price", $total_price);
        oci_bind_by_name($stmt, ":status_id", $status_id);
        oci_bind_by_name($stmt, ":modified_by", $modified_by);
        oci_bind_by_name($stmt, ":order_line_id", $order_line_id);

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

    if ($action == "eliminar" && isset($_POST["order_line_id"])) {
        $order_line_id = $_POST["order_line_id"];
        $modified_by = $_POST["modified_by"];
        $eliminado = eliminarOrderLines($order_line_id, $modified_by);
        if ($eliminado) {
            echo "success";
        } else {
            echo "Error al eliminar el linea de orden";
        }
    } elseif ($action == "obtenerDetalles" && isset($_POST["order_line_id"])) {
        $order_line_id = $_POST["order_line_id"];
        error_log("Obteniendo detalles para ID: " . $order_line_id); // Depuración

        $detalles = obtenerDetallesOrderLines($order_line_id);

        if (isset($detalles['error'])) {
            http_response_code(404); // Devuelve error si no se encuentra el servicio
        }
        
        echo json_encode($detalles);
    } elseif ($action == "actualizar" && isset($_POST["ORDER_LINE_ID"])) {
        $order_line_id = $_POST["ORDER_LINE_ID"];
    
        // Registrar todos los datos recibidos para depuración
        error_log("Datos recibidos para actualizar: " . json_encode($_POST));
    
        // Verifica parámetros requeridos
        $required_fields = ["ORDER_LINE_ID", "PRODUCT_ID", "ORDER_ID", "QTY_ITEM", "STATUS_ID", "TOTAL_PRICE", "modified_by"];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                http_response_code(400); // Código de error 400: Solicitud Incorrecta
                echo "El campo $field es requerido.";
                exit;
            }
        }
    
        // Ejecuta la actualización
        $actualizado = actualizarOrderLines(
            $order_line_id,
            $_POST["ORDER_ID"],
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

        $insertado = IngresarOrderLines(
            $_POST["order_id"],
            $_POST["product_id"],
            $_POST["qty_item"],
            $_POST["comments"],
            $_POST["total_price"],
            $_POST["created_by"]
        );
        if ($insertado) {
            echo "success";
        } else {
            echo "Error al insertar el carrito";
        }
         
    } else {
        echo "Acción no válida";
    }
}
