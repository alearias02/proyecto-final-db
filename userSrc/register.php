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
            header("Location: register-Addy.php?customer_id=" . urlencode($customer_id) . "&email=" . urlencode($user_email));
            exit();
        }       
        
    }
}
// Conectar a la base de datos
$connection = conectar();

?>

<!DOCTYPE html>
<html lang="es">
<body>
    <main class="main-login">
        <form method="POST" id="form_Register">
            <div class="card-body">
                <input type="hidden" name="action" value="">
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
                
                <label for="password" class="label-login required-label">Contraseña</label>
                <div class="input-form-login">
                    <input type="password" class="input-login" name="password" id="password" placeholder="Ingrese su contraseña" required>
                </div>

                <label for="Cpassword" class="label-login required-label">Confirmar Contraseña</label>
                <div class="input-form-login">
                    <input type="password" class="input-login" name="Cpassword" id="Cpassword" placeholder="Confirme su contraseña" required>
                </div>

                <p class="p-register" style="color: red;"><?php echo $mensajeError; ?></p>

                <a href="register-Addy.php" ><button type="submit" class="button-submit">Registrarse</button></a>
                <p class="p">¿Posees una cuenta? <span class="span"><a href="login.php">Inicia sesión</a></span></p>
            </div>
        </form>
        
    </main>
</body>
</html>

