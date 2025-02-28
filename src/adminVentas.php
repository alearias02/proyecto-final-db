<?php
session_start();
//Si no es ADMIN redirige a index
if(!(isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'ROLE_ADMIN')) {
    header("Location: index.php");
    exit(); 
}
session_abort();
?>

<?php
require_once "../include/templates/header.php";
require_once "../DAL/ventas.php";

$query = "select * from venta";
$oVentas = getArray($query);
// If there are camisas, display them in a table
if ($oVentas != null && !empty($oVentas)) {
    echo "<div class='content-container mt-4'>
            <div class='container'>
                <div class='row'>
                    <div class='col-md-12'>
                        <div class='card mb-4'>
                            <div class='card-header d-flex justify-content-between align-items-center'>
                                <h4 class='text-center'>Ventas</h4>
                            </div>
                            <div class='card-body cardAdmin'>
                                <div class='table-responsive'>
                                    <table class='table table-striped'>
                                        <thead class='table-dark'>
                                            <tr>
                                                <th>#</th>
                                                <th>Factura</th>
                                                <th>Producto</th>
                                                <th>Descripcion</th>
                                                <th>Precio</th>
                                                <th>Cantidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>";
    // Loop through each camisa and create a row for it
    foreach ($oVentas as $value) {
        echo "<tr>
                <td>{$value['id_venta']}</td>
                <td>{$value['id_factura']}</td>
                <td>{$value['id_producto']}</td>
                <td>{$value['descripcion']}</td>
                <td >{$value['precio']}</td>
                <td>{$value['cantidad']}</td>
            </tr>";
    }
    echo "</tbody>
        </table>
    </div>
</div>
</div>
</div>
</div>
</div>";
} else {
    echo "<p>No hay ventas</p>";
}

require_once "../include/templates/footer.php";
?>

