<?php
require_once "../include/templates/headerUser.php";
$errores=array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once "../include/functions/recoge.php";
    $username = recogePost('username');
    $password = recogePost('password');
    

    if (empty($errores)) {
        require_once "../DAL/usuarios.php";
        require_once "../DAL/roles.php";
        $query = "select  id_usuario , username, password ,  nombre , apellidos ,correo , telefono , ruta_imagen  from usuario where username = '$username'";
        $mySession = getObject($query);
        $roleQuery = "SELECT nombre FROM rol WHERE id_usuario = '{$mySession['id_usuario']}'";
        $rol = getObjectR ($roleQuery);
        if ($mySession != null) {
            $auth = password_verify($password, $mySession['password']);
            if ($auth) {    
                session_start();     
                $_SESSION['usuario'] = array(
                    'id_username' => $mySession['id_usuario'],
                    'username' => $mySession ['username'],
                    'password' => $mySession ['password'],
                    'nombre' => $mySession ['nombre'],
                    'apellidos' => $mySession ['apellidos'],
                    'correo' => $mySession ['correo'],
                    'telefono' => $mySession ['telefono'],
                    'ruta_imagen' =>  $mySession ['ruta_imagen'],
                    'rol' => $rol ['nombre'],
                    'login' => true
                );
                header("Location: ../src/index.php");
            } else {
                $errores[] = "Contraseña incorrecta";
            }
        } else {
            $errores[] = "Ese usuario no existe";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<body>
    <main class="main-login">
            <form method="POST">
                <div class="card-body">
                    <label for= "username" class="label-login">Usuario</label>
                    <div class="input-form-login">
                    <i class="fa-solid fa-user"></i>    
                    <input type="text" class="input-login" name="username" id="username" placeholder="Ingrese su usuario">
                    </div>   
                    <label for= "password" class="label-login">Contraseña</label>
                    <div class="input-form-login">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" class="input-login" name="password" id="password" placeholder="Ingrese su contraseña">
                    </div>
                    <button type="submit" class="button-submit">Iniciar Sesion</button>
                    <p class="p">¿No tienes cuenta?<span class="span"><a href="register.php">Regístrate aquí</a></span>
                    <?php
                     require_once "../include/templates/errores.php";
                    ?>
                </div>  
            </form>
    </main>
</body>
</html>