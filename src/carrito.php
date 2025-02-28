<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    require_once "../include/templates/header.php";
    ?>
    <nav id="cart-nav">

    </nav>
    <main id="cart-main">
        <div id="cart-container">

        </div>
        <div id="bill-payment">

        </div>
        <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel">Iniciar sesión</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Debes iniciar sesión para comprar.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <a href="../userSrc/login.php" class="btn btn-primary">Iniciar sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
<?php
require_once "../include/templates/footer.php";
?>

</html>