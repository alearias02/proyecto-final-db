<?php
$page = "mostrar";
require_once "../include/templates/header.php";
require_once "../include/functions/recoge.php";
require_once "../DAL/impermeables.php";

$errores = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = recogeGet("id_impermeable");
    //sanitización
    $elSQL = "select id_impermeable, descripcion, detalle, talla, precio, existencias, ruta_imagen, activo from impermeable where id_impermeable = $id";
    $oImpermeable = getObject($elSQL);

    if($oImpermeable == null) {
        $errores[] = "Impermeable no encontrado";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = recogePost("id_impermeable");

    if ($id == "") {
        $errores[] = "Identificador de impermeable incorrecto";
    }

    if (empty($errores)) {
        // operación de eliminación en base de datos
        if (EliminarImpermeable($id)) {
            header("Location: impermeableMain.php");
        } else {
            $errores[] = "Ocurrió un error al intentar eliminar el impermeable en base de datos";
        }
    }
}
?>

<section class="my-3">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="card">
                    <img class="card-img-top" src="<?php echo $oImpermeable['ruta_imagen']; ?>" alt="Impermeable a la venta">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $oImpermeable['descripcion']; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="card">
                    <div class="card-body">
                        <p class="card-text"><?php echo $oImpermeable['precio'] . " - " . $oImpermeable['existencias']; ?></p>
                        <p><?php echo $oImpermeable['detalle']; ?></p>
                        <select class="form-control mb-3" id="talla">
                            <option>Xtra Small</option>
                            <option>Small</option>
                            <option>Medium</option>
                            <option>Large</option>
                            <option>Xtra Large</option>
                        </select>
                        <button class="btn btn-primary" id="addCar" onclick="agregarAlCarrito('<?php echo $oImpermeable['id_impermeable']; ?>',
                                                                                                '<?php echo $oImpermeable['ruta_imagen']; ?>',
                                                                                                '<?php echo $oImpermeable['descripcion']; ?>',
                                                                                                '<?php echo $oImpermeable['precio']; ?>')">Agregar al carrito</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once "../include/templates/footer.php";
?>
