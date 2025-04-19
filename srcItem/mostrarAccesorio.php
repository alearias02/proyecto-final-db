<?php
$page = "mostrar";
require_once "../include/templates/header.php";
require_once "../include/functions/recoge.php";
require_once "../DAL/productos.php"; // archivo donde están las funciones con Oracle

$errores = [];
$oProducto = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $product_id = recogeGet("product_id");

    if ($product_id == "") {
        $errores[] = "ID de producto no válido";
    } else {
        $oProducto = obtenerDetallesProducto($product_id);

        if (isset($oProducto["error"])) {
            $errores[] = $oProducto["error"];
        }
    }
}
?>

<section class="my-3">
    <div class="container">
        <?php if (!empty($errores)) : ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errores as $error) : ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($oProducto) && empty($errores)) : ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <img class="card-img-top" src="<?= $oProducto['Image_path']; ?>" alt="Imagen del producto">
                        <div class="card-body" >
                            <h5 class="card-title"><?= $oProducto['Description']; ?></h5>
                            <p class="card-text">Precio: $<?= number_format($oProducto['Unit_price'], 2); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title"><?= $oProducto['Description']; ?></h2>
                            <p class="card-text">Precio: $<?= number_format($oProducto['Unit_price'], 2); ?></p>
                            <p><?= $oProducto['Comments']; ?></p>

                            <button class="btn btn-primary"
                                onclick="agregarAlCarrito('<?= $oProducto['Product_ID']; ?>',
                                                          '<?= $oProducto['Image_path']; ?>',
                                                          '<?= $oProducto['Description']; ?>',
                                                          '<?= $oProducto['Unit_price']; ?>')">
                                Agregar al carrito
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.card-img-top {
  object-fit: cover;
  height: 400px;
  border-radius: 12px 12px 0 0;
}

/* Card refinada */
.card {
  border: none;
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.1);
  transition: transform 0.3s ease-in-out;
}

.card:hover {
  transform: scale(1.01);
}

/* Botón */
.btn-primary {
  background-color: #4a90e2;
  border: none;
  font-weight: bold;
  transition: background-color 0.3s ease;
}

.btn-primary:hover {
  background-color: #357ABD;
}

/* Precio y descripción */
.card-text {
  color: #333;
}

.card-title {
  font-size: 2rem;
  font-weight: bold;
}

/* Select personalizado */
select.form-control {
  border-radius: 8px;
  border: 1px solid #ccc;
  padding: 0.5rem;
}

/* Espaciado general */
.container {
  padding-top: 2rem;
  padding-bottom: 2rem;
}
</style>


<?php require_once "../include/templates/footer.php"; ?>

<script>
function agregarAlCarrito(productId, imagePath, description, unitPrice) {
  $.ajax({
    url: '../include/functions/addToCart.php',
    type: 'POST',
    dataType: 'json',
    data: {
      product_id: productId
    },
    success: function (response) {
      if (response.success) {
        alert("Producto agregado al carrito 🛒");
        console.log("Carrito ID:", response.cart_id);
      } else if (response.error) {
        alert("Error: " + response.error);
      }
    },
    error: function (xhr, status, error) {
      console.error("Error AJAX:", error);
      alert("Hubo un error al agregar el producto.");
    }
  });
}
</script>
