<?php
require_once '../DAL/conexion.php';
require_once '../include/templates/header.php';
$conn = conectar();

$customer_id = $_SESSION['usuario']['customer_id'] ?? null;
if (!$customer_id) {
    die("Usuario no autenticado.");
}

$sql = "SELECT 
            o.Order_ID,
            o.Order_Date,
            o.Order_Amount,
            o.Order_Tax,
            p.Payment_Method_Name,
            s.Description AS Status,
            b.Billing_ID as FACTURA
        FROM FIDE_SAMDESIGN.FIDE_ORDER_TB o
        LEFT JOIN FIDE_SAMDESIGN.FIDE_PAYMENT_METHOD_TB p ON p.Payment_Method_ID = o.Payment_Method_ID
        LEFT JOIN FIDE_SAMDESIGN.FIDE_STATUS_TB s ON s.Status_ID = o.Status_ID
        LEFT JOIN FIDE_SAMDESIGN.FIDE_BILLING_TB b ON b.Order_ID = o.Order_ID
        WHERE o.Customer_ID = :customer_id
        ORDER BY o.Order_Date DESC";

$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":customer_id", $customer_id);
oci_execute($stmt);

$ordenes = [];
while ($row = oci_fetch_assoc($stmt)) {
    $ordenes[] = $row;
}

desconectar($conn);
?>

<div class="container mt-5 mb-5">
    <h2 class="text-center mb-4">📦 Mis Órdenes</h2>

    <?php if (empty($ordenes)): ?>
        <div class="alert alert-info text-center">No has realizado ninguna orden todavía.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-secondary text-center">
                    <tr>
                        <th>ID de Orden</th>
                        <th># de Factura</th>
                        <th>Fecha</th>
                        <th>Monto Total</th>
                        <th>IVA</th>
                        <th>Método de Pago</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php foreach ($ordenes as $orden): ?>
                        <tr>
                            <td><?= htmlspecialchars($orden['ORDER_ID']) ?></td>
                            <td><?= htmlspecialchars($orden['FACTURA']) ?></td>
                            <td><?= htmlspecialchars($orden['ORDER_DATE']) ?></td>
                            <td>$<?= number_format($orden['ORDER_AMOUNT'], 2) ?></td>
                            <td>$<?= number_format($orden['ORDER_TAX'], 2) ?></td>
                            <td><?= htmlspecialchars($orden['PAYMENT_METHOD_NAME']) ?></td>
                            <td><span class="badge bg-info text-dark"><?= htmlspecialchars($orden['STATUS']) ?></span></td>
                            <td>
                                <a href="../src/detallesOrden.php?order_id=<?= urlencode($orden['ORDER_ID']) ?>" class="btn btn-sm btn-outline-primary">
                                    Ver Detalle
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../include/templates/footer.php'; ?>
