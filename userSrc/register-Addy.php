<?php
require_once "../DAL/database.php";
require_once "../include/templates/headerUser.php";
require_once "../include/functions/recoge.php";

// Obtener el ID del usuario desde la URL
$customer_id = $_GET['customer_id'] ?? '';
$user_email = $_GET['email'] ?? '';

// Verificar que los valores existan antes de continuar
if (!$customer_id) {
    die("Error: customer_id no recibido.");
}
if (!$user_email) {
    die("Error: email no recibido.");
}

// Conectar a la base de datos
$connection = conectar();

if (!$connection) {
    die("Error: No se pudo conectar a la base de datos.");
}

// Depuración
//var_dump($user_email);


$query = "SELECT USER_NAME FROM FIDE_SAMDESIGN.FIDE_USERS_TB WHERE user_email = :user_email";
$stmt = oci_parse($connection, $query);

if (!$stmt) {
    die("Error: Fallo al preparar la consulta SQL.");
}

oci_bind_by_name($stmt, ":user_email", $user_email, -1);

if (!oci_execute($stmt)) {
    die("Error: No se pudo ejecutar la consulta en Oracle.");
}

$user = oci_fetch_assoc($stmt);

if (!$user) {
    die("Error: No se encontró el usuario en la base de datos.");
}

$user_name = $user['USER_NAME']; 
// depuracion var_dump($user_name);

// Consultas para cargar listas de selección
$countries = fetchAll($connection, "SELECT COUNTRY_ID, NAME FROM FIDE_SAMDESIGN.FIDE_COUNTRIES_TB");
$provincias = fetchAll($connection, "SELECT STATE_ID, NAME FROM FIDE_SAMDESIGN.FIDE_STATE_ADDRESS_TB");
$cantones = fetchAll($connection, "SELECT CITY_ID, NAME FROM FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB");

?>

<!DOCTYPE html>
<html lang="es">
<body>
    <main class="main-login">
        <form id="addressForm">
            <div class="card-body">
                <h3>Completa tu dirección</h3>

                <div class="user-info">
                    <p><strong>Nombre:</strong> <?= htmlspecialchars($user_name); ?></p>
                </div>

                <!-- Campo oculto con el ID del usuario -->
                <input type="hidden" name="ID_CUSTOMER" id="ID_CUSTOMER" value="<?= htmlspecialchars($customer_id); ?>">
                <input type="hidden" name="action" value="insertar">

                <label for="ADDRESS">Dirección Específica</label>
                <textarea class="form-control mt-2" name="ADDRESS" id="ADDRESS" rows="3" required placeholder="Señas adicionales"></textarea>

                <label for="ID_CITY">Cantón:</label>
                <select class="form-control mt-2" name="ID_CITY" id="ID_CITY" required>
                    <option value="" disabled selected>Seleccione un cantón</option>
                    <?php foreach ($cantones as $canton): ?>
                        <option value="<?= $canton['CITY_ID']; ?>"><?= htmlspecialchars($canton['NAME']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="ID_STATE">Provincia:</label>
                <select class="form-control mt-2" name="ID_STATE" id="ID_STATE" required>
                    <option value="" disabled selected>Seleccione una provincia</option>
                    <?php foreach ($provincias as $provincia): ?>
                        <option value="<?= $provincia['STATE_ID']; ?>"><?= htmlspecialchars($provincia['NAME']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="ID_COUNTRY">País:</label>
                <select class="form-control mt-2" name="ID_COUNTRY" id="ID_COUNTRY" required>
                    <option value="" disabled selected>Seleccione un país</option>
                    <?php foreach ($countries as $country): ?>
                        <option value="<?= $country['COUNTRY_ID']; ?>"><?= htmlspecialchars($country['NAME']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="ZIP_CODE">Código Postal:</label>
                <input class="form-control mt-2" name="ZIP_CODE" id="ZIP_CODE" required placeholder="Su código postal">

                <button type="submit" class="btn btn-primary w-100">Finalizar Registro</button>
            </div>
        </form>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    </main>
</body>
</html>

<script>
    $(document).ready(function () {
        $("#addressForm").submit(function (e) {
            e.preventDefault(); // Evita el envío tradicional del formulario

            var formData = $(this).serialize(); // Serializa los datos del formulario

            $.ajax({
                type: "POST",
                url: "../DAL/address.php",
                data: formData,
                success: function (response) {
                    console.log("Respuesta del servidor:", response);

                    if (response.includes("Direccion insertada correctamente")) {
                        alert("Dirección registrada con éxito.");
                        window.location.href = "login.php"; // Redirigir al login
                    } else {
                        alert("Error al registrar la dirección.");
                    }
                },
                error: function () {
                    alert("Error en la solicitud AJAX.");
                }
            });
        });
    });
</script>
