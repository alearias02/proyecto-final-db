$(document).ready(function() {
    $.ajax({
        url: '../../include/functions/recogeRepuestos.php', 
        type: 'GET', 
        dataType: 'json', 
        success: function(data) { 
            console.log(data)
            if (data && data.length > 0) {
                $.each(data, function(index, repuesto) {
                    var repuestoHTML = `
                    <div class="col-md-4 mb-4">
                        <div class="card text-dark bg-light h-100">
                            <div style="height: 250px; overflow: hidden;">
                                <img src="${repuesto.ruta_imagen}" class="card-img-top" alt="Imagen Repuesto" style="object-fit: cover; width: 100%; height: 100%;">
                            </div>
                            <div class="card-body d-flex flex-column justify-content-between">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item text-center justify-content-center"><h2 class="card-title">${repuesto.descripcion}</h5></li>
                                    <li class="list-group-item text-center justify-content-center">${repuesto.detalle}</li>  
                                    <li class="list-group-item">Precio: ${repuesto.precio}</li>
                                    <li class="list-group-item">Existencias: ${repuesto.existencias}</li>
                                    <a href="../srcItem/mostrarRepuesto.php?id_repuesto=${repuesto.id_repuesto}" class="btn btn-primary mt-3">Ver más</a>
                                </ul>
                            </div>
                        </div>
                    </div>`;
                    
                    $('#repuesto-container').append(repuestoHTML);
                });
            } else {
                $('#repuesto-container').html('<p>No hay productos</p>');
            }
        },
        error: function(xhr, status, error) { 
            console.error('Error al obtener los repuestos:', error);
            $('#repuesto-container').html('<p>Ocurrió un error al obtener los productos</p>');
        }
    });
});
