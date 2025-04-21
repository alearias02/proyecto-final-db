<?php
session_start();
require_once '../../DAL/conexion.php';
$conn = conectar();
$USER = $_SESSION['usuario']['user_name'] ?? 'sistema';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["actualizar_linea"])) {
        $cart_line_id = $_POST["actualizar_linea"];
        $cantidades = $_POST["cantidades"];
        $nueva_cantidad = intval($cantidades[$cart_line_id]);

        $sql = "UPDATE FIDE_SAMDESIGN.FIDE_CART_LINES_TB 
                SET Qty_Item = :qty_item, 
                    Modified_On = SYSDATE,
                    Modified_By = :modified_by 
                WHERE Cart_Line_ID = :cart_line_id";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":qty_item", $nueva_cantidad);
        oci_bind_by_name($stmt, ":modified_by", $USER);
        oci_bind_by_name($stmt, ":cart_line_id", $cart_line_id);

        if (oci_execute($stmt)) {
            oci_commit($conn);
        } else {
            $e = oci_error($stmt);
            error_log("Error Oracle: " . $e['message']);
        }

        oci_free_statement($stmt);

    } else if (isset($_POST["eliminar_linea"])){
        $cart_line_id = $_POST["eliminar_linea"];

        $SQL = "BEGIN 
                    ELIMINAR_FIDE_CART_LINES_TB_SP(
                        :cart_line_id,
                        :modified_by,
                        SYSTIMESTAMP);
                END;";
        $stmt = oci_parse($conn, $SQL);
        oci_bind_by_name($stmt, ":modified_by", $USER);
        oci_bind_by_name($stmt, ":cart_line_id", $cart_line_id);

        if (oci_execute($stmt)) {
            oci_commit($conn);
        } else {
            $e = oci_error($stmt);
            error_log("Error Oracle: " . $e['message']);
        }

        oci_free_statement($stmt);

    }
    
}

desconectar($conn);
header("Location: ../../src/carrito.php");
exit;
