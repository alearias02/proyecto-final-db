<?php
session_start();
require_once "../include/templates/headerUser.php";
require_once "../include/functions/recoge.php";
require_once "../DAL/conexion.php"; // Archivo de conexión con Oracle

$errores = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = recogePost('user_name');
    $password = recogePost('password');

    if (empty($user_name) || empty($password)) {
        $errores[] = "Usuario y contraseña son obligatorios.";
    } else {
        $conn = conectar(); // Función que retorna la conexión a Oracle

        // Consulta con JOIN para obtener usuario y rol
        $query = "SELECT u.user_id, u.user_name, u.user_email, u.password, u.rol_id, r.rol_name AS rol
                  FROM FIDE_USERS_TB u 
                  JOIN FIDE_ROL_TB r ON u.rol_id = r.rol_id 
                  WHERE u.user_name = :user_name";

        $stmt = oci_parse($conn, $query);
        oci_bind_by_name($stmt, ":user_name", $user_name);
        oci_execute($stmt);

        $mySession = oci_fetch_assoc($stmt);

        if (!$mySession) {
            $errores[] = "Usuario no encontrado.";
        }  else {
            if (password_verify($password, $mySession['PASSWORD']) || $password === $mySession['PASSWORD']) {
                // Contraseña correcta
                $_SESSION = [
                    'user_id'   => $mySession['USER_ID'],
                    'user_name' => $mySession['USER_NAME'],
                    'email'     => $mySession['USER_EMAIL'],
                    'rol'       => $mySession['ROL'],
                    'rol_id'    => $mySession['ROL_ID'],
                    'login'     => true
                ];
                header("Location: ../src/index.php");
                exit();
            } else {
                $errores[] = "Contraseña incorrecta.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<body>
    <main class="main-login">
        <form method="POST">
            <div class="card-body">
                <label for="user_name" class="label-login">Usuario</label>
                <div class="input-form-login">
                    <i class="fa-solid fa-user"></i>    
                    <input type="text" class="input-login" name="user_name" id="user_name" placeholder="Ingrese su usuario" required>
                </div>   
                
                <label for="password" class="label-login">Contraseña</label>
                <div class="input-form-login">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" class="input-login" name="password" id="password" placeholder="Ingrese su contraseña" required>
                </div>

                <button type="submit" class="button-submit">Iniciar Sesión</button>
                <p class="p">¿No tienes cuenta? <span class="span"><a href="register.php">Regístrate aquí</a></span></p><p class="p">¿Olvidaste tu contraseña? <span class="span"><a href="recovery.php">Restaura tu contraseña aquí</a></span></p>
                
                <?php if (!empty($errores)): ?>
                    <div class="errores">
                        <?php foreach ($errores as $error): ?>
                            <p style="color: red;"><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>  
        </form>
    </main>
</body>
</html>
