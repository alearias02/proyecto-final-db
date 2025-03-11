<?php
// Establece el tiempo de vida de la sesión en 1 hora (3600 segundos)
ini_set('session.gc_maxlifetime', 3600);
ini_set('session.cookie_lifetime', 3600);
session_start();

if (isset($_SESSION['usuario'])) {
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600)) {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }
    $_SESSION['LAST_ACTIVITY'] = time();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAM DESIGN</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Staatliches&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/normalize.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <!-- El preload nos va a permitir solicitar recursos html rapidamente -->
    <link rel="preload" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/styles.css">
</head>

<body>
    <header class="header">
        <section id="main-header" class="m-0 p-0 text-bg-color " style="background-color:#eee; width: 100%">
            <div class="container ">
                <div class="row">
                    <div class="contenedor-img col-8 ">
                        <a href="index.php">
                            <img src="../../img/montañas.png" class="img-header img-fluid" alt="Montanas" />
                        </a>
                    </div>
                    <div class="col-4 row justify-content-end text-center ">
                        <ul class="p-1 m-0 col-2 align-self-end " style="width: 100px; list-style: none;">
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </header>
    <!-- segmento de el navbar -->
    <!-- aqui modificamos el color del nav atraves de CSS aderido con la etiqueta style para usar los colores hexdec -->
    <nav class="navbar navbar-expand-md p-0 " style="background-color:/*#A3BFD7*/#475a68; z-index: 1; ">
        <div class="container-fluid">

            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand m-0 py-0 pl-4 pr-2" href="index.php">
                <img src="../../img/logo_pequeño-removebg-preview.png" class="img-responsive" alt="Responsive image" width="94" height="94" />
            </a>
            <div class="collapse navbar-collapse  justify-content-evenly" id="navbarCollapse">
                <ul class="navbar-nav " style="font-size:1.5rem ; padding: 0 ">
                    <li class="nav-item px-3 py-4"><a class="nav-link text-white" href="../../src/index.php">HOME</a></li>

                    <li class="nav-item px-3 py-4">
                        <div class=" dropdown">
                            <a class="nav-link text-white dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown">
                                PRODUCTOS
                            </a>
                            <div class="dropdown-menu shadow" style="border-radius: 0; border: none; background-color: #eee/#475a68" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item nav-link text-black " href="../../src/camisasMain.php">ACCESORIOS</a>
                                <a class="dropdown-item nav-link text-black " href="../../src/impermeableMain.php">REPUESTOS</a>
                                <a class="dropdown-item nav-link text-black " href="../../src/impermeableMain.php">LLANTAS</a>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item px-3 py-4">
                    <div class=" dropdown">
                            <a class="nav-link text-white dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown">
                                TEXTILES
                            </a>
                            <div class="dropdown-menu shadow" style="border-radius: 0; border: none; background-color: #eee/#475a68" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item nav-link text-black " href="../../src/camisasMain.php">SUETAS</a>
                                <a class="dropdown-item nav-link text-black " href="../../src/camisasMain.php">CAMISAS</a>
                                <a class="dropdown-item nav-link text-black " href="../../src/impermeableMain.php">GUANTES</a>
                                <a class="dropdown-item nav-link text-black " href="../../src/impermeableMain.php">UNIFORMES</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item px-3 py-4">
                        <div class=" dropdown">
                            <a class="nav-link text-white dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown">
                                ABOUT
                            </a>
                            <div class="dropdown-menu shadow" style="border-radius: 0; border: none; background-color: #eee/#475a68" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item nav-link text-black " href="../../src/contact.php">CONTACTOS</a>
                                <a class="dropdown-item nav-link text-black" href="../../src/about.php">NOSOTROS</a>
                            </div>
                        </div>
                    </li>
                    <?php
                    if (isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'ROLE_ADMIN') {
                        echo ' 
                        <li class="nav-item px-3 py-4">
                            <div class="dropdown">
                                <a class="nav-link text-white dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown">
                                    ADMIN
                                </a>
                                <div class="dropdown-menu shadow" style="border-radius: 0; border: none; background-color: #eee/#475a68" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item nav-link text-black" href="../../src/adminRepuestos.php">REPUESTOS</a>
                                    <a class="dropdown-item nav-link text-black" href="../../src/adminCamisas.php">CAMISAS</a>
                                    <a class="dropdown-item nav-link text-black" href="../../src/adminImpermeables.php">IMPERMEABLES</a>
                                    <a class="dropdown-item nav-link text-black" href="../../src/adminVentas.php">REPORTE VENTAS</a>
                                    <a class="dropdown-item nav-link text-black" href="../../src/adminFacturas.php">REPORTE FACTURAS</a>
                                </div>
                            </div>
                        </li>';
                    }
                    ?>
                    <li class="nav-item px-3 py-4"><a class="nav-link text-white" href="../../src/carrito.php"><i class="fa-solid fa-cart-shopping"></i></a></li>
                </ul>
            </div>
            <div class="user-headerDiv">
                <?php if (isset($_SESSION['usuario'])) : ?>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle " type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i> <?php echo isset($_SESSION['usuario']['user_name']) ? $_SESSION['usuario']['user_name'] : 'Invitado'; ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="../userSrc/logout.php">Cerrar sesión</a></li>
                        </ul>
                    </div>
                <?php else : ?>
                    <a href="../userSrc/login.php" class="a-header-user">
                        <p class="user-log">Iniciar sesión</p>
                    </a>
                <?php endif; ?>
            </div>

        </div>
    </nav>