<?php
session_start();
require_once '../../DAL/conexion.php';
$conn = conectar();
$USER = $_SESSION['usuario']['user_name'] ?? 'sistema';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["payment_method_id"], $_POST["customer_id"])) {
    $customer_id = $_POST["customer_id"];
    $payment_method_id = $_POST["payment_method_id"];

    // Obtener el cart_id activo
    $sql_cart = "SELECT Cart_ID FROM FIDE_SAMDESIGN.FIDE_CART_TB 
                 WHERE Customer_ID = :customer_id AND Status_ID = 1 FETCH FIRST 1 ROWS ONLY";
    $stmt_cart = oci_parse($conn, $sql_cart);
    oci_bind_by_name($stmt_cart, ":customer_id", $customer_id);
    oci_execute($stmt_cart);
    $cart = oci_fetch_assoc($stmt_cart);

    if ($cart) {
        $cart_id = $cart['CART_ID'];

        // Actualizar el carrito con el método de pago
        $sql_update = "UPDATE FIDE_SAMDESIGN.FIDE_CART_TB 
                       SET Payment_Method_ID = :payment_method_id,
                           Modified_On = SYSDATE,
                           Modified_By = :modified_by 
                       WHERE Cart_ID = :cart_id";
        $stmt_update = oci_parse($conn, $sql_update);
        oci_bind_by_name($stmt_update, ":payment_method_id", $payment_method_id);
        oci_bind_by_name($stmt_update, ":cart_id", $cart_id);
        oci_bind_by_name($stmt_update, ":modified_by", $USER);
        oci_execute($stmt_update);
        oci_commit($conn);
    }

    oci_free_statement($stmt_cart);
    if (isset($stmt_update)) oci_free_statement($stmt_update);
}

desconectar($conn);
header("Location: ../../src/carrito.php");
exit;
