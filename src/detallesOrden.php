<?php
require_once "../DAL/order.php";
require_once "../include/templates/header.php";

if (!isset($_GET['order_id'])) {
    echo "<p class='text-danger text-center'>No se proporcionó una orden válida.</p>";
    exit;
}

$order_id = $_GET['order_id'];
$orden = obtenerOrdenPorId($order_id);
$lineas = obtenerLineasOrden($order_id);
?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Detalles de la Orden #<?= htmlspecialchars($order_id) ?></h2>
    
    <?php if ($orden): ?>
        <div class="card mb-4">
            <div class="card-body">
                <a href="../userSrc/userOrders.php"><button type="button" class="btn" style="color: var(--primarioOscuro) !important;"><</button></a>
                <p><strong>Cliente:</strong> <?= htmlspecialchars($orden['CUSTOMER_NAME']) ?></p>
                <p><strong>Fecha:</strong> <?= htmlspecialchars($orden['ORDER_DATE']) ?></p>
                <p><strong>Monto Total:</strong> $<?= number_format($orden['ORDER_AMOUNT'], 2) ?></p>
                <p><strong>Método de Pago:</strong> <?= htmlspecialchars($orden['PAYMENT_METHOD_NAME']) ?></p>
                <p><strong>Estado:</strong> <?= htmlspecialchars($orden['STATUS_DESCRIPTION']) ?></p>
            </div>
        </div>

        <h4 class="mb-3">Productos en esta Orden</h4>
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID Línea</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lineas as $linea): ?>
                        <tr>
                            <td><?= $linea['ORDER_LINE_ID'] ?></td>
                            <td><?= $linea['DESCRIPTION'] ?></td>
                            <td><?= $linea['QTY_ITEM'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <a href="adminOrdenes.php" class="btn btn-secondary">Volver a Órdenes</a>
        </div>

    <?php else: ?>
        <p class="text-danger text-center">No se encontraron datos para esta orden.</p>
    <?php endif; ?>
</div>

<?php include_once "../include/templates/footer.php"; ?>