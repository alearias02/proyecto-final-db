
<?php
require_once "../include/templates/header.php";
require_once '../DAL/conexion.php';

$conn = conectar();
$customer_id = $_SESSION['usuario']['customer_id'] ?? null;

$direccion_cliente = null;

if ($customer_id) {
    $sql = "SELECT 
                a.Address_ID AS ID,
                a.Address AS Street,
                c.Name AS City,
                s.Name AS State,
                co.Name AS Country,
                a.ZIP_Code
            FROM FIDE_SAMDESIGN.FIDE_ADDRESS_TB a
            LEFT JOIN FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB c ON c.City_ID = a.ID_City
            LEFT JOIN FIDE_SAMDESIGN.FIDE_STATE_ADDRESS_TB s ON s.State_ID = a.ID_State
            LEFT JOIN FIDE_SAMDESIGN.FIDE_COUNTRIES_TB co ON co.Country_ID = a.ID_Country
            WHERE a.ID_Customer = :customer_id AND a.Status_ID = 10
            FETCH FIRST 1 ROWS ONLY";

    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":customer_id", $customer_id);
    oci_execute($stmt);
    $direccion_cliente = oci_fetch_assoc($stmt);
}

$metodos_pago = [];
$sql = "SELECT Payment_Method_ID, Payment_Method_Name 
        FROM FIDE_SAMDESIGN.FIDE_PAYMENT_METHOD_TB 
        WHERE Status_ID = 1";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);
while ($row = oci_fetch_assoc($stmt)) {
    $metodos_pago[] = $row;
}

$metodo_pago_actual = null;

$sql = "SELECT Payment_Method_ID 
        FROM FIDE_SAMDESIGN.FIDE_CART_TB 
        WHERE Customer_ID = :customer_id AND Status_ID = 1 FETCH FIRST 1 ROWS ONLY";

$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":customer_id", $customer_id);
oci_execute($stmt);
$row = oci_fetch_assoc($stmt);
if ($row && $row['PAYMENT_METHOD_ID']) {
    $metodo_pago_actual = $row['PAYMENT_METHOD_ID'];
}

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

  <div class="row">
    <!-- Columna izquierda: Dirección -->
    <div class="col-md-4 mb-4">
      <?php if ($direccion_cliente): ?>
        <div class="card mb-3">
          <div class="card-header bg-light fw-bold">
            <i class="fas fa-map-marker-alt text-danger"></i> Dirección registrada:
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Calle:</strong> <?= htmlspecialchars($direccion_cliente['STREET']) ?></li>
            <li class="list-group-item"><strong>Ciudad:</strong> <?= htmlspecialchars($direccion_cliente['CITY']) ?></li>
            <li class="list-group-item"><strong>Estado:</strong> <?= htmlspecialchars($direccion_cliente['STATE']) ?></li>
            <li class="list-group-item"><strong>País:</strong> <?= htmlspecialchars($direccion_cliente['COUNTRY']) ?></li>
            <li class="list-group-item"><strong>Código Postal:</strong> <?= htmlspecialchars($direccion_cliente['ZIP_CODE']) ?></li>
          </ul>
        </div>
      <?php endif; ?>

      <!-- Dropdown método de pago -->
      <form action="../include/functions/savePaymntMethCart.php" method="POST">
        <div class="card">
          <div class="card-header bg-light fw-bold">
            <i class="fas fa-credit-card text-primary"></i> Método de pago:
          </div>
          <div class="card-body">
            <select name="payment_method_id" class="form-select mb-2" required>
              <option value="">Seleccione un método</option>
              <?php foreach ($metodos_pago as $metodo): ?>
                <option value="<?= $metodo['PAYMENT_METHOD_ID'] ?>"
                  <?= ($metodo_pago_actual == $metodo['PAYMENT_METHOD_ID']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($metodo['PAYMENT_METHOD_NAME']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <input type="hidden" name="customer_id" value="<?= $customer_id ?>">
            <button type="submit" class="btn btn-success w-100">
              <i class="fas fa-save"></i> Guardar método de pago
            </button>
          </div>
        </div>
      </form>
    </div>


    <!-- Columna derecha: Carrito -->
    <div class="col-md-8">
      <?php if (empty($carrito)): ?>
        <div class="alert alert-info text-center">Tu carrito está vacío.</div>
      <?php else: ?>
        <div class="table-responsive">
          <form method="POST" action="../include/functions/actualizarCart.php">
            <table class="table table-bordered table-hover align-middle">
              <thead class="table-secondary text-center">
                <tr>
                  <th>Imagen</th>
                  <th>Producto</th>
                  <th>Cantidad</th>
                  <th>Precio c/IVA</th>
                  <th>Subtotal</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($carrito as $item): ?>
                  <tr>
                    <td class="text-center">
                      <img src="<?= htmlspecialchars($item['IMAGE_PATH']) ?>" alt="Imagen" style="width: 80px; height: 80px; object-fit: cover;">
                    </td>
                    <td><?= htmlspecialchars($item['DESCRIPTION']) ?></td>
                    <td class="text-center" style="max-width: 100px;">
                      <input type="number" name="cantidades[<?= $item['CART_LINE_ID'] ?>]" value="<?= $item['QTY_ITEM'] ?>" min="1" class="form-control form-control-sm text-center">
                    </td>
                    <td class="text-end">$<?= number_format($item['TOTAL_PRICE'], 2) ?></td>
                    <td class="text-end fw-bold">$<?= number_format($item['SUBTOTAL'], 2) ?></td>
                    <td class="text-center">
                      <button type="submit" name="actualizar_linea" value="<?= $item['CART_LINE_ID'] ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-sync"></i>
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="4" class="text-end fw-bold">Total:</td>
                  <td class="text-end fw-bold text-success">$<?= number_format($total_general, 2) ?></td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </form>
        </div>
        
        <div class="text-end mt-3">
          <a href="checkout.php" class="btn btn-primary">
            <i class="fas fa-credit-card"></i> Finalizar compra
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

</body>
<?php
require_once "../include/templates/footer.php";
?>