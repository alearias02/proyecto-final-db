<?php
require_once "../DAL/order.php";
require_once "../include/templates/header.php";

$ordenes = obtenerOrdenes();
$estados = obtenerEstados();
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Órdenes Ingresadas</h2>

    <?php if (count($ordenes) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th># Orden</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
<?php foreach ($ordenes as $orden): ?>
    <tr>
        <td><?= $orden['ORDER_ID'] ?></td>
        <td><?= $orden['CUSTOMER_NAME'] ?></td>
        <td><?= date('Y-m-d', strtotime($orden['ORDER_DATE'])) ?></td>
        <td>$<?= number_format($orden['ORDER_AMOUNT'], 2) ?></td>
        <td>
            <form method="POST" action="actualizarEstado.php" class="d-flex justify-content-center">
                <input type="hidden" name="order_id" value="<?= $orden['ORDER_ID'] ?>">
                <select name="status_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <?php foreach ($estados as $estado): ?>
                        <option value="<?= $estado['STATUS_ID'] ?>" <?= $orden['STATUS_ID'] == $estado['STATUS_ID'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($estado['DESCRIPTION']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </td>
        <td>
            <a href="detallesOrden.php?order_id=<?= $orden['ORDER_ID'] ?>" class="btn btn-info btn-sm">Ver Detalles</a>
        </td>
    </tr>
<?php endforeach; ?>
</tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-center">No hay órdenes registradas.</p>
    <?php endif; ?>
</div>

<?php include_once "../include/templates/footer.php"; ?>