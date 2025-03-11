<?php 
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
                <form id="addReservationForm" class="was-validated" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="insertar">
                    <div class="modal-body text-center" style="background-color: #eee;">

                        <!-- Fecha de inicio -->
                        <div class="mb-3">
                            <label for="startDate">Fecha de inicio</label>
                            <input class="form-control" type="date" name="start_date" id="startDate" required />
                        </div>

                        <!-- Fecha de fin -->
                        <div class="mb-3">
                            <label for="endDate">Fecha final</label>
                            <input class="form-control" type="date" name="end_date" id="endDate" required />
                        </div>

                        <!-- Cantidad de noches -->
                        <div class="mb-3">
                            <label for="qty_nights">Cantidad de noches</label>
                            <input class="form-control mt-2" type="number" name="qty_nights" id="qty_nights" required>
                        </div>

                        <!-- Comentarios -->
                        <div class="mb-3">
                            <label for="comments">Comentarios</label>
                            <textarea class="form-control mt-2" name="comments" id="comments" rows="3" required></textarea>
                        </div>

                        <!-- CLIENTE -->
                        <div class="mb-3">
                            <label for="reservation_customer_id">Cliente de la reserva</label>
                            <select class="form-control mt-2" name="reservation_customer_id" id="reservation_customer_id" required>
                                <option value="" disabled selected>Seleccione un cliente</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?= $customer['CUSTOMER_ID']; ?>"><?= htmlspecialchars($customer['CUSTOMER_NAME']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Habitación -->
                        <div class="mb-3">
                            <label for="room_id">Habitación</label>
                            <select class="form-control mt-2" name="room_id" id="room_id" required>
                                <option value="" disabled selected>Seleccione una habitación</option>
                                <?php foreach ($rooms as $room): ?>
                                    <option value="<?= $room['ROOM_ID']; ?>"><?= htmlspecialchars($room['ROOM']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- HOTEL -->
                        <div class="mb-3">
                            <label for="hotel_id">Hotel donde vacacionar</label>
                            <select class="form-control mt-2" name="hotel_id" id="hotel_id" required>
                                <option value="" disabled selected>Seleccione un hotel</option>
                                <?php foreach ($hotels as $hotel): ?>
                                    <option value="<?= $hotel['HOTEL_ID']; ?>"><?= htmlspecialchars($hotel['FULL_HOTELNAME']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Estado de LA RESERVA -->
                        <div class="mb-3">
                            <label for="status_id">Estado de la reserva</label>
                            <select class="form-control mt-2" name="status_id" id="status_id" required>
                                <option value="" disabled selected>Seleccione un estado</option>
                                <?php foreach ($statuses as $status): ?>
                                    <option value="<?= $status['STATUS_ID']; ?>"><?= htmlspecialchars($status['STATUS_DESCRIPTION']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- tipo de pago -->
                        <div class="mb-3">
                            <label for="payment_method_id">Metodo de pago</label>
                            <select class="form-control mt-2" name="payment_method_id" id="payment_method_id" required>
                                <option value="" disabled selected>Seleccione un tipo de pago</option>
                                <?php foreach ($payMethods as $payMethod): ?>
                                    <option value="<?= $payMethod['PAYMENT_METHOD_ID']; ?>"><?= htmlspecialchars($payMethod['PAYMENT_METHOD_NAME']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Creado por -->
                        <div class="mb-3">
                            <label for="created_by">Creado por</label>
                            <input type="text" class="form-control mt-2" name="created_by" id="created_by" placeholder="Ingrese su nombre" required />
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
