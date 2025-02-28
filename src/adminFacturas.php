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
require_once "../DAL/facturas.php";

$query = "select * from factura";
$oFacturas = getArray($query);
// If there are camisas, display them in a table
if ($oFacturas != null && !empty($oFacturas)) {
    echo "<div class='content-container mt-4'>
            <div class='container'>
                <div class='row'>
                    <div class='col-md-12'>
                        <div class='card mb-4'>
                            <div class='card-header d-flex justify-content-between align-items-center'>
                                <h4 class='text-center'>Facturas</h4>
                            </div>
                            <div class='card-body cardAdmin'>
                                <div class='table-responsive'>
                                    <table class='table table-striped'>
                                        <thead class='table-dark'>
                                            <tr>
                                                <th>#</th>
                                                <th>id_usuario</th>
                                                <th>Fecha</th>
                                                <th>Total</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>";

    foreach ($oFacturas as $value) {
        echo "<tr>
                <td>{$value['id_factura']}</td>
                <td>{$value['id_usuario']}</td>
                <td>{$value['fecha']}</td>
                <td>{$value['total']}</td>
                <td>{$value['estado']}</td>
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
    echo "<p>No hay facturas</p>";
}

require_once "../include/templates/footer.php";
?>

