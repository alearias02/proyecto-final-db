<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['usuario']['customer_id']) || !isset($_SESSION['usuario']['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Sesión expirada o no válida.']);
    exit;
}


header('Content-Type: application/json');

require_once '../../DAL/conexion.php';

$conn = conectar();
if (!$conn) {
    echo json_encode(['error' => 'Error al conectar con la base de datos']);
    exit;
}

if (!isset($_POST['product_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de producto faltante']);
    desconectar($conn);
    exit;
}

$product_id = $_POST['product_id'];
$customer_id = $_SESSION['usuario']['customer_id'] ?? null;
$USER = $_SESSION['usuario']['user_name'] ?? null;
$cart_id = null;


// ENCONTRAR DIRECCION RELACIONADA AL USER.
$sqlAddy = "SELECT Address_ID FROM FIDE_SAMDESIGN.FIDE_ADDRESS_TB 
        WHERE ID_Customer = :customer_id AND Status_ID = 1
        FETCH FIRST 1 ROWS ONLY";

$stmtAddy = oci_parse($conn, $sqlAddy);
oci_bind_by_name($stmtAddy, ":customer_id", $customer_id);
oci_execute($stmtAddy);
$Addy = oci_fetch_assoc($stmtAddy);


if ($Addy) {
    $address_id = $Addy['ADDRESS_ID'];
}

// Buscar carrito activo
$sql = "SELECT Cart_ID FROM FIDE_SAMDESIGN.FIDE_CART_TB 
        WHERE Customer_ID = :customer_id AND Status_ID = 1
        FETCH FIRST 1 ROWS ONLY";

$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":customer_id", $customer_id);
oci_execute($stmt);
$row = oci_fetch_assoc($stmt);

if ($row) {
    $cart_id = $row['CART_ID'];
} else {
    // 4. Crear nuevo carrito
    $sql = "INSERT INTO FIDE_SAMDESIGN.FIDE_CART_TB 
            (Cart_ID, Customer_ID, Address_ID, Order_Date, Status_ID, Created_On, Created_By)
            VALUES (FIDE_CART_SEQ.NEXTVAL, :customer_id, :address_id, TRUNC(SYSTIMESTAMP), 1, SYSTIMESTAMP, :created_by)
            RETURNING Cart_ID INTO :new_cart_id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":customer_id", $customer_id);
    oci_bind_by_name($stmt, ":address_id", $address_id);
    oci_bind_by_name($stmt, ":created_by", $USER);
    oci_bind_by_name($stmt, ":new_cart_id", $cart_id, 32);
    $result = oci_execute($stmt, OCI_NO_AUTO_COMMIT);

    if (!$result) {
        oci_rollback($conn);
        $e = oci_error($stmt);
        desconectar($conn);
        echo json_encode(['error' => 'Error creando el carrito: ' . $e['message']]);
        exit;
    }
}

// 5. Buscar si ya existe el producto en el carrito
$sql = "SELECT Cart_Line_ID, Qty_Item FROM FIDE_SAMDESIGN.FIDE_CART_LINES_TB 
        WHERE Cart_ID = :cart_id AND Product_ID = :product_id";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":cart_id", $cart_id);
oci_bind_by_name($stmt, ":product_id", $product_id);
oci_execute($stmt);
$line = oci_fetch_assoc($stmt);

if ($line) {
    // 6. Actualizar cantidad
    $new_qty = $line['QTY_ITEM'] + 1;
    $sql = "UPDATE FIDE_SAMDESIGN.FIDE_CART_LINES_TB 
            SET Qty_Item = :qty, Modified_On = SYSTIMESTAMP, Modified_By = :mod_by 
            WHERE Cart_Line_ID = :line_id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":qty", $new_qty);
    oci_bind_by_name($stmt, ":mod_by", $USER);
    oci_bind_by_name($stmt, ":line_id", $line['CART_LINE_ID']);
    oci_execute($stmt, OCI_NO_AUTO_COMMIT);
} else {
    // 7. Obtener precio del producto
    $sql = "SELECT unit_price FROM FIDE_SAMDESIGN.FIDE_PRODUCT_TB WHERE Product_ID = :product_id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":product_id", $product_id);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    $price = $row ? $row['UNIT_PRICE'] : 0;
    $price_with_tax = round($price * 1.13, 2);
    $talla = $_POST['talla'] ?? 'No especificado';

    // 8. Insertar nueva línea en el carrito
    $sql = "INSERT INTO FIDE_SAMDESIGN.FIDE_CART_LINES_TB 
            (Cart_Line_ID, Cart_ID, Product_ID, Qty_Item, Total_Price, Comments, Created_On, Created_By, Status_ID)
            VALUES (FIDE_CART_LINE_SEQ.NEXTVAL, :cart_id, :product_id, 1, :price, :comments, SYSTIMESTAMP, :created_by, 1)";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":cart_id", $cart_id);
    oci_bind_by_name($stmt, ":product_id", $product_id);
    oci_bind_by_name($stmt, ":price", $price_with_tax);
    oci_bind_by_name($stmt, ":comments", $talla); 
    oci_bind_by_name($stmt, ":created_by", $USER);
    oci_execute($stmt, OCI_NO_AUTO_COMMIT);
}

// 9. Confirmar la transacción
oci_commit($conn);

// 10. Respuesta al frontend
echo json_encode(['success' => true, 'cart_id' => $cart_id]);

// 11. Cerrar conexión
desconectar($conn);
exit;
?>
