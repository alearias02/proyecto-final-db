<?php
require_once "../include/templates/header.php";
require_once "../DAL/database.php";

$customer_id = $_SESSION['usuario']['customer_id'] ?? null;
if (!$customer_id) {
    die("Error: Falta el ID del cliente.");
}

$connection = conectar();

// Obtener datos de dirección
$query = "SELECT a.*, co.NAME AS COUNTRY, s.NAME AS STATE, c.NAME AS CITY
          FROM FIDE_SAMDESIGN.FIDE_ADDRESS_TB a
          LEFT JOIN FIDE_SAMDESIGN.FIDE_COUNTRIES_TB co ON co.COUNTRY_ID = a.ID_COUNTRY
          LEFT JOIN FIDE_SAMDESIGN.FIDE_STATE_ADDRESS_TB s ON s.STATE_ID = a.ID_STATE
          LEFT JOIN FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB c ON c.CITY_ID = a.ID_CITY
          WHERE a.ID_CUSTOMER = :customer_id AND a.STATUS_ID = 1 OR a.STATUS_ID = 10
          ORDER BY a.ADDRESS_ID ASC";

$stmt = oci_parse($connection, $query);
oci_bind_by_name($stmt, ":customer_id", $customer_id);
oci_execute($stmt);

$addresses = [];
while ($row = oci_fetch_assoc($stmt)) {
    $addresses[] = $row;
}

$countries = fetchAll($connection, "SELECT COUNTRY_ID, NAME FROM FIDE_SAMDESIGN.FIDE_COUNTRIES_TB");
$provincias = fetchAll($connection, "SELECT STATE_ID, NAME FROM FIDE_SAMDESIGN.FIDE_STATE_ADDRESS_TB");
$cantones = fetchAll($connection, "SELECT CITY_ID, NAME FROM FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB");
oci_close($connection);
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Direcciones del Cliente #<?= htmlspecialchars($customer_id) ?></h4>
        <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAdd1">+ Nueva Dirección</button>
    </div>
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Dirección</th>
                <th>Provincia</th>
                <th>Cantón</th>
                <th>País</th>
                <th>ZIP</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($addresses as $addr): ?>
                <tr>
                    <td><?= $addr['ADDRESS_ID'] ?></td>
                    <td><?= htmlspecialchars($addr['ADDRESS']) ?></td>
                    <td><?= htmlspecialchars($addr['STATE']) ?></td>
                    <td><?= htmlspecialchars($addr['CITY']) ?></td>
                    <td><?= htmlspecialchars($addr['COUNTRY']) ?></td>
                    <td><?= htmlspecialchars($addr['ZIP_CODE']) ?></td>
                    <td>
                        <button class="btn btn-success editarDireccion" data-address-id="<?= $addr['ADDRESS_ID'] ?>">Editar</button>
                        <button class="btn btn-danger" onclick="eliminarDireccion(<?= $addr['ADDRESS_ID'] ?>)">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Offcanvas Add -->
<div class="offcanvas offcanvas-end" id="offcanvasAdd1" tabindex="-1">
    <div class="offcanvas-header bg-primary text-white">
        <h5 class="offcanvas-title">Agregar Dirección</h5>
        <button class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <form id="addAddressForm" class="offcanvas-body">
        <input type="hidden" name="action" value="insertar">
        <input type="hidden" name="ID_CUSTOMER" value="<?= $customer_id ?>">

        <label>Dirección:</label>
        <textarea name="ADDRESS" class="form-control mb-2" required></textarea>

        <label>Provincia:</label>
        <select name="ID_STATE" class="form-control mb-2" required>
            <?php foreach ($provincias as $p): ?>
                <option value="<?= $p['STATE_ID'] ?>"><?= $p['NAME'] ?></option>
            <?php endforeach; ?>
        </select>

        <label>Cantón:</label>
        <select name="ID_CITY" class="form-control mb-2" required>
            <?php foreach ($cantones as $c): ?>
                <option value="<?= $c['CITY_ID'] ?>"><?= $c['NAME'] ?></option>
            <?php endforeach; ?>
        </select>

        <label>País:</label>
        <select name="ID_COUNTRY" class="form-control mb-2" required>
            <?php foreach ($countries as $co): ?>
                <option value="<?= $co['COUNTRY_ID'] ?>"><?= $co['NAME'] ?></option>
            <?php endforeach; ?>
        </select>

        <label>ZIP:</label>
        <input name="ZIP_CODE" class="form-control mb-3" required>

        <button class="btn btn-success w-100" type="submit">Guardar</button>
    </form>
</div>

<!-- Script JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#addAddressForm').on('submit', function(e) {
    e.preventDefault();
    $.post('../DAL/address.php', $(this).serialize(), function(response) {
        if (response.includes("correctamente")) {
            alert("Dirección agregada con éxito");
            location.reload();
        } else {
            alert("Error: " + response);
        }
    });
});

$(document).on('click', '.editarDireccion', function() {
    const id = $(this).data('address-id');
    alert("Aquí va el offcanvas para editar: cargar datos por AJAX usando el ID " + id);
});

function eliminarDireccion(id) {
    if (confirm("Deseas eliminar esta dirección?")) {
        $.post('../DAL/address.php', {
            action: 'eliminar',
            address_id: id
        }, function(resp) {
            if (resp.includes("exitosamente")) {
                alert("Eliminado.");
                location.reload();
            } else {
                alert("Error: " + resp);
            }
        });
    }
}
</script>
