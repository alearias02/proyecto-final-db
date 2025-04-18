<?php
require_once '../DAL/conexion.php';
$conn = conectar();
session_start();

$customer_id = $_SESSION['usuario']['customer_id'] ?? null;

if (!$customer_id) {
    die("Usuario no autenticado.");
}

// 1. Obtener información del carrito
$sql = "SELECT c.Cart_ID, c.Payment_Method_ID, a.Address_ID
        FROM FIDE_SAMDESIGN.FIDE_CART_TB c
        JOIN FIDE_SAMDESIGN.FIDE_ADDRESS_TB a ON a.ID_Customer = c.Customer_ID AND a.Status_ID = 1
        WHERE c.Customer_ID = :customer_id AND c.Status_ID = 1
        FETCH FIRST 1 ROWS ONLY";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":customer_id", $customer_id);
oci_execute($stmt);
$cart_info = oci_fetch_assoc($stmt);

if (!$cart_info) {
    die("No se encontró un carrito activo.");
}

$cart_id = $cart_info['CART_ID'];
$payment_method_id = $cart_info['PAYMENT_METHOD_ID'];
$address_id = $cart_info['ADDRESS_ID'];

// 2. Obtener líneas del carrito
$carrito = [];
$total_general = 0;

$sql = "SELECT Product_ID, Qty_Item, Total_Price, (Qty_Item * Total_Price) AS Subtotal
        FROM FIDE_SAMDESIGN.FIDE_CART_LINES_TB
        WHERE Cart_ID = :cart_id";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":cart_id", $cart_id);
oci_execute($stmt);
while ($row = oci_fetch_assoc($stmt)) {
    $carrito[] = $row;
    $total_general += $row['SUBTOTAL'];
}

// 3. Insertar encabezado de la orden
$created_by = $_SESSION['usuario']['user_name'] ?? 'system';

$order_id = null;
$sql = "INSERT INTO FIDE_SAMDESIGN.FIDE_ORDER_TB (
            Customer_ID, Order_Date, Order_Amount, 
            Order_Tax, Status_ID, Payment_Method_ID, 
            Created_On, Created_By, Address_ID
        ) VALUES (
            :customer_id, SYSTIMESTAMP, :amount, 
            :tax, 8, :payment_method_id, 
            SYSTIMESTAMP, :created_by, :address_id
        ) RETURNING Order_ID INTO :order_id";

$stmt = oci_parse($conn, $sql);
$tax = $total_general * 0.13;
oci_bind_by_name($stmt, ":customer_id", $customer_id);
oci_bind_by_name($stmt, ":amount", $total_general);
oci_bind_by_name($stmt, ":tax", $tax);
oci_bind_by_name($stmt, ":payment_method_id", $payment_method_id);
oci_bind_by_name($stmt, ":created_by", $created_by);
oci_bind_by_name($stmt, ":address_id", $address_id);
oci_bind_by_name($stmt, ":order_id", $order_id, 100); 
oci_execute($stmt);

$sqlBilling = "INSERT INTO FIDE_SAMDESIGN.FIDE_BILLING_TB (
    Billing_ID, Order_ID, Customer_ID, Invoiced_Address_ID, 
    Billing_Date, Total_Amount, Status_ID, Payment_Method_ID, 
    Created_On, Created_By 
) VALUES (
    FIDE_BILLING_SEQ.NEXTVAL, :order_id, :customer_id, :address_id, 
    SYSTIMESTAMP, :amount, 1, :payment_method_id, SYSTIMESTAMP, :created_by )";

$stmtB = oci_parse($conn, $sqlBilling);
oci_bind_by_name($stmtB, ":customer_id", $customer_id);
oci_bind_by_name($stmtB, ":amount", $total_general);
oci_bind_by_name($stmtB, ":payment_method_id", $payment_method_id);
oci_bind_by_name($stmtB, ":created_by", $created_by);
oci_bind_by_name($stmtB, ":address_id", $address_id);
oci_bind_by_name($stmtB, ":order_id", $order_id); 
oci_execute($stmtB);


// 4. Insertar líneas de orden
foreach ($carrito as $item) {
    echo "<script>console.log(" . json_encode($item) . ");</script>";

    $sql = "INSERT INTO FIDE_SAMDESIGN.FIDE_ORDER_LINES_TB (
                Order_Line_ID, Order_ID, Product_ID, Qty_Item,
                Total_Price, Status_ID, Created_On, Created_By
            ) VALUES (
                FIDE_ORDER_LINE_SEQ.NEXTVAL, :order_id, :product_id, :qty,
                :price, 1, SYSTIMESTAMP, :created_by)";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":order_id", $order_id);
    oci_bind_by_name($stmt, ":product_id", $item['PRODUCT_ID']);
    oci_bind_by_name($stmt, ":qty", $item['QTY_ITEM']);
    oci_bind_by_name($stmt, ":price", $item['SUBTOTAL']);
    oci_bind_by_name($stmt, ":created_by", $created_by);

    if (!oci_execute($stmt)) {
        $e = oci_error($stmt);
        echo "<script>console.error('Error al insertar línea de orden: " . $e['message'] . "');</script>";
    }
}



// 5. Actualizar estado del carrito
$sql = "UPDATE FIDE_SAMDESIGN.FIDE_CART_TB 
        SET Status_ID = 9, Modified_On = SYSTIMESTAMP, Modified_By = :modified_by
        WHERE Cart_ID = :cart_id";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":modified_by", $created_by);
oci_bind_by_name($stmt, ":cart_id", $cart_id);
oci_execute($stmt);

oci_commit($conn);
desconectar($conn);

header("Location: ordenConfirmada.php?order_id=" . $order_id);
exit;
?>