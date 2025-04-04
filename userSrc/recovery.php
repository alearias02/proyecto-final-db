<?php
session_start();

require_once "../DAL/conexion.php";
require_once "../include/templates/headerUser.php";
require_once "../include/functions/recoge.php";
require_once "../include/functions/sendEmail.php"; 

$errores = [];
$exito = "";
$user_name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = recogePost('user_name');
    $email = recogePost('email');

    if (empty($user_name) && empty($email)) {
        $errores[] = "Debe ingresar el nombre de usuario o el correo.";
    } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo ingresado no es válido.";
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
            $token = bin2hex(random_bytes(32));
            $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $updateQuery = "UPDATE FIDE_USERS_TB 
                            SET reset_token = :token, 
                                reset_token_expiration = TO_TIMESTAMP(:expiration, 'YYYY-MM-DD HH24:MI:SS'),
                                modified_by = :user_name,
                                modified_on = CURRENT_TIMESTAMP 
                            WHERE user_id = :user_id";
            $updateStmt = oci_parse($conn, $updateQuery);
            oci_bind_by_name($updateStmt, ':token', $token);
            oci_bind_by_name($updateStmt, ':expiration', $expiration);
            oci_bind_by_name($updateStmt, ':user_id', $user['USER_ID']);
            oci_bind_by_name($updateStmt, ':user_name', $user['USER_NAME']);
            oci_execute($updateStmt);

            $resetLink = "http://localhost:8000/userSrc/passwordReset.php?token=" . $token;


            if (enviarCorreoRecuperacion($user['USER_EMAIL'], $user['USER_NAME'], $resetLink)) {
                $exito = "Se ha enviado un enlace de recuperación a tu correo electrónico.";
            } else {
                $errores[] = "No se pudo enviar el correo. Aquí está el enlace de recuperación:";
                $errores[] = "<a href='$resetLink'>$resetLink</a>"; // debug
            }
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

                    <label for="user_name" class="label-login">Usuario</label>
                    <div class="input-form-login">
                        <i class="fa-solid fa-user"></i>    
                        <input type="text" class="input-login" name="user_name" id="user_name"
                               placeholder="Ingrese su usuario" value="<?= htmlspecialchars($user_name) ?>">
                    </div>   

                    <label for="email" class="label-login">Correo</label>
                    <div class="input-form-login">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" class="input-login" name="email" id="email"
                               placeholder="Ingrese su correo" value="<?= htmlspecialchars($email) ?>">
                    </div>

                    <button type="submit" class="button-submit">
                        <i class="fa-solid fa-magnifying-glass"></i> Revisar
                    </button>
                    <p class="p">¿No tienes cuenta? <span class="span"><a href="register.php">  Regístrate aquí</a></span></p>

                    <?php if (!empty($errores)): ?>
                        <div class="errores">
                            <?php foreach ($errores as $error): ?>
                                <p style="color: red;"><?= $error ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($exito)): ?>
                        <div class="success-msg">
                            <p style="color: green;"><?= $exito ?></p>
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

    .errores, .success-msg {
        margin-top: 1rem;
        text-align: center;
    }

    .errores a {
        color: blue;
        text-decoration: underline;
    }
</style>
