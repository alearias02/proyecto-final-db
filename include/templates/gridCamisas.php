<div class='row'>
  <h2 class='text-center justify-content-center'>Camisas</h2>
</div>
<div class='row row-cols-1 row-cols-md-3 m-3 g-4' id="camisas-container">
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  $.ajax({
    url: '../include/functions/recogeCamisas.php', 
    type: 'GET', 
    dataType: 'json', 
    success: function(data) { 
      console.log("Datos recibidos:", data);

      if (data && data.length > 0) {
        $('#camisas-container').empty();

        $.each(data, function(index, CAMISA) {
            const IMAGE_PATH = CAMISA.IMAGE_PATH ? CAMISA.IMAGE_PATH.replace('..', '../') : '../../img/default.jpg';
            const DESCRIPTION = CAMISA.DESCRIPTION || 'Sin descripción';
            const DETALLE = CAMISA.DETALLE || 'Detalles no disponibles';
            const PRECIO = CAMISA.PRECIO !== null ? `Precio: $${CAMISA.PRECIO}` : 'Precio no disponible';

            var camisaHTML = `
                <div class="col">
                <div class="card text-dark bg-light h-100 shadow">
                    <div class="image-container">
                    <img src="${IMAGE_PATH}" class="card-img-top" alt="Imagen Camisa">
                    </div>
                    <div class="card-body d-flex flex-column align-items-center text-center">
                    <h3 class="card-title fw-bold">${DESCRIPTION}</h3>
                    <p>${DETALLE}</p>
                    <p class="fw-bold">Precio: $${CAMISA.PRECIO}</p>
                    <div class="btn-group mt-auto w-100 gap-2 d-flex flex-column flex-md-row">
                                        <a href="../srcItem/mostrarCamisa.php?ID_CAMISA=${CAMISA.PRODUCT_ID}" class="btn btn-primary mt-auto w-100">Ver más</a>
                                        <button class="btn btn-success add-to-cart" data-id="${CAMISA.PRODUCT_ID}">
                                          <i class="fas fa-cart-plus"></i> Agregar al carrito
                                        </button>
                                      </div>
                    </div>
                </div>
                </div>`;
            
            $('#camisas-container').append(camisaHTML);
            });
      } else {
        $('#camisas-container').html('<p>No hay productos disponibles</p>');
      }
    },
    error: function(xhr, status, error) { 
      console.error('Error al obtener las camisas:', error);
      $('#camisas-container').html('<p>Ocurrió un error al obtener los productos</p>');
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

