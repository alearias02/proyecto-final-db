<?php
$page = "mostrar";
require_once "../include/templates/header.php";
require_once "../include/functions/recoge.php";
require_once "../DAL/camisas.php"; // Cambiar la ruta según corresponda

$errores = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = recogeGet("id_camisa"); // Cambiar el nombre del parámetro según corresponda
    // Sanitización
    $elSQL = "SELECT id_camisa, descripcion, detalle, talla, precio, existencias, ruta_imagen, activo FROM camisa WHERE id_camisa = $id"; // Cambiar el nombre de la tabla según corresponda
    $oCamisa = getObject($elSQL);

    if($oCamisa == null) {
        $errores[] = "Camisa no encontrada";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = recogePost("id_camisa"); // Cambiar el nombre del parámetro según corresponda

    if ($id == "") {
        $errores[] = "Identificador de camisa incorrecto";
    }

    if (empty($errores)) {
        // Operación de eliminación en base de datos
        if (EliminarCamisa($id)) { // Cambiar el nombre de la función según corresponda
            header("Location: camisaMain.php"); // Cambiar la ruta según corresponda
        } else {
            $errores[] = "Ocurrió un error al intentar eliminar la camisa en base de datos";
        }
    }
}
?>

<section class="my-3">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="card">
                    <img class="card-img-top" src="<?php echo $oCamisa['ruta_imagen']; ?>" alt="Camisa a la venta">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $oCamisa['descripcion']; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="card">
                    <div class="card-body">
                        <p class="card-text"><?php echo $oCamisa['precio'] . " - " . $oCamisa['existencias']; ?></p>
                        <p><?php echo $oCamisa['detalle']; ?></p>
                        <select class="form-control mb-3" id="talla">
                            <option>Xtra Small</option>
                            <option>Small</option>
                            <option>Medium</option>
                            <option>Large</option>
                            <option>Xtra Large</option>
                        </select>
                        <button class="btn btn-primary" id="addCar" onclick="agregarAlCarrito('<?php echo $oCamisa['id_camisa']; ?>',
                                                                                                '<?php echo $oCamisa['ruta_imagen']; ?>',
                                                                                                '<?php echo $oCamisa['descripcion']; ?>',
                                                                                                '<?php echo $oCamisa['precio']; ?>')">Agregar al carrito</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once "../include/templates/footer.php";
?>
