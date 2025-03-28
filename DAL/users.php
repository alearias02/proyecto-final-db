<?php

require_once "conexion.php"; // Archivo que maneja la conexión a Oracle

function IngresarUsuarioCliente($customer_id, $name, $user_name, $user_email, $password, $customer_phone) {
    $retorno = false;
    $oConexion = conectar(); // Connect to Oracle

    try {
        oci_execute(oci_parse($oConexion, "ALTER SESSION SET NLS_DATE_FORMAT = 'YYYY-MM-DD HH24:MI:SS'"), OCI_DEFAULT);

        // Encrypt the password before storing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert into USERS table
        $sqlUser = "INSERT INTO FIDE_SAMDESIGN.FIDE_USERS_TB (USER_NAME, USER_EMAIL, PASSWORD, STATUS_ID, ROL_ID, CREATED_BY, CREATED_ON)
                    VALUES (:user_name, :user_email, :password, 1, 3, 'SELF-USER', CURRENT_TIMESTAMP)";

        $stmtUser = oci_parse($oConexion, $sqlUser);
        oci_bind_by_name($stmtUser, ":user_name", $user_name);
        oci_bind_by_name($stmtUser, ":user_email", $user_email);
        oci_bind_by_name($stmtUser, ":password", $hashedPassword);

        if (!oci_execute($stmtUser, OCI_NO_AUTO_COMMIT)) {
            throw new Exception("Error inserting into USERS table");
        }

        oci_commit($oConexion);

        // Insert into CUSTOMERS table using full name
        $sqlCustomer = "INSERT INTO FIDE_SAMDESIGN.FIDE_CUSTOMER_TB (CUSTOMER_ID, CUSTOMER_NAME, CUSTOMER_EMAIL, CUSTOMER_PHONE_NUMBER, STATUS_ID, CREATED_BY, CREATED_ON)
                        VALUES (:customer_id, :full_name, :customer_email, :customer_phone, 1, 'SELF-USER', CURRENT_TIMESTAMP)";

        $stmtCustomer = oci_parse($oConexion, $sqlCustomer);
        oci_bind_by_name($stmtCustomer, ":customer_id", $customer_id);
        oci_bind_by_name($stmtCustomer, ":full_name", $name); // Concatenated name
        oci_bind_by_name($stmtCustomer, ":customer_email", $user_email);
        oci_bind_by_name($stmtCustomer, ":customer_phone", $customer_phone);

        if (!oci_execute($stmtCustomer, OCI_NO_AUTO_COMMIT)) {
            throw new Exception("Error inserting into CUSTOMERS table");
        }

        // Commit the transaction if everything is successful
        oci_commit($oConexion);
        $retorno = true;

    } catch (Exception $e) {
        // Rollback the transaction if an error occurs
        oci_rollback($oConexion);
        echo "Error: " . $e->getMessage();
    } finally {
        oci_free_statement($stmtUser);
        oci_free_statement($stmtCustomer);
        oci_close($oConexion);
    }

    return $retorno;
}
function correoExiste($pCorreo) {
    $oConexion = conectar();

    $sql = "SELECT COUNT(*) AS TOTAL 
            FROM FIDE_SAMDESIGN.FIDE_USERS_TB 
            WHERE USER_EMAIL = :user_email";

    $stmt = oci_parse($oConexion, $sql);
    oci_bind_by_name($stmt, ":user_email", $pCorreo);
    oci_execute($stmt);

    $row = oci_fetch_assoc($stmt);
    
    oci_free_statement($stmt);
    oci_close($oConexion);

    // si total mayor a 0 entonces existe correo
    return ($row['TOTAL'] > 0);
}


function usuarioExiste($pUser_Name) {
    $oConexion = conectar();

    $sql = "SELECT COUNT(*) AS TOTAL 
            FROM FIDE_SAMDESIGN.FIDE_USERS_TB 
            WHERE USER_NAME = :user_name";

    $stmt = oci_parse($oConexion, $sql);
    oci_bind_by_name($stmt, ":user_name", $pUser_Name);
    oci_execute($stmt);

    $row = oci_fetch_assoc($stmt);
    
    oci_free_statement($stmt);
    oci_close($oConexion);

    // si total mayor a 0 entonces existe usuario
    return ($row['TOTAL'] > 0);
}



// Función para obtener los detalles de una habitación
function obtenerDetallesUsuario($user_name) {
    $oConexion = conectar();
    $sql = "SELECT 
                user_id,
                user_name,
                user_email, 
                password, 
                status_id,
                role_id,
                created_by,
                created_on,
                modified_on,
                modified_by 
            FROM FIDE_SAMDESIGN.fide_users_tb 
            WHERE user_name = :user_name";
    $stmt = oci_parse($oConexion, $sql);
    oci_bind_by_name($stmt, ":user_name", $user_name);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
    oci_close($oConexion);
    if ($row === false) {
        return ['error' => 'No se encontró el usuario con el ID proporcionado.'];
    }

    return $row;
}

// Insertar una habitación
function IngresarUsuario($user_name, $user_email, $password) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "INSERT INTO FIDE_SAMDESIGN.fide_users_tb (user_name, user_email, password, status_id, role_id)
                VALUES (:user_name, :user_email, :password, 1,  1)";

        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":user_name", $user_name);
        oci_bind_by_name($stmt, ":user_email", $user_email);
        oci_bind_by_name($stmt, ":password", $password);
        // Ejecutar la consulta
        if (oci_execute($stmt)) {
            $retorno = true;
        }

    } catch (\Throwable $th) {
        echo $th;
    } finally {
        oci_free_statement($stmt);
        oci_close($oConexion);
    }

    return $retorno;
}
// Eliminar un producto
function eliminarUsuario($user_name) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "UPDATE FIDE_SAMDESIGN.fide_users_tb
                SET status_id = 0,
                    modified_on = SYSDATE,
                    modified_by = :modified_by 
                WHERE user_name = :user_name";
        $stmt = oci_parse($oConexion, $sql);

        // Vincular el parámetro
        oci_bind_by_name($stmt, ":user_name", $user_name);

        // Ejecutar la consulta
        if (oci_execute($stmt)) {
            $retorno = true;
        }
    } catch (\Throwable $th) {
        echo $th;
    } finally {
        oci_free_statement($stmt);
        oci_close($oConexion);
    }

    return $retorno;
}

// Actualizar un producto
function actualizarUsuario($user_name, $user_email, $password, $role_id) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "UPDATE FIDE_SAMDESIGN.fide_users_tb 
                SET user_name  = :user_name,
                    user_email = :user_email,
                    password = :password,
                    role_id = :role_id,
                    modified_on = SYSDATE,
                WHERE user_name = :user_name";

        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":user_name", $user_name);
        oci_bind_by_name($stmt, ":user_email", $user_email);
        oci_bind_by_name($stmt, ":password", $password);
        oci_bind_by_name($stmt, ":role_id", $role_id);
        
        // Ejecutar la consulta
        if (oci_execute($stmt)) {
            $retorno = true;
        }

    } catch (\Throwable $th) {
        echo $th;
    } finally {
        oci_free_statement($stmt);
        oci_close($oConexion);
    }

    return $retorno;
}

// Manejar acciones desde solicitudes POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    $action = $_POST["action"];

    if ($action == "eliminar" && isset($_POST["user_name"])) {
        $room_id = $_POST["user_name"];
        $eliminado = eliminarUsuario($user_name);
        echo $eliminado ? "El usuario ha sido eliminado exitosamente" : "Error al intentar eliminar el usuario";
    } elseif ($action == "obtenerDetalles" && isset($_POST["user_name"])) {
        $room_id = $_POST["user_name"];
        error_log("Obteniendo detalles para ID: " . $user_name); // Depuración

        $detalles = obtenerDetallesUsuario($user_name);

        if (isset($detalles['error'])) {
            http_response_code(404); // Devuelve error si no se encuentra el servicio
        }
        
        echo json_encode($detalles);
    } elseif ($action == "actualizar" && isset($_POST["USER_NAME"])) {
        $user_name = $_POST["USER_NAME"];
    
        // Registrar todos los datos recibidos para depuración
        error_log("Datos recibidos para actualizar: " . json_encode($_POST));
    
        // Verifica parámetros requeridos
        $required_fields = ["USER_NAME", "USER_EMAIL", "PASSWORD"];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                http_response_code(400); // Código de error 400: Solicitud Incorrecta
                echo "El campo $field es requerido.";
                exit;
            }
        }
    
        // Ejecuta la actualización
        $actualizado = actualizarUsuario(
            $user_name,
            $_POST["USER_EMAIL"],
            $_POST["PASSWORD"],
            $_POST["ROLE_ID"]
        );
    
        // Envía la respuesta adecuada
        if ($actualizado) {
            echo "success";
        } else {
            http_response_code(500); // Código de error 500: Error Interno
            echo "Error updating service. Check logs for details.";
        }
        exit;
    } elseif ($action == "insertar") {

        $insertado = IngresarUsuario(
            $_POST["USER_NAME"],
            $_POST["USER_EMAIL"],
            $_POST["PASSWORD"]
        );
        echo $insertado ? "Usuario insertado correctamente" : "Error al insertar el Usuario";
    } elseif ($action == "" ) {
        // echo "Acción default";
    } else {
        echo "Acción no valida";
    } 
}
