<?php 
require_once "../DAL/database.php";
require_once "../include/templates/headerUser.php";
require_once "../include/functions/recoge.php";
require_once "../DAL/users.php";

$mensajeErrorU = "";
$mensajeErrorE = "";
$mensajeError = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capturar datos del formulario
    $customer_id = recogePost('customer_id'); // Cedula del cliente
    $user_name = recogePost('user_name');
    $password = recogePost('password');
    $confirm_password = recogePost('Cpassword');
    $customer_name = recogePost('name');
    $customer_lastname = recogePost('lastname');
    $full_name = trim($customer_name . " " . $customer_lastname); // Concatenate name + last name
    $customer_phone = recogePost('tel');
    $user_email = recogePost('email');

    // Verificar si las contraseñas coinciden
    if ($password !== $confirm_password) {
        $mensajeError = "Las contraseñas no coinciden.";
    }

    // Verificar si el usuario o correo existen
    if (usuarioExiste($user_name)) {
        $mensajeErrorU = "Este usuario ya existe.";
    }
    if (correoExiste($user_email)) {
        $mensajeErrorE = "Este correo ya está registrado.";
    }

    // Si no hay errores, registrar el usuario
    if (empty($mensajeError) && empty($mensajeErrorU) && empty($mensajeErrorE)) {
        $resultado = IngresarUsuarioCliente($customer_id, $full_name, $user_name, $user_email, $password,  $customer_phone);

        if ($resultado) {
            header("Location: ../userSrc/login.php");
            exit();
        } else {
            $mensajeError = "Ocurrió un error al registrar el usuario. Inténtalo de nuevo más tarde.";
        }
    }
}
// Conectar a la base de datos
$connection = conectar();


// Consultas
$countries = fetchAll($connection, "SELECT COUNTRY_ID, NAME FROM FIDE_SAMDESIGN.FIDE_COUNTRIES_TB");
$provincias = fetchAll($connection, "SELECT STATE_ID, NAME FROM FIDE_SAMDESIGN.FIDE_STATE_ADDRESS_TB");
$cantones = fetchAll($connection, "SELECT CITY_ID, NAME FROM FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB");


?>

<!DOCTYPE html>
<html lang="es">
<body>
    <main class="main-login">
        <form method="POST" id="form">
            <div class="card-body">
                <p class="p-register required-label">Elementos necesarios:</p>

                <label for="customer_id" class="label-login required-label">Cédula de Identidad</label>
                <div class="input-form-login">
                    <input type="text" class="input-login" name="customer_id" id="customer_id" placeholder="Ingrese su cédula" required>
                </div>

                <label for="name" class="label-login">Nombre</label>
                <div class="input-form-login">
                    <input type="text" class="input-login" name="name" id="name" placeholder="Ingrese su nombre">
                </div>

                <label for="lastname" class="label-login">Apellidos</label>
                <div class="input-form-login">
                    <input type="text" class="input-login" name="lastname" id="lastname" placeholder="Ingrese sus apellidos">
                </div>

                <label for="user_name" class="label-login required-label">Usuario</label>
                <div class="input-form-login">
                    <input type="text" class="input-login" name="user_name" id="user_name" placeholder="Ingrese su usuario" required>
                </div>
                <p class="p-register"><?php echo $mensajeErrorU; ?></p> 

                <label for="email" class="label-login required-label">Correo</label>
                <div class="input-form-login">
                    <input type="email" class="input-login" name="email" id="email" placeholder="Ingrese su correo" required>
                </div>
                <p class="p-register"><?php echo $mensajeErrorE; ?></p> 

                <label for="tel" class="label-login required-label">Teléfono</label>
                <div class="input-form-login">
                    <input type="tel" class="input-login" name="tel" id="tel" placeholder="Ingrese su teléfono" required>
                </div>
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                Agregar su dirección:
                </button>

                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <h5 id="offcanvasRightLabel">Agregue su dirección</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">
                <form id="addAddressForm" class="was-validated" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="insertar">
                    <input type="hidden" name="customer_id" id="address_customer_id" value=""> <!-- Nuevo campo oculto -->
                    <div class="modal-body text-center" style="background-color: #eee;">

                        <!-- Comentarios -->
                        <div class="mb-3">
                            <label for="address">Direccion Especifica:</label>
                            <textarea class="form-control mt-2" name="address" id="address" rows="3" required placeholder="Señas adicionales"></textarea>
                        </div>

                        <!-- Country -->
                        <div class="mb-3">
                            <label for="id_country">Pais:</label>
                            <select class="form-control mt-2" name="id_country" id="id_country" required>
                                <option value="" disabled selected>Seleccione un pais</option>
                                <?php foreach ($countries as $country): ?>
                                    <option value="<?= $country['COUNTRY_ID']; ?>"><?= htmlspecialchars($country['NAME']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Provincias -->
                        <div class="mb-3">
                            <label for="state_id">Provincia:</label>
                            <select class="form-control mt-2" name="state_id" id="state_id" required>
                                <option value="" disabled selected>Seleccione una provincia</option>
                                <?php foreach ($provincias as $provincia): ?>
                                    <option value="<?= $provincia['STATE_ID']; ?>"><?= htmlspecialchars($provincia['NAME']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- CANTON -->
                        <div class="mb-3">
                            <label for="city_id">Canton:</label>
                            <select class="form-control mt-2" name="city_id" id="city_id" required>
                                <option value="" disabled selected>Seleccione un canton</option>
                                <?php foreach ($cantones as $canton): ?>
                                    <option value="<?= $canton['CITY_ID']; ?>"><?= htmlspecialchars($canton['NAME']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- ZIPCODE -->
                        <div class="mb-3">
                            <label for="zip_code">Codigo Postal:</label>
                            <input class="form-control mt-2" name="zip_code" id="zip_code" rows="1" required placeholder="Su codigo postal"></input>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-primary">Crear</button>
                    </div>
                </form>

                </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

                <label for="password" class="label-login required-label">Contraseña</label>
                <div class="input-form-login">
                    <input type="password" class="input-login" name="password" id="password" placeholder="Ingrese su contraseña" required>
                </div>

                <label for="Cpassword" class="label-login required-label">Confirmar Contraseña</label>
                <div class="input-form-login">
                    <input type="password" class="input-login" name="Cpassword" id="Cpassword" placeholder="Confirme su contraseña" required>
                </div>
                <p class="p-register" style="color: red;"><?php echo $mensajeError; ?></p>

                <button type="submit" class="button-submit">Registrarse</button>
                <p class="p">¿Posees una cuenta? <span class="span"><a href="login.php">Inicia sesión</a></span></p>
            </div>
        </form>
    </main>
</body>
</html>

<script>
    //para asignarle el valor del customer_id en el address
    document.getElementById('customer_id').addEventListener('input', function(){
        document.getElementById('address_customer_id').value = this.value;
    });

    
</script>

