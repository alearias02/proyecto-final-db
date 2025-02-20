<?php 
require_once "../include/templates/headerUser.php";
$mensajeErrorU = "";
$mensajeErrorE = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once "../include/functions/recoge.php";
    require_once "../DAL/usuarios.php"; 

    $username = recogePost('username');
    $password = recogePost('password');
    $nombre = recogePost('name');
    $apellidos = recogePost('lastname');
    $correo = recogePost('email');
    $telefono = recogePost('tel');
    $rutaImagen = recogePost("image");
    $activo = 1; 

    
    $usuarioExiste =  usuarioExiste($username);
    $correoExiste = correoExiste($correo);
    

    if ($usuarioExiste) {
        $mensajeErrorU = "Este usuario ya existe";
    }
    if ($correoExiste) {
        $mensajeErrorE = "Este correo ya existe";
    }

    
    if (empty($mensajeErrorU) && empty($mensajeErrorE)) {
        $resultado = IngresarUsuario($username, $password, $nombre, $apellidos, $correo, $telefono, $rutaImagen, $activo);
        
        if ($resultado === true) {

            require_once "../DAL/usuarios.php";
            require_once "../DAL/roles.php";
            $query = "select id_usuario from usuario where username = '$username' and correo = '$correo' ";
            
            $mySQL = getObject($query);
            IngresarRol('ROLE_USER', $mySQL['id_usuario']);
            header("Location: ../userSrc/login.php");
            exit();
        } else {
            
            $mensajeError = "Ocurrió un error al ingresar el usuario. Por favor, inténtalo de nuevo más tarde.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<body>
    <main class="main-login">
            <form method="POST" id="form">
                <div class="card-body">
                    <p class="p-register required-label">Elementos necesarios  :</p>
                    <label for= "username" class="label-login">Nombre</label>
                    <div class="input-form-login">
                    <input type="text" class="input-login" name="name" id="name" placeholder="Ingrese su nombre">
                    </div>
                    <label for= "username" class="label-login">Apellidos</label>
                    <div class="input-form-login">
                    <input type="text" class="input-login" name="lastname" id="lastname" placeholder="Ingrese sus apellidos">
                    </div>
                    <label for= "username" class="label-login required-label">Usuario</label>
                    <div class="input-form-login">
                    <input type="text" class="input-login" name="username" id="username" placeholder="Ingrese su usuario" required>
                    </div>
                    <p id="mensajeU" class="p-register"><?php echo $mensajeErrorU; ?></p> 
                    <label for= "username" class="label-login required-label">Correo</label>
                    <div class="input-form-login">
                    <input type="email" class="input-login" name="email" id="email" placeholder="Ingrese su correo" required>
                    </div>
                    <p id="mensajeE" class="p-register"><?php echo $mensajeErrorE; ?></p> 
                    <label for= "username" class="label-login required-label">Teléfono</label>
                    <div class="input-form-login">
                    <input type="tel" class="input-login" name="tel" id="tel" placeholder="Ingrese su teléfono" required>
                    </div>
                    <p id="mensajeT" class="p-register"></p> 
                    <label for= "username" class="label-login">Imagen de perfil</label>
                    <div class="">
                    <input type="file" class="input-file" name="image" id="image" >
                    </div>        
                    <label for= "password" class="label-login required-label">Contraseña</label>
                    <div class="input-form-login">
                    <input type="password" class="input-login " name="password" id="password" placeholder="Ingrese su contraseña" required>
                    </div>
                    <p id="mensajeP" class="p-register"></p>
                    <label for= "password" class="label-login required-label">Confirmar contraseña</label>
                    <div class="input-form-login">
                    <input type="password" class="input-login" name="Cpassword" id="Cpassword" placeholder="Confirme su contraseña" required>
                    </div>
                    <p id="mensajeC" class="p-register"></p>
                    <button type="submit" class="button-submit">Registrarse</button>
                    <p id="mensajeCancelacion" class="p-register" style="color: red;  text-align: center;"></p>
                    <p class="p">¿Posees una cuenta?<span class="span"><a href="login.php">Inicia sesión</a></span>
                </div>  
            </form>
            <script src="../js/expresiones.js"></script>
    </main>
</body>
</html>
