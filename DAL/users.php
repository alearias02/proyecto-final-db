<?php

require_once "conexion.php"; // Archivo que maneja la conexión a Oracle


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
                VALUES (:user_name, :user_email, password, 1,  1)";

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
    } else {
        echo "Acción no válida";
    }
}

// function usuarioExiste($pUsername) {
//     try {
//         $oConexion = conectar();

//         // Verificar si el nombre de usuario ya existe
//         $stmt = $oConexion->prepare("SELECT COUNT(*) FROM usuario WHERE username = ?");
//         $stmt->bind_param("s", $pUsername);
//         $stmt->execute();
//         $stmt->bind_result($count);
//         $stmt->fetch();
//         $stmt->close();
        
//         return $count > 0;
//     } catch (\Throwable $th) {
//         // Manejar el error
//         echo $th;
//         return false;
//     } finally {
//         desconectar($oConexion);
//     }
// }

// function correoExiste($pCorreo) {
//     try {
//         $oConexion = conectar();

//         // Verificar si el correo ya existe
//         $stmt = $oConexion->prepare("SELECT COUNT(*) FROM usuario WHERE correo = ?");
//         $stmt->bind_param("s", $pCorreo);
//         $stmt->execute();
//         $stmt->bind_result($count);
//         $stmt->fetch();
//         $stmt->close();

//         return $count > 0;
//     } catch (\Throwable $th) {
//         // Manejar el error
//         echo $th;
//         return false;
//     } finally {
//         desconectar($oConexion);
//     }
// }


// function IngresarUsuario($pUsername, $pPassword, $pNombre, $pApellidos, $pCorreo, $pTelefono, $pRutaImagen, $pActivo) {
//     $retorno = false;

//     try {
    
//         $oConexion = conectar();

//         if (mysqli_set_charset($oConexion, "utf8")) {
           
//             $hashedPassword = password_hash($pPassword, PASSWORD_DEFAULT);

//             $stmt = $oConexion->prepare("INSERT INTO usuario (username, password, nombre, apellidos, correo, telefono, ruta_imagen, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
//             $stmt->bind_param("sssssssi", $vUsername, $vPassword, $vNombre, $vApellidos, $vCorreo, $vTelefono, $vRutaImagen, $vActivo);
            
//             $vUsername = $pUsername;
//             $vPassword = $hashedPassword; 
//             $vNombre = $pNombre;
//             $vApellidos = $pApellidos;
//             $vCorreo = $pCorreo;
//             $vTelefono = $pTelefono;
//             $vRutaImagen = $pRutaImagen;
//             $vActivo = $pActivo;

//             if ($stmt->execute()) {
//                 $retorno = true;
//             }
//         }
//     } catch (\Throwable $th) {
//         // Manejar el error
//         echo $th;
//     } finally {
//         desconectar($oConexion);
//     }
//     return $retorno;
// }



// function getArray($sql){
//     try {
//         $oConexion = conectar();

//         if (mysqli_set_charset($oConexion, "utf8")) {
//             if(!$result = mysqli_query($oConexion, $sql)) die();//cancelar ejecucion

//             $retorno = array();

//             while ($row = mysqli_fetch_array($result)) {
//                 $retorno[] = $row;
//             }
//         }
//     } catch (\Throwable $th) {
//         //throw $th;
//         echo $th;
//     }finally{
//         desconectar($oConexion);
//     }
//     return $retorno;
// }

// function getObject($sql){
//     try {
//         $oConexion = conectar();

//         if (mysqli_set_charset($oConexion, "utf8")) {
//             if(!$result = mysqli_query($oConexion, $sql)) die();

//             $retorno = null;

//             while ($row = mysqli_fetch_array($result)) {
//                 $retorno = $row;
//             }
//         }
//     } catch (\Throwable $th) {
//         //throw $th;
//         echo $th;
//     }finally{
//         desconectar($oConexion);
//     }
//     return $retorno;
// }