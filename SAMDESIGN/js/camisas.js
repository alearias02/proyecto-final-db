$(document).ready(function() {
    $.ajax({
        url: '../../include/functions/recogeCamisas.php', 
        type: 'GET', 
        dataType: 'json', 
        success: function(data) { 
            console.log(data)
            if (data && data.length > 0) {
                $.each(data, function(index, camisa) {
                    var camisaHTML = `
                    <div class="col-md-4 mb-4">
                        <div class="card text-dark bg-light h-100">
                            <div style="height: 250px; overflow: hidden;">
                                <img src="${camisa.ruta_imagen}" class="card-img-top" alt="Imagen Camisa" style="object-fit: cover; width: 100%; height: 100%;">
                            </div>
                            <div class="card-body d-flex flex-column justify-content-between">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item text-center justify-content-center"><h2 class="card-title">${camisa.descripcion}</h5></li>
                                    <li class="list-group-item text-center justify-content-center">${camisa.detalle}</li>  
                                    <li class="list-group-item">Precio: ${camisa.precio}</li>
                                    <li class="list-group-item">Existencias: ${camisa.existencias}</li>
                                </ul>
                                <a href="../srcItem/mostrarCamisa.php?id_camisa=${camisa.id_camisa}" class="btn btn-primary mt-3">Ver más</a>
                            </div>
                        </div>
                    </div>`;
                    
                    $('#camisas-container').append(camisaHTML);
                });
            } else {
                $('#camisas-container').html('<p>No hay productos</p>');
            }
        },
        error: function(xhr, status, error) { 
            console.error('Error al obtener las camisas:', error);
            $('#camisas-container').html('<p>Ocurrió un error al obtener los productos</p>');
        }
    });
});
