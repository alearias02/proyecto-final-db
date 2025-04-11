<div class='row'>
  <h2 class='text-center justify-content-center'>Repuestos</h2>
</div>
<div class='row row-cols-1 row-cols-md-3 m-3 g-4' id="repuestos-container">
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  $.ajax({
    url: '../include/functions/recogeRepuestos.php', 
    type: 'GET', 
    dataType: 'json', 
    success: function(data) { 
      console.log("Datos recibidos:", data);

      if (data && data.length > 0) {
        $('#repuestos-container').empty();

        $.each(data, function(index, REPUESTO) {
          console.log("Repuesto:", REPUESTO);

          const IMAGE_PATH = REPUESTO.IMAGE_PATH ? REPUESTO.IMAGE_PATH.replace('..', '../') : '../../img/default.jpg';
          const DESCRIPCION = REPUESTO.DESCRIPTION || 'Sin descripción';
          const DETALLE = REPUESTO.DETALLE || 'Detalles no disponibles';
          const PRECIO = REPUESTO.PRECIO !== null ? `Precio: $${REPUESTO.PRECIO}` : 'Precio no disponible';
          
          var repuestoHTML = `
                            <div class="col">
                              <div class="card text-dark bg-light h-100 shadow">
                                <div class="image-container">
                                  <img src="${IMAGE_PATH}" class="card-img-top" alt="Imagen Repuesto">
                                </div>
                                <div class="card-body d-flex flex-column m-auto align-items-center">
                                  <h3 class="card-title text-center">${DESCRIPCION}</h3>
                                  <p class="text-center">${DETALLE}</p>
                                  <p class="text-center"><strong>${PRECIO}</strong></p>
                                  <a href="../srcItem/mostrarRepuesto.php?PRODUCT_ID=${REPUESTO.PRODUCT_ID}" class="btn btn-primary mt-auto">Ver más</a>
                                </div>
                              </div>
                            </div>`;
          $('#repuestos-container').append(repuestoHTML);
        });
      } else {
        $('#repuestos-container').html('<p>No hay productos disponibles</p>');
      }
    },
    error: function(xhr, status, error) { 
      console.error('Error al obtener los productos:', error);
      $('#repuestos-container').html('<p>Ocurrió un error al obtener los productos</p>');
    }
  });
});
</script>

<style>
/* Contenedor de imagen ajustado */
.image-container {
  height: 250px;
  overflow: hidden;
}

.image-container img {
  object-fit: cover;
  width: 100%;
  height: 100%;
}

/* Alineación y espacio */
.card {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  border-radius: 12px;
  overflow: hidden;
}

.card-body {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

/* Botón siempre abajo */
.btn {
  width: 100%;
  margin-top: auto;
}

/* Agrega un efecto de sombra para mejorar el diseño */
.shadow {
  box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}
</style>
