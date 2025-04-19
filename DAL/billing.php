<?php

require_once "conexion.php"; // Archivo que maneja la conexión a Oracle


// Función para obtener los detalles de una habitación
function obtenerDetallesBilling($billing_id) {
    $oConexion = conectar();
    $sql = "SELECT 
                billing_id,
                order_id,
                customer_id,
                invoiced_address_id,
                billing_date,
                total_amount,
                comments,
                status_id, 
                payment_method_id, 
                created_on,
                created_by,
                modified_on,
                modified_by,
                address_id 
            FROM FIDE_SAMDESIGN.fide_billing_tb 
            WHERE billing_id = :billing_id";
    $stmt = oci_parse($oConexion, $sql);
    oci_bind_by_name($stmt, ":billing_id", $billing_id);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
    oci_close($oConexion);
    if ($row === false) {
        return ['error' => 'No se encontró la linea de orden con el ID proporcionado.'];
    }

    return $row;
}

// Insertar una Billing
function IngresarBilling($order_id, $customer_id, $address_id, $total_amount, $comments, $payment_method_id, $created_by) {
    $retorno = false;

    try {
        $conn = conectar();

        $sql = "BEGIN 
                    INSERTAR_FIDE_BILLING_TB_SP(
                        :order_id,
                        :customer_id,
                        :address_id,
                        SYSTIMESTAMP,
                        :total_amount,
                        :comments,
                        2, 
                        :payment_method_id,
                        SYSTIMESTAMP,
                        :created_by
                    );
                END;";

        $stmt = oci_parse($conn, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":order_id", $order_id);
        oci_bind_by_name($stmt, ":customer_id", $customer_id);
        oci_bind_by_name($stmt, ":address_id", $address_id);
        oci_bind_by_name($stmt, ":total_amount", $total_amount);
        oci_bind_by_name($stmt, ":comments", $comments);
        oci_bind_by_name($stmt, ":payment_method_id", $payment_method_id);
        oci_bind_by_name($stmt, ":created_by", $created_by);

        // Ejecutar el SP
        if (oci_execute($stmt)) {
            oci_commit($conn);
            $retorno = true;
        }

    } catch (Throwable $e) {
        echo "Error al ejecutar el SP: " . $e->getMessage();
    } finally {
        oci_free_statement($stmt);
        oci_close($conn);
    }

    return $retorno;
}

// Eliminar un linea de orden
function eliminarBilling($billing_id, $modified_by) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql_bill = "UPDATE FIDE_SAMDESIGN.fide_billing_tb 
                     SET 
                        status_id = 0,
                        modified_on = SYSDATE,
                        modified_by = :modified_by 
                     WHERE billing_id = :billing_id";
        $stmt_bill = oci_parse($oConexion, $sql_bill);
        oci_bind_by_name($stmt_bill, ":billing_id", $billing_id);
        oci_bind_by_name($stmt_bill, ":modified_by", $modified_by);

        if (oci_execute($stmt_bill)) {
            $retorno = true;
        }
    } catch (\Throwable $th) {
        echo $th;
    } finally {
        if (isset($stmt_bill)) oci_free_statement($stmt_bill);
        oci_close($oConexion);
    }

    return $retorno;
}


// Actualizar un carrito
function actualizarBilling($billing_id, $order_id, $customer_id, $address_id, $billing_date, $total_amount, $comments, $status_id, $payment_method_id, $modified_by) {
    $retorno = false;

    try {
        $conn = conectar();

        $sql = "BEGIN 
                    MODIFICAR_FIDE_BILLING_TB_SP(
                        :billing_id,
                        :order_id,
                        :customer_id,
                        :address_id,
                        :billing_date,
                        :total_amount,
                        :comments,
                        :status_id,
                        :payment_method_id,
                        :modified_by,
                        SYSTIMESTAMP
                    );
                END;";

        $stmt = oci_parse($conn, $sql);

        // Bind de parámetros
        oci_bind_by_name($stmt, ":billing_id", $billing_id);
        oci_bind_by_name($stmt, ":order_id", $order_id);
        oci_bind_by_name($stmt, ":customer_id", $customer_id);
        oci_bind_by_name($stmt, ":address_id", $address_id);
        oci_bind_by_name($stmt, ":billing_date", $billing_date); // puede ser string o timestamp
        oci_bind_by_name($stmt, ":total_amount", $total_amount);
        oci_bind_by_name($stmt, ":comments", $comments);
        oci_bind_by_name($stmt, ":status_id", $status_id);
        oci_bind_by_name($stmt, ":payment_method_id", $payment_method_id);
        oci_bind_by_name($stmt, ":modified_by", $modified_by);

        if (oci_execute($stmt)) {
            oci_commit($conn);
            $retorno = true;
        }

    } catch (Throwable $e) {
        echo "Error al actualizar la factura: " . $e->getMessage();
    } finally {
        oci_free_statement($stmt);
        oci_close($conn);
    }

    return $retorno;
}

// Manejar acciones desde solicitudes POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    $action = $_POST["action"];

    // 1. Eliminar
    if ($action == "eliminar" && isset($_POST["billing_id"])) {
        $billing_id = $_POST["billing_id"];
        $modified_by = $_POST["modified_by"];
        $eliminado = eliminarBilling($billing_id, $modified_by);
        echo $eliminado ? "success" : "Error al eliminar la línea de facturación";

    // 2. Obtener detalles
    } elseif ($action == "obtenerDetalles" && isset($_POST["billing_id"])) {
        $billing_id = $_POST["billing_id"];
        error_log("Obteniendo detalles para ID: " . $billing_id);
        $detalles = obtenerDetallesBilling($billing_id);
        if (isset($detalles['error'])) {
            http_response_code(404);
        }
        echo json_encode($detalles);

    // 3. Actualizar usando el SP
    } elseif ($action == "actualizar") {
        error_log("Datos recibidos para actualizar: " . json_encode($_POST));

        $required_fields = ["billing_id", "order_id", "customer_id", "address_id", "billing_date", "total_amount", "comments", "status_id", "payment_method_id", "modified_by"];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                http_response_code(400);
                echo "El campo $field es requerido.";
                exit;
            }
        }

        $actualizado = actualizarBilling(
            $_POST["billing_id"],
            $_POST["order_id"],
            $_POST["customer_id"],
            $_POST["address_id"],
            $_POST["billing_date"], 
            $_POST["total_amount"],
            $_POST["comments"],
            $_POST["status_id"],
            $_POST["payment_method_id"],
            $_POST["modified_by"]
        );

        if ($actualizado) {
            echo "success";
        } else {
            http_response_code(500);
            echo "Error al actualizar la línea de facturación. Verifica los logs.";
        }

    // 4. Insertar usando SP
    } elseif ($action == "insertar") {
        $required_fields = ["order_id", "customer_id", "address_id", "total_amount", "comments", "payment_method_id", "created_by"];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                http_response_code(400);
                echo "El campo $field es requerido.";
                exit;
            }
        }

        $insertado = IngresarBilling(
            $_POST["order_id"],
            $_POST["customer_id"],
            $_POST["address_id"],
            $_POST["total_amount"],
            $_POST["comments"],
            $_POST["payment_method_id"],
            $_POST["created_by"]
        );

        if ($insertado) {
            echo "success";
        } else {
            http_response_code(500);
            echo "Error al insertar la línea de facturación";
        }

    // Acción no reconocida
    } else {
        http_response_code(400);
        echo "Acción no válida";
    }
}