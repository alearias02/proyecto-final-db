<?php
require_once "../include/templates/header.php";
$page = "about";
?>

<h2 class='text-center justify-content-center '>SAM DESING</h2>
<h3 class='text-center justify-content-center '>Sobre Nosotros</h3>
<section>
    <div class="row justify-content-around align-items-center">
        <div class="col-sm-6 ">
            <img src="../img/sam_desing-removebg-preview.png" alt="" />
        </div>
        <div class="col-sm-4 text-center">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam tincidunt quam a leo tincidunt egestas. Etiam dignissim sollicitudin tincidunt.
                Cras metus lacus, vehicula sed rutrum ut, lobortis vitae risus. Vestibulum convallis enim lorem, id porta urna pharetra et. Donec nec nulla ante.
                Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
            </p>
        </div>
    </div>
</section>

<section>
    <h1 class="text-center">Por qué comprar con nosotros?</h1>

    <div class="row justify-content-center">
        <div class="col-md-4 p-3 m-3 text-center">
            <img src="../img/fast-delivery.png" alt="ICON 1" class="img-fluid mb-3" style="max-width: 100px;">
            <h2 class="mb-3">Envío Inmediato</h2>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque bibendum neque id libero faucibus, at congue tortor aliquet.
                Nulla vel malesuada sapien. In tempus eros et massa porta, ut venenatis lacus suscipit.
            </p>
        </div>
        <div class="col-md-4 p-3 m-3 text-center">
            <img src="../img/shirt.png" alt="ICON 2" class="img-fluid mb-3" style="max-width: 100px;">
            <h2 class="mb-3">Servicio Personalizado</h2>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque bibendum neque id libero faucibus, at congue tortor aliquet.
                Nulla vel malesuada sapien. In tempus eros et massa porta, ut venenatis lacus suscipit.
            </p>
        </div>
        <div class="col-md-4 p-3 m-3 text-center">
            <img src="../img/best-price.png" alt="ICON 3" class="img-fluid mb-3" style="max-width: 100px;">
            <h2 class="mb-3">El Mejor Precio</h2>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque bibendum neque id libero faucibus, at congue tortor aliquet.
                Nulla vel malesuada sapien. In tempus eros et massa porta, ut venenatis lacus suscipit.
            </p>
        </div>
        <div class="col-md-4 p-3 m-3 text-center">
            <img src="../img/security-payment.png" alt="ICON 4" class="img-fluid mb-3" style="max-width: 100px;">
            <h2 class="mb-3">Con Pagos Seguros</h2>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque bibendum neque id libero faucibus, at congue tortor aliquet.
                Nulla vel malesuada sapien. In tempus eros et massa porta, ut venenatis lacus suscipit.
            </p>
        </div>
    </div>
</section>

<section>
    <div>
        <h2 class='text-center justify-content-center text-white'>Quieres algo personalizado?</h2>
        <form class="formulario_camisas rounded" method="post" action="procesar-formulario.php">
            <h3 class='text-center justify-content-center text-black'>¡Te lo hacemos por ti!</h3>
            <div class="input-group mb-3">
                <span class="input-group-text" for="nombre">Nombre</span>
                <input type="text" name="nombre" class="form-control" placeholder="Nombre" aria-label="Nombre" aria-describedby="nombre" id="nombre">
            </div>
            <div class="input-group">
                <span class="input-group-text" for="apellidos">Apellidos</span>
                <input type="text" name="apellido1" id="apellidos" placeholder="1er apellido" aria-label="Primer apellido" class="form-control">
                <input type="text" name="apellido2" id="apellidos" placeholder="2do apellido" aria-label="Segundo apellido" class="form-control">
            </div>

            <div class="input-group my-3">
                <span class="input-group-text" for="correo">Correo</span>
                <input type="email" name="correo" class="form-control" placeholder="correo@dominio.com" aria-label="Email" aria-describedby="correo" id="correo">
            </div>

            <div class="input-group my-3">
                <span class="input-group-text" for="telefono">Telefono</span><span class="input-group-text" id="inputGroupPrepend">+506</span>
                <input type="number" name="telefono" class="form-control" placeholder="88881111" aria-label="Telefono" aria-describedby="telefono" id="telefono">
            </div>

            <div class='input-group mb-3'>
                <label class="input-group-text mb-1 border-solid" for="cantidades">Cantidades:</label>
                <input type="number" name="cantidades" id="cantidades" class="form-control mb-1" placeholder="Cantidad" min="15" required="true" />
            </div>

            <div class="input-group mb-3">
                <label class="input-group-text mx-3" for="tallas">Tallas:</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="tallas[]" id="check1" value="XS">
                    <label class="form-check-label" for="check1">XS</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="tallas[]" id="check2" value="S">
                    <label class="form-check-label" for="check2">S</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="tallas[]" id="check3" value="M">
                    <label class="form-check-label" for="check3">M</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="tallas[]" id="check4" value="L">
                    <label class="form-check-label" for="check4">L</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="tallas[]" id="check5" value="XL">
                    <label class="form-check-label" for="check5">XL</label>
                </div>
            </div>

            <div class="form-group">
                <label class='text-black' for="exampleFormControlTextarea1 ">Cuentannos lo que andas buscando:</label>
                <div class="input-group">
                    <span class="input-group-text">Descripción</span>
                    <textarea name="descripcion" class="form-control" aria-label="With textarea"></textarea>
                </div>
            </div>

            <div class="col-12">
                <button class="btn btn-success" type="submit">Enviar</button>
            </div>
        </form>
    </div>
</section>

<?php
require_once "../include/templates/footer.php";
?>