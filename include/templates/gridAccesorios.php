<div class='row'>
  <h2 class='text-center justify-content-center'>Accesorios</h2>
</div>
<div class='row row-cols-1 row-cols-md-3 m-3 g-4' id="accesorios-container">
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  $.ajax({
    url: '../include/functions/recogeAccesorios.php', 
    type: 'GET', 
    dataType: 'json', 
    success: function(data) { 
      console.log("Datos recibidos:", data);

      if (data && data.length > 0) {
        $('#accesorios-container').empty();

        $.each(data, function(index, ACCESORIO) {
          console.log("Accesorio:", ACCESORIO);

          const IMAGE_PATH = ACCESORIO.IMAGE_PATH ? ACCESORIO.IMAGE_PATH.replace('..', '../') : '../../img/default.jpg';
          const DESCRIPCION = ACCESORIO.DESCRIPTION || 'Sin descripción';
          const DETALLE = ACCESORIO.DETALLE || 'Detalles no disponibles';
          const PRECIO = ACCESORIO.PRECIO !== null ? `Precio: $${ACCESORIO.PRECIO}` : 'Precio no disponible';
          
          var accesorioHTML = `<div class="col">
                                  <div class="card text-dark bg-light h-100 shadow">
                                    <div class="image-container">
                                      <img src="${IMAGE_PATH}" class="card-img-top" alt="Imagen Accesorio">
                                    </div>
                                    <div class="card-body d-flex flex-column m-auto align-items-center">
                                      <h3 class="card-title text-center">${DESCRIPCION}</h3>
                                      <p class="text-center">${DETALLE}</p>
                                      <p class="text-center"><strong>${PRECIO}</strong></p>
                                      <div class="btn-group mt-auto w-100 gap-2 d-flex flex-column flex-md-row">
                                        <a href="../srcItem/mostrarAccesorio.php?PRODUCT_ID=${ACCESORIO.PRODUCT_ID}" class="btn btn-primary">
                                          Ver más
                                        </a>
                                        <button class="btn btn-success add-to-cart" data-id="${ACCESORIO.PRODUCT_ID}">
                                          <i class="fas fa-cart-plus"></i> Agregar al carrito
                                        </button>
                                      </div>
                                    </div>
                                  </div>
                                </div>`;
          $('#accesorios-container').append(accesorioHTML);
        });
      } else {
        $('#accesorios-container').html('<p>No hay productos disponibles</p>');
      }
    },
    error: function(xhr, status, error) { 
      console.error('Error al obtener los productos:', error);
      $('#accesorios-container').html('<p>Ocurrió un error al obtener los productos</p>');
    }
  });
});

$(document).on('click', '.add-to-cart', function() {
  const productId = $(this).data('id');

  $.ajax({
    url: '../include/functions/addToCart.php', 
    type: 'POST',
    dataType: 'json',
    data: { product_id: productId },
    success: function(response) {
      if (response.success) {
        console.log('Producto asignado al carrito ', response)
        alert('Producto agregado al carrito 🛒');
      } else if (response.error) {
        alert('Error: ' + response.error);
      }
    },
    error: function(xhr, status, error) {
      console.log('Error AJAX:', error);
      alert('Hubo un error al agregar el producto.');
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
