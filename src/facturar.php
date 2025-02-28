<!DOCTYPE html>
<html lang="es">

<head>

</head>

<body>
    <?php
    require_once "../include/templates/header.php";
    ?>
    <div class="modal" id="modal-iniciar-sesion" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <p>Debes iniciar sesión para continuar</p>
                </div>
                <div class="text-center">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="window.location.href='../userSrc/login.php'">Iniciar sesión</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="factura-main" id='factura-contenido'>
        <div id="facturaGeneral">

        </div>
        <div id="datosCliente">
            <?php
            if (isset($_SESSION['usuario'])) {
                $usuario = $_SESSION['usuario']; 
                $id_username = $usuario['id_username']; 
                $correo = $usuario['correo']; 
                $telefono = $usuario['telefono'];
                echo '<input  id="idCliente"type="hidden" value="' . $id_username . '">';
                echo '<div id="datosCliente">';
                echo '<p>Correo: ' . $correo . '</p>';
                echo '<p>Teléfono: ' . $telefono . '</p>';
                echo '</div>';
                echo '<div class= "text-center">';
                echo '<button type="button" class="button-buy-bill" id="button-pay">Pagar</button>';
                echo '</div>';
            }
            ?>
        </div> 

    </div>      
    <?php
    require_once "../include/templates/footer.php";
    ?>
    <?php if (!isset($_SESSION['usuario'])) : ?>
        <script>
            $(document).ready(function() {
                $('#modal-iniciar-sesion').modal('show');

            });
        </script>
    <?php else : ?>
        <script src="../js/factura.js"></script>
    <?php endif; ?>
</body>
</html>