<div class='row'>
  <h2 class='text-center justify-content-center'>Llantas</h2>
</div>
<div class='row row-cols-1 row-cols-md-3 m-3 g-4' id="llantas-container">
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  $.ajax({
    url: '../include/functions/recogeLlantas.php', 
    type: 'GET', 
    dataType: 'json', 
    success: function(data) { 
      console.log("Datos recibidos:", data);

      if (data && data.length > 0) {
        $('#llantas-container').empty();

        $.each(data, function(index, LLANTA) {
          console.log("Llanta:", LLANTA);

          const IMAGE_PATH = LLANTA.IMAGE_PATH ? LLANTA.IMAGE_PATH.replace('..', '../') : '../../img/default.jpg';
          const DESCRIPCION = LLANTA.DESCRIPTION || 'Sin descripción';
          const DETALLE = LLANTA.DETALLE || 'Detalles no disponibles';
          const PRECIO = LLANTA.PRECIO !== null ? `Precio: $${LLANTA.PRECIO}` : 'Precio no disponible';
          
          var llantaHTML = `
                            <div class="col">
                              <div class="card text-dark bg-light h-100 shadow">
                                <div class="image-container">
                                  <img src="${IMAGE_PATH}" class="card-img-top" alt="Imagen Llanta">
                                </div>
                                <div class="card-body d-flex flex-column m-auto align-items-center">
                                  <h3 class="card-title text-center">${DESCRIPCION}</h3>
                                  <p class="text-center">${DETALLE}</p>
                                  <p class="text-center"><strong>${PRECIO}</strong></p>
                                  <a href="../srcItem/mostrarLlanta.php?PRODUCT_ID=${LLANTA.PRODUCT_ID}" class="btn btn-primary mt-auto">Ver más</a>
                                </div>
                              </div>
                            </div>`;
          $('#llantas-container').append(llantaHTML);
        });
      } else {
        $('#llantas-container').html('<p>No hay productos disponibles</p>');
      }
    },
    error: function(xhr, status, error) { 
      console.error('Error al obtener los productos:', error);
      $('#llantas-container').html('<p>Ocurrió un error al obtener los productos</p>');
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
