<div class="row row-cols-4 row-cols-sm-4 text-center justify-content-center">
    <div class="card m-2 px-0" foreach $oRepuesto >
        <div class="card-body text-center justify-content-center " style="background-color: #a3bfd7; color: #EEEEEE">
            <h5 class="card-title"><?php  ?></h5>
            <figure>
                <img th:src="@{${repuesto.rutaImagen}}" width="251px" height="254px" />
                <figcaption>[[${repuesto.precio}+' ('+${repuesto.existencias}+')']]
                </figcaption>
            </figure>
            <a th:href="@{/mostrarRepuesto/mostrar/}+${repuesto.idRepuesto}" class="btn boton-bs" style="background-color: #bbb;">
                <i class="fas fa-plus"> Ver mas</i>
            </a>
            <form>
                <input type="hidden" name="texto" th:value="${repuesto.idRepuesto}" />
                <input type="hidden" name="texto" th:value="${repuesto.existencias}" />
                <button class="btn btn-ligth" id="addCar" name="addCar" onclick="addCard(this.form, 'Repuestos')" type="button"><i class="fas fa-cart-plus"></i></button>
            </form>
            <p class="card-text">[[${repuesto.detalle}]]</p>
        </div>
    </div>
</div>