<section>
    <div class="container-fluid my-4">
        <h2 class="text-center">Productos destacados</h2>
        <div class="row d-flex align-items-center g-0">
            
            <!-- Carrusel de camisas ocupando todo el lado izquierdo -->
            <div class="col-md-12 col-lg-7">
                <section class="carrusel_camisas rounded">
                    <div id="carouselExampleIndicators" class="carousel slide">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div>
                        <h2 class="display-6 text-center text-white" style="font-family: var(--fuentePrincipal); line-height: 60px;">
                            Nuestras camisas
                        </h2>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <a class="nav-link text-black" href="../src/camisasMain.php">
                                    <img src="../../img/grafico1.jpg" class="d-block w-100 rounded" style="height: 450px; object-fit: cover;" alt="Imagen 1 de carrusel" />
                                </a>
                            </div>
                            <div class="carousel-item">
                                <a class="nav-link text-black" href="../src/camisasMain.php">
                                    <img src="../../img/grafico2.jpg" class="d-block w-100 rounded" style="height: 450px; object-fit: cover;" alt="Imagen 2 de carrusel" />
                                </a>
                            </div>
                            <div class="carousel-item">
                                <a class="nav-link text-black" href="../src/camisasMain.php">
                                    <img src="../../img/grafico1.jpg" class="d-block w-100 rounded" style="height: 450px; object-fit: cover;" alt="Imagen 3 de carrusel" />
                                </a>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </section>
            </div>

            <!-- Grid de productos alineado a la derecha -->
            <div class="col-md-12 col-lg-5">
                
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 g-1">
                    <div class="col">
                        <div class="p-2 rounded text-white" style="background-color: var(--primarioOscuro);">
                            <img src="../img/llanta4.jpg" class="img-fluid rounded w-100" style="height: 120px; object-fit: cover;">
                            <h6 class="mt-2 text-white">Llanta de montaña</h6>
                            <h6>$75</h6>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-2 rounded text-white" style="background-color: var(--primarioOscuro);">
                            <a href="../../srcItem/mostrarRepuesto.php?id_repuesto=9">
                                <img src="../img/velocimetro.jpeg" class="img-fluid rounded w-100" style="height: 120px; object-fit: cover;">
                            </a>
                            <h6 class="mt-2 text-white">Velocímetro DT</h6>
                            <h6>$55</h6>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-2 rounded text-white" style="background-color: var(--primarioOscuro);">
                            <a href="../../srcItem/mostrarRepuesto.php?id_repuesto=7">
                                <img src="../img/tapas-frontales.jpg" class="img-fluid rounded w-100" style="height: 120px; object-fit: cover;">
                            </a>
                            <h6 class="mt-2 text-white">Plásticos Frontales</h6>
                            <h6>$55</h6>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-2 rounded text-white" style="background-color: var(--primarioOscuro);">
                            <a href="../../">
                                <img src="../img/llanta-continental.jpg" class="img-fluid rounded w-100" style="height: 120px; object-fit: cover;">
                            </a>
                            <h6 class="mt-2 text-white">Llanta continental</h6>
                            <h6>$75</h6>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-2 rounded text-white" style="background-color: var(--primarioOscuro);">
                            <a href="../../srcItem/mostrarRepuesto.php?id_repuesto=5">
                                <img src="../img/empaques-dt.jpg" class="img-fluid rounded w-100" style="height: 120px; object-fit: cover;">
                            </a>
                            <h6 class="mt-2 text-white">Empaques de DT</h6>
                            <h6>$35</h6>
                        </div>
                    </div>
                    <div class="col d-flex justify-content-center align-items-center">
                        <a href="/src/repuestosMain.php" class="text-decoration-none">
                            <i class="fas fa-plus-circle fa-4x text-white"></i> <!-- Ícono sin fondo -->
                            <h6 class="mt-2 text-white text-center">Ver más</h6>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
