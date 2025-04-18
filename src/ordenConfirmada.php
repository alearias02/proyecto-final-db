<?php
require_once '../DAL/conexion.php';
require_once '../include/templates/header.php';
$conn = conectar();

$order_id = $_GET['order_id'] ?? null;
if (!$order_id) {
    die("Orden no especificada.");
}

// Obtener datos de la orden
$sql = "SELECT o.Order_ID, o.Order_Date, o.Order_Amount, o.Order_Tax, p.Payment_Method_Name,
               a.Address, c.Name AS City, s.Name AS State, co.Name AS Country, a.ZIP_Code
        FROM FIDE_SAMDESIGN.FIDE_ORDER_TB o
        LEFT JOIN FIDE_SAMDESIGN.FIDE_PAYMENT_METHOD_TB p ON p.Payment_Method_ID = o.Payment_Method_ID
        LEFT JOIN FIDE_SAMDESIGN.FIDE_ADDRESS_TB a ON a.Address_ID = o.Address_ID
        LEFT JOIN FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB c ON c.City_ID = a.ID_City
        LEFT JOIN FIDE_SAMDESIGN.FIDE_STATE_ADDRESS_TB s ON s.State_ID = a.ID_State
        LEFT JOIN FIDE_SAMDESIGN.FIDE_COUNTRIES_TB co ON co.Country_ID = a.ID_Country
        WHERE o.Order_ID = :order_id";

$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":order_id", $order_id);
oci_execute($stmt);
$order = oci_fetch_assoc($stmt);

// Obtener líneas
$sql = "SELECT p.Description, l.Qty_Item, l.Total_Price, (l.Total_Price / l.Qty_Item) AS UNIT_PRICE  
        FROM FIDE_SAMDESIGN.FIDE_ORDER_LINES_TB l
        JOIN FIDE_SAMDESIGN.FIDE_PRODUCT_TB p ON p.Product_ID = l.Product_ID
        WHERE l.Order_ID = :order_id";

$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":order_id", $order_id);
oci_execute($stmt);

$lineas = [];
while ($row = oci_fetch_assoc($stmt)) {
    $lineas[] = $row;
}

desconectar($conn);
?>

<div class="container mt-5 mb-5">
  <div class="text-center mb-4">
    <h2>🎉 ¡Gracias por tu compra!</h2>
    <p class="text-success">Tu orden ha sido registrada exitosamente.</p>
  </div>

  <div class="card mb-4 shadow">
    <div class="card-header fw-bold bg-light">📄 Detalles de la Orden</div>
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><strong>ID de Orden:</strong> <?= htmlspecialchars($order['ORDER_ID']) ?></li>
      <li class="list-group-item"><strong>Fecha:</strong> <?= htmlspecialchars($order['ORDER_DATE']) ?></li>
      <li class="list-group-item"><strong>Monto Total:</strong> $<?= number_format($order['ORDER_AMOUNT'], 2) ?> (IVA: $<?= number_format($order['ORDER_TAX'], 2) ?>)</li>
      <li class="list-group-item"><strong>Método de Pago:</strong> <?= htmlspecialchars($order['PAYMENT_METHOD_NAME']) ?></li>
    </ul>
  </div>

  <div class="card mb-4 shadow">
    <div class="card-header fw-bold bg-light">📦 Dirección de Entrega</div>
    <div class="card-body">
      <?= htmlspecialchars($order['ADDRESS']) ?>, <?= htmlspecialchars($order['CITY']) ?>, <?= htmlspecialchars($order['STATE']) ?>, <?= htmlspecialchars($order['COUNTRY']) ?><br>
      Código Postal: <?= htmlspecialchars($order['ZIP_CODE']) ?>
    </div>
  </div>

  <div class="card shadow">
    <div class="card-header fw-bold bg-light">🛍️ Productos Comprados</div>
    <ul class="list-group list-group-flush">
      <?php foreach ($lineas as $linea): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <?= htmlspecialchars($linea['DESCRIPTION']) ?> 
          <span><strong><?= $linea['QTY_ITEM'] ?></strong> x $<?= number_format($linea['UNIT_PRICE'], 2) ?></span>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>

  <div class="text-center mt-4">
    <a href="index.php" class="btn btn-primary">
      <i class="fas fa-shopping-bag"></i> Seguir comprando
    </a>
  </div>
</div>

<?php require_once '../include/templates/footer.php'; ?>
