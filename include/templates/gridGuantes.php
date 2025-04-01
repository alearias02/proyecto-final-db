<div class='row'>
    <h2 class='text-center justify-content-center'>Guantes</h2>
</div>
<div class='row row-cols-1 row-cols-md-3 m-3 g-4' id="guantes-container">
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $.ajax({
        url: '../include/functions/recogeGuantes.php', 
        type: 'GET', 
        dataType: 'json', 
        success: function(data) { 
            console.log("Datos recibidos:", data);

            if (data && data.length > 0) {
                $('#guantes-container').empty();

                $.each(data, function(index, GUANTE) {
                    console.log("Guante:", GUANTE); // Agregado para depuración

                    const IMAGE_PATH = GUANTE.IMAGE_PATH ? GUANTE.IMAGE_PATH.replace('..', '../') : '../../img/default.jpg';
                    const DESCRIPCION = GUANTE.DESCRIPTION || 'Sin descripción';
                    const DETALLE = GUANTE.DETALLE || 'Detalles no disponibles';
                    const PRECIO = GUANTE.PRECIO !== null ? `Precio: $${GUANTE.PRECIO}` : 'Precio no disponible';
                    
                    var camisaHTML = `<div class="col-md-4 mb-4">
                                            <div class="card text-dark bg-light h-100">
                                                <div style="height: 250px; overflow: hidden;">
                                                    <img src="${IMAGE_PATH}" class="card-img-top" alt="Imagen Guante" style="object-fit: cover; width: 100%; height: 100%;">
                                                </div>
                                                <div class="card-body d-flex flex-column justify-content-between">
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item text-center"><h5 class="card-title">${DESCRIPCION}</h5></li>
                                                        <li class="list-group-item text-center">${DETALLE}</li>  
                                                        <li class="list-group-item">${PRECIO}</li>
                                                    </ul>
                                                    <a href="../srcItem/mostrarGuante.php?PRODUCT_ID=${GUANTE.PRODUCT_ID}" class="btn btn-primary mt-3">Ver más</a>
                                                </div>
                                            </div>
                                        </div>`;

                    $('#guantes-container').append(camisaHTML);
                });
            } else {
                $('#guantes-container').html('<p>No hay productos disponibles</p>');
            }
        },
        error: function(xhr, status, error) { 
            console.error('Error al obtener las camisas:', error);
            console.log("xhr:",xhr);
            console.log("status:",status);
            console.log("error:",error);
            $('#guantes-container').html('<p>Ocurrió un error al obtener los productos</p>');
        }
    });
});
</script>