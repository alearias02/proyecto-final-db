
<?php
require_once "../include/templates/header.php";
require_once '../DAL/conexion.php';

$conn = conectar();
$customer_id = $_SESSION['usuario']['customer_id'] ?? null;
$cart_id = null;
$carrito = [];
$total_general = 0;

if ($customer_id) {
    // Obtener el carrito activo
    $sql = "SELECT Cart_ID FROM FIDE_SAMDESIGN.FIDE_CART_TB 
            WHERE Customer_ID = :customer_id AND Status_ID = 1
            FETCH FIRST 1 ROWS ONLY";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":customer_id", $customer_id);
    oci_execute($stmt);
    $cart = oci_fetch_assoc($stmt);
    $cart_id = $cart ? $cart['CART_ID'] : null;

    if ($cart_id) {
        // Obtener líneas del carrito
        $sql = "SELECT
                    l.Cart_Line_ID,
                    l.Product_ID,
                    p.Description,
                    p.Image_Path,
                    l.Qty_Item,
                    l.Total_Price,
                    (l.Qty_Item * l.Total_Price) AS Subtotal
                FROM FIDE_SAMDESIGN.FIDE_CART_LINES_TB l
                JOIN FIDE_SAMDESIGN.FIDE_PRODUCT_TB p ON p.Product_ID = l.Product_ID
                WHERE l.Cart_ID = :cart_id";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":cart_id", $cart_id);
        oci_execute($stmt);

        while ($row = oci_fetch_assoc($stmt)) {
            $carrito[] = $row;
            $total_general += $row['SUBTOTAL'];
        }
    }
}

desconectar($conn);
?>
<body>
<div class="container mt-4">
  <h2 class="mb-4 text-center">🛒 Mi Carrito</h2>

  <?php if (empty($carrito)): ?>
    <div class="alert alert-info text-center">Tu carrito está vacío.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-secondary text-center">
          <tr>
            <th>Imagen</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio c/IVA</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($carrito as $item): ?>
          <tr>
            <td class="text-center">
              <img src="<?= htmlspecialchars($item['IMAGE_PATH']) ?>" alt="Imagen" style="width: 80px; height: 80px; object-fit: cover;">
            </td>
            <td><?= htmlspecialchars($item['DESCRIPTION']) ?></td>
            <td class="text-center"><?= $item['QTY_ITEM'] ?></td>
            <td class="text-end">$<?= number_format($item['TOTAL_PRICE'], 2) ?></td>
            <td class="text-end fw-bold">$<?= number_format($item['SUBTOTAL'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="4" class="text-end fw-bold">Total:</td>
            <td class="text-end fw-bold text-success">$<?= number_format($total_general, 2) ?></td>
          </tr>
        </tfoot>
      </table>
    </div>

    <div class="text-end mt-3">
      <a href="checkout.php" class="btn btn-primary">
        <i class="fas fa-credit-card"></i> Finalizar compra
      </a>
    </div>
  <?php endif; ?>
</div>
</body>
<?php
require_once "../include/templates/footer.php";
?>