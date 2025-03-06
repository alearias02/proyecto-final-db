<section class="container my-5">
    <div class="row align-items-center justify-content-center">
        
        <!-- Formulario a la izquierda -->
        <div class="col-md-12 col-lg-6">
            <h2 class="text-center text-white mb-4">¿Quieres algo personalizado?</h2>
            <form class="p-4 rounded shadow bg-light" method="post" action="procesar-formulario.php">
                <h3 class="text-center text-black mb-3">¡Te lo hacemos por ti!</h3>

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" placeholder="Escribe tu nombre" id="nombre">
                </div>

                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <div class="d-flex gap-2">
                        <input type="text" name="apellido1" class="form-control" placeholder="1er apellido">
                        <input type="text" name="apellido2" class="form-control" placeholder="2do apellido">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="correo" class="form-label">Correo</label>
                    <input type="email" name="correo" class="form-control" placeholder="correo@dominio.com" id="correo">
                </div>

                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <div class="input-group">
                        <span class="input-group-text">+506</span>
                        <input type="number" name="telefono" class="form-control" placeholder="88881111" id="telefono">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="cantidades" class="form-label">Cantidad mínima</label>
                    <input type="number" name="cantidades" class="form-control" placeholder="Mínimo 15 unidades" min="15" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tallas disponibles:</label>
                    <div class="d-flex flex-wrap gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tallas[]" id="check1" value="XS">
                            <label class="form-check-label" for="check1">XS</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tallas[]" id="check2" value="S">
                            <label class="form-check-label" for="check2">S</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tallas[]" id="check3" value="M">
                            <label class="form-check-label" for="check3">M</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tallas[]" id="check4" value="L">
                            <label class="form-check-label" for="check4">L</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tallas[]" id="check5" value="XL">
                            <label class="form-check-label" for="check5">XL</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Cuéntanos lo que buscas:</label>
                    <textarea name="descripcion" class="form-control" placeholder="Describe tu idea..." rows="3"></textarea>
                </div>

                <div class="text-center">
                    <button class="btn btn-success w-100" type="submit">Enviar solicitud</button>
                </div>
            </form>
        </div>
        
        <!-- Carrusel -->
        <div class="col-md-12 col-lg-6 d-flex flex-column justify-content-center align-items-center" style="height: 100%;">
            <div class="w-100 text-center mb-3">
                <h2 class="text-white p-3 rounded shadow" style="background-color: var(--primarioOscuro);">Nuestros diseños</h2>
            </div>
            <div class="carousel slide shadow rounded" id="carouselEjemplo" data-bs-ride="carousel" style="width: auto; height: auto;">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselEjemplo" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#carouselEjemplo" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#carouselEjemplo" data-bs-slide-to="2"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="../img/sueta1.jpg" class="d-block" style="max-width: 100%; height: auto; object-fit: cover;" alt="Ejemplo de diseño 1">
                    </div>
                    <div class="carousel-item">
                        <img src="../img/sueta2.jpg" class="d-block" style="max-width: 100%; height: auto; object-fit: cover;" alt="Ejemplo de diseño 2">
                    </div>
                    <div class="carousel-item">
                        <img src="../img/sueta3.jpg" class="d-block" style="max-width: 100%; height: auto; object-fit: cover;" alt="Ejemplo de diseño 3">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselEjemplo" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselEjemplo" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>

    </div>
</section>