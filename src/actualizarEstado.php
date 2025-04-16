<?php
require_once "../DAL/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status_id = $_POST['status_id'];

    $conn = conectar();
    $sql = "UPDATE FIDE_ORDER_TB SET STATUS_ID = :status_id WHERE ORDER_ID = :order_id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":status_id", $status_id);
    oci_bind_by_name($stmt, ":order_id", $order_id);
    oci_execute($stmt);
    oci_free_statement($stmt);
    oci_close($conn);

    // Redirigir a la página correcta
    header("Location: adminOrdenes.php");
    exit();
}
?>