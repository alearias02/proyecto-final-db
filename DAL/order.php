<?php

require_once "conexion.php"; // Archivo que maneja la conexión a Oracle


// Función para obtener los detalles de una habitación
function obtenerDetallesOrden($order_id) {
    $oConexion = conectar();
    $sql = "SELECT 
                cart_id,
                customer_id,
                address_id,
                order_date,
                comments, 
                status_id, 
                payment_method_id, 
                created_by,
                created_on,
                modified_on,
                modified_by 
            FROM FIDE_SAMDESIGN.fide_cart_tb 
            WHERE cart_id = :cart_id";
    $stmt = oci_parse($oConexion, $sql);
    oci_bind_by_name($stmt, ":cart_id", $cart_id);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
    oci_close($oConexion);
    if ($row === false) {
        return ['error' => 'No se encontró el carrito con el ID proporcionado.'];
    }

    return $row;
}

// Insertar una orden
function IngresarOrden(
    $customer_id,
    $order_date,
    $order_amount,
    $comments,
    $payment_method_id,
    $created_on,
    $created_by
) {
    $resultado = false;

    try {
        // Conectar a la base de datos Oracle
        $conexion = conectar();
        if (!$conexion) {
            $e = oci_error();
            throw new Exception($e['message']);
        }

        // Preparar la llamada al procedimiento almacenado
        $sql = 'BEGIN 
                INSERTAR_FIDE_ORDER_TB_SP( :customer_id, :order_date, :order_amount, :comments, 8, :payment_method_id, :created_on, :created_by); 
                END;';
        $stmt = oci_parse($conexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ':customer_id', $customer_id);
        oci_bind_by_name($stmt, ':order_date', $order_date);
        oci_bind_by_name($stmt, ':order_amount', $order_amount);
        oci_bind_by_name($stmt, ':comments', $comments);
        oci_bind_by_name($stmt, ':payment_method_id', $payment_method_id);
        oci_bind_by_name($stmt, ':created_on', $created_on);
        oci_bind_by_name($stmt, ':created_by', $created_by);

        // Ejecutar la consulta
        if (oci_execute($stmt)) {
            $resultado = true;
        } else {
            $e = oci_error($stmt);
            throw new Exception($e['message']);
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    } finally {
        // Liberar recursos
        if (isset($stmt)) {
            oci_free_statement($stmt);
        }
        if (isset($conexion)) {
            oci_close($conexion);
        }
    }

    return $resultado;
}

// Eliminar un producto
function eliminarCarrito($cart_id, $modified_by) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "UPDATE FIDE_SAMDESIGN.fide_cart_tb
                SET status_id = 0,
                    modified_on = SYSDATE,
                    modified_by = :modified_by 
                WHERE cart_id = :cart_id";
        $stmt = oci_parse($oConexion, $sql);

        // Vincular el parámetro
        oci_bind_by_name($stmt, ":cart_id", $cart_id);
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
function actualizarCarrito($cart_id, $description, $status_id, $modified_by) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "UPDATE FIDE_SAMDESIGN.fide_cart_tb 
                SET description = :description,
                    status_id = :status_id,
                    modified_on = SYSDATE,
                    modified_by = :modified_by 
                WHERE cart_id = :cart_id";

        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":description", $description);
        oci_bind_by_name($stmt, ":status_id", $status_id);
        oci_bind_by_name($stmt, ":modified_by", $modified_by);
        oci_bind_by_name($stmt, ":cart_id", $cart_id);

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

    if ($action == "eliminar" && isset($_POST["cart_id"])) {
        $cart_id = $_POST["cart_id"];
        $modified_by = $_POST['modified_by'];
        $eliminado = eliminarCarrito($cart_id, $modified_by);
        if ($eliminado) {
            echo "success";
        } else {
            echo "Error al eliminar el producto";
        }
    } elseif ($action == "obtenerDetalles" && isset($_POST["cart_id"])) {
        $cart_id = $_POST["cart_id"];
        error_log("Obteniendo detalles para ID: " . $cart_id); // Depuración

        $detalles = obtenerDetallesCarrito($cart_id);

        if (isset($detalles['error'])) {
            http_response_code(404); // Devuelve error si no se encuentra el servicio
        }
        
        echo json_encode($detalles);
    } elseif ($action == "actualizar" && isset($_POST["CART_ID"])) {
        $cart_id = $_POST["CART_ID"];
    
        // Registrar todos los datos recibidos para depuración
        error_log("Datos recibidos para actualizar: " . json_encode($_POST));
    
        // Verifica parámetros requeridos
        $required_fields = ["CART_ID", "DESCRIPTION", "STATUS_ID", "modified_by"];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                http_response_code(400); // Código de error 400: Solicitud Incorrecta
                echo "El campo $field es requerido.";
                exit;
            }
        }
    
        // Ejecuta la actualización
        $actualizado = actualizarCarrito(
            $cart_id,
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

        $insertado = IngresarCarrito(
            $_POST["description"],
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
