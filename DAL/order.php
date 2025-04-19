<?php

require_once "conexion.php"; // Archivo que maneja la conexión a Oracle


// Función para obtener los detalles de una habitación
function obtenerDetallesOrder($order_id) {
    $oConexion = conectar();
    $sql = "SELECT 
                order_id,
                customer_id,
                order_date,
                order_amount,
                order_tax,
                comments,
                dispatch,
                fullfield, 
                status_id, 
                payment_method_id, 
                created_on,
                created_by,
                modified_on,
                modified_by,
                address_id 
            FROM FIDE_SAMDESIGN.fide_order_tb 
            WHERE order_id = :order_id";
    $stmt = oci_parse($oConexion, $sql);
    oci_bind_by_name($stmt, ":order_id", $order_id);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
    oci_close($oConexion);
    if ($row === false) {
        return ['error' => 'No se encontró la linea de orden con el ID proporcionado.'];
    }

    return $row;
}

// Insertar una Order
function IngresarOrder($customer_id, $order_amount, $order_tax, $comments, $payment_method_id, $created_by, $address_id ) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "INSERT INTO FIDE_SAMDESIGN.fide_order_tb 
            (customer_id, order_date, order_amount, order_tax, comments, dispatch, fullfield, status_id, payment_method_id, created_on, created_by, address_id)
        VALUES 
            (:customer_id, TRUNC(SYSDATE), :order_amount, :order_tax, :comments, 0, 0, 1, :payment_method_id, SYSDATE, :created_by, :address_id)";

        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":customer_id", $customer_id);
        oci_bind_by_name($stmt, ":order_amount", $order_amount);
        oci_bind_by_name($stmt, ":order_tax", $order_tax);
        oci_bind_by_name($stmt, ":comments", $comments);
        oci_bind_by_name($stmt, ":payment_method_id", $payment_method_id);
        oci_bind_by_name($stmt, ":created_by", $created_by);
        oci_bind_by_name($stmt, ":address_id", $address_id);
 
        // Ejecutar la consulta
        if (oci_execute($stmt)) {
            $retorno = true;
            oci_commit($oConexion);
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
function eliminarOrder($order_id, $modified_by) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql_cart = "UPDATE FIDE_SAMDESIGN.fide_order_tb 
                     SET 
                        status_id = 0,
                        modified_on = SYSDATE,
                        modified_by = :modified_by 
                     WHERE order_id = :order_id";
        $stmt_cart = oci_parse($oConexion, $sql_cart);
        oci_bind_by_name($stmt_cart, ":order_id", $order_id);
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
function actualizarOrder($order_id, $customer_id, $order_date, $order_amount, $order_tax, $comments, $dispatch, $fullfield, $status_id, $payment_method_id, $modified_by, $address_id) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "UPDATE FIDE_SAMDESIGN.fide_order_tb 
                SET 
                    customer_id = :customer_id,
                    order_date = :order_date,
                    order_amount = :order_amount,
                    order_tax = :order_tax,
                    comments = :comments,
                    dispatch = :dispatch,
                    fullfield = :fullfield,
                    status_id = :status_id,
                    payment_method_id = :payment_method_id,
                    modified_on = SYSDATE,
                    modified_by = :modified_by,
                    address_id = :address_id 
                WHERE order_id = :order_id";

        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":order_id", $order_id);
        oci_bind_by_name($stmt, ":customer_id", $customer_id);
        oci_bind_by_name($stmt, ":order_date", $order_date);
        oci_bind_by_name($stmt, ":order_amount", $order_amount);
        oci_bind_by_name($stmt, ":order_tax", $order_tax);
        oci_bind_by_name($stmt, ":comments", $comments);
        oci_bind_by_name($stmt, ":dispatch", $dispatch);
        oci_bind_by_name($stmt, ":fullfield", $fullfield);
        oci_bind_by_name($stmt, ":status_id", $status_id);
        oci_bind_by_name($stmt, ":payment_method_id", $payment_method_id);
        oci_bind_by_name($stmt, ":modified_by", $modified_by);
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

// Manejar acciones desde solicitudes POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    $action = $_POST["action"];

    if ($action == "eliminar" && isset($_POST["order_id"])) {
        $order_id = $_POST["order_id"];
        $modified_by = $_POST["modified_by"];
        $eliminado = eliminarOrder($order_id, $modified_by);
        if ($eliminado) {
            echo "success";
        } else {
            echo "Error al eliminar el linea de orden";
        }

    } elseif ($action == "obtenerDetalles" && isset($_POST["order_id"])) {
        $order_id = $_POST["order_id"];
        error_log("Obteniendo detalles para ID: " . $order_id);

        $detalles = obtenerDetallesOrder($order_id);

        if (isset($detalles['error'])) {
            http_response_code(404);
        }

        echo json_encode($detalles);

    } elseif ($action == "actualizar") {
        error_log("Datos recibidos para actualizar: " . json_encode($_POST));

        $required_fields = ["ORDER_ID", "CUSTOMER_ID", "ORDER_DATE", "ORDER_AMOUNT", "ORDER_TAX", "COMMENTS", "DISPATCH", "FULLFIELD", "STATUS_ID", "PAYMENT_METHOD_ID", "modified_by", "ADDRESS_ID"];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                http_response_code(400);
                echo "El campo $field es requerido.";
                exit;
            }
        }

        $actualizado = actualizarOrder(
            $_POST["ORDER_ID"],
            $_POST["CUSTOMER_ID"],
            $_POST["ORDER_DATE"],
            $_POST["ORDER_AMOUNT"],
            $_POST["ORDER_TAX"],
            $_POST["COMMENTS"],
            $_POST["DISPATCH"],
            $_POST["FULLFIELD"],
            $_POST["STATUS_ID"],
            $_POST["PAYMENT_METHOD_ID"],
            $_POST["modified_by"],
            $_POST["ADDRESS_ID"]
        );

        if ($actualizado) {
            echo "success";
        } else {
            http_response_code(500);
            echo "Error updating order. Check logs for details.";
        }

    } elseif ($action == "insertar") {
        $required_fields = ["customer_id", "order_amount", "order_tax", "comments", "payment_method_id", "created_by", "address_id"];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                http_response_code(400);
                echo "El campo $field es requerido.";
                exit;
            }
        }

        $insertado = IngresarOrder(
            $_POST["customer_id"],
            $_POST["order_amount"],
            $_POST["order_tax"],
            $_POST["comments"],
            $_POST["payment_method_id"],
            $_POST["created_by"],
            $_POST["address_id"]
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
function obtenerOrdenes() {
    $conn = conectar();

    $sql = "
        SELECT 
            o.ORDER_ID,
            c.CUSTOMER_NAME,
            TRUNC(o.ORDER_DATE) AS ORDER_DATE,
            o.ORDER_AMOUNT,
            o.COMMENTS,
            p.PAYMENT_METHOD_NAME,
            o.STATUS_ID
        FROM 
            FIDE_ORDER_TB o
        JOIN 
            FIDE_CUSTOMER_TB c ON o.CUSTOMER_ID = c.CUSTOMER_ID
        JOIN 
            FIDE_PAYMENT_METHOD_TB p ON o.PAYMENT_METHOD_ID = p.PAYMENT_METHOD_ID
        ORDER BY o.ORDER_ID DESC
    ";

    $stmt = oci_parse($conn, $sql);
    oci_execute($stmt);

    $ordenes = [];
    while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $ordenes[] = $row;
    }

    oci_free_statement($stmt);
    oci_close($conn);

    return $ordenes;
}

function obtenerEstados() {
    $conexion = conectar();
    $sql = "SELECT status_id, description FROM FIDE_SAMDESIGN.FIDE_STATUS_TB WHERE status_id > 0";
    $stmt = oci_parse($conexion, $sql);
    oci_execute($stmt);
    
    $estados = [];
    while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $estados[] = $row;
    }

    oci_free_statement($stmt);
    oci_close($conexion);
    return $estados;
}

function obtenerLineasOrden($order_id) {
    $conn = conectar(); // ← conecta usando OCI

    $sql = "SELECT 
                ol.ORDER_LINE_ID, 
                p.DESCRIPTION, 
                ol.QTY_ITEM, 
                ol.TOTAL_PRICE, 
                ol.COMMENTS
            FROM 
                FIDE_SAMDESIGN.FIDE_ORDER_LINES_TB ol
            JOIN 
                FIDE_SAMDESIGN.FIDE_PRODUCT_TB p ON ol.PRODUCT_ID = p.PRODUCT_ID
            WHERE 
                ol.ORDER_ID = :order_id";

    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':order_id', $order_id);
    oci_execute($stmt);

    $lineas = [];
    while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $lineas[] = $row;
    }

    oci_free_statement($stmt);
    oci_close($conn);

    return $lineas;
}

function obtenerOrdenPorId($order_id) {
    $conn = conectar();

    $sql = "SELECT 
                o.ORDER_ID,
                c.CUSTOMER_NAME,
                TRUNC(o.ORDER_DATE) AS ORDER_DATE,
                o.ORDER_AMOUNT,
                o.COMMENTS,
                p.PAYMENT_METHOD_NAME,
                s.DESCRIPTION AS STATUS_DESCRIPTION
            FROM 
                FIDE_ORDER_TB o
            JOIN 
                FIDE_CUSTOMER_TB c ON o.CUSTOMER_ID = c.CUSTOMER_ID
            JOIN 
                FIDE_PAYMENT_METHOD_TB p ON o.PAYMENT_METHOD_ID = p.PAYMENT_METHOD_ID
            JOIN 
                FIDE_STATUS_TB s ON o.STATUS_ID = s.STATUS_ID
            WHERE 
                o.ORDER_ID = :order_id";

    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":order_id", $order_id);
    oci_execute($stmt);

    $orden = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS);

    oci_free_statement($stmt);
    oci_close($conn);

    return $orden;
}