<?php
require_once "../DAL/conexion.php";
require_once "../include/templates/headerUser.php";

$errores = [];
$exito = "";
$token = $_GET['token'] ?? null;

if (!$token) {
    $errores[] = "Token inválido o no proporcionado.";
} else {
    $conn = conectar();

    // Verificar token
    $query = "SELECT user_id, user_name, user_email 
              FROM FIDE_USERS_TB 
              WHERE reset_token = :token AND reset_token_expiration > SYSDATE";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ":token", $token);
    oci_execute($stmt);
    $user = oci_fetch_assoc($stmt);

    if (!$user) {
        $errores[] = "El enlace de recuperación es inválido o ha expirado.";
    }

    // Si se envió el formulario para cambiar contraseña
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
        $new_pass = $_POST['password'] ?? '';
        $confirm_pass = $_POST['confirm_password'] ?? '';

        if (empty($new_pass) || empty($confirm_pass)) {
            $errores[] = "Todos los campos son obligatorios.";
        } elseif ($new_pass !== $confirm_pass) {
            $errores[] = "Las contraseñas no coinciden.";
        } else {
            $passwordHash = password_hash($new_pass, PASSWORD_DEFAULT);

            $update = "UPDATE FIDE_USERS_TB 
                       SET password = :password, reset_token = NULL, reset_token_expiration = NULL,
                           modified_by = :user_name,
                           modified_on = CURRENT_TIMESTAMP  
                       WHERE user_id = :user_id";
            $updateStmt = oci_parse($conn, $update);
            oci_bind_by_name($updateStmt, ":password", $passwordHash);
            oci_bind_by_name($updateStmt, ":user_id", $user['USER_ID']);
            oci_bind_by_name($updateStmt, ':user_name', $user['USER_NAME']);
            oci_execute($updateStmt);

            $exito = "Contraseña actualizada correctamente. Ahora puedes iniciar sesión.";
        }
    }
}
?>

<style>
    .reset-container {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .reset-card {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        max-width: 400px;
        width: 100%;
    }

    .input-reset {
        width: 100%;
        padding: 0.75rem;
        margin-bottom: 1rem;
        border-radius: 10px;
        border: 1px solid #ccc;
    }

    .submit-reset {
        width: 100%;
        padding: 0.75rem;
        background-color: black;
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        cursor: pointer;
    }

    .submit-reset:hover {
        background-color: #333;
    }

    .error-msg, .success-msg {
        margin-top: 1rem;
        text-align: center;
    }

    .error-msg { color: red; }
    .success-msg { color: green; }
</style>

<div class="reset-container">
    <div class="reset-card">
        <?php if (!empty($errores)): ?>
            <div class="error-msg">
                <?php foreach ($errores as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($exito)): ?>
            <div class="success-msg">
                <p><?= $exito ?></p>
                <a href="../userSrc/login.php">Iniciar sesión</a>
            </div>
        <?php elseif ($user): ?>
            <form method="POST">
                <label for="password">Nueva contraseña</label>
                <input type="password" class="input-reset" name="password" id="password" required>

                <label for="confirm_password">Confirmar contraseña</label>
                <input type="password" class="input-reset" name="confirm_password" id="confirm_password" required>

                <button type="submit" class="submit-reset">Cambiar contraseña</button>
            </form>
        <?php endif; ?>
    </div>
</div>
