<?php
session_start();
require_once "../DAL/conexion.php";
require_once "../include/templates/headerUser.php";
require_once "../include/functions/recoge.php";


$errores = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $user_name = recogePost('user_name');
    $email = recogePost('email');

    if (empty($user_name) && empty($email)) {
        $errores[] = "Debe ingresar el nombre de usuario o el correo.";
    } else {
        $conn = conectar();
    
        if (!empty($user_name)) {
            $query = "SELECT u.user_id, u.user_name, u.user_email, u.password, u.rol_id, r.rol_name AS rol
                      FROM FIDE_USERS_TB u 
                      JOIN FIDE_ROL_TB r ON u.rol_id = r.rol_id 
                      WHERE u.user_name = :user_name";
    
            $stmt = oci_parse($conn, $query);
            oci_bind_by_name($stmt, ":user_name", $user_name);
        } else {
            $query = "SELECT u.user_id, u.user_name, u.user_email, u.password, u.rol_id, r.rol_name AS rol
                      FROM FIDE_USERS_TB u 
                      JOIN FIDE_ROL_TB r ON u.rol_id = r.rol_id 
                      WHERE u.user_email = :email";
    
            $stmt = oci_parse($conn, $query);
            oci_bind_by_name($stmt, ":email", $email);
        }
    
        oci_execute($stmt);
        $user = oci_fetch_assoc($stmt);
    
        if (!$user) {
            $errores[] = "Usuario o correo no encontrado.";
        } else {
            $_SESSION['usuario'] = [
                'user_id'   => $user['USER_ID'],
                'user_name' => $user['USER_NAME'],
                'email'     => $user['USER_EMAIL'],
                'rol'       => $user['ROL'],
                'rol_id'    => $user['ROL_ID'],
                'login'     => true
            ];                
            header("Location: ../userSrc/passwordChange.php");
            exit();
        }
    }
}

?>
<main>
    <body style="margin: 0;">
        <div class="center-container">
            <form method="POST">
                <div class="card-body">
                    <p class="p">Ingrese su usuario o correo electrónico para recuperar la contraseña</p>

                    <label for="user_name" class="label-login">Usuario </label>
                    <div class="input-form-login">
                        <i class="fa-solid fa-user"></i>    
                        <input type="text" class="input-login" name="user_name" id="user_name" placeholder="Ingrese su usuario">
                    </div>   

                    <label for="email" class="label-login">Correo</label>
                    <div class="input-form-login">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" class="input-login" name="email" id="email" placeholder="Ingrese su correo">
                    </div>

                    <button type="submit" class="button-submit">
                        <i class="fa-solid fa-magnifying-glass"></i> Revisar
                    </button>
                    <p class="p">¿No tienes cuenta? <span class="span"><a href="register.php">  Regístrate aquí</a></span></p>

                    <?php if (!empty($errores)): ?>
                        <div class="errores">
                            <?php foreach ($errores as $error): ?>
                                <p style="color: red;"><?php echo $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>  
            </form>
        </div>
    </body>
</main>

<style>
    .center-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh; /* altura completa del viewport */
    }

    .card-body {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
    }

    .input-form-login {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .input-login {
        flex: 1;
        padding: 0.5rem;
        margin-left: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 10px;
    }

    .button-submit {
        width: 100%;
        padding: 0.75rem;
        background-color: black;
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 1rem;
    }

    .button-submit:hover {
        background-color: #333;
    }
</style>
