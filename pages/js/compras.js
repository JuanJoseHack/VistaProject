var URL = 'http://ti.app.informaticapp.com:4170/api-ti/compras';
loadCompras();
function loadCompras(){
    $.ajax({
            url: URL, // Reemplaza esta URL con la URL de tu API
            type: 'GET',
            success: function(response) {
                console.log(response);
                var html = '';
                if (response.length === 0) {
                    html += '<tr><td colspan="7" class="text-center">No hay datos disponibles</td></tr>';
                  } else {
                $.each(response, function(i, item){
                    html += `<tr>
                            <td>${response[i].id}</td>
                            <td>${response[i].codigoRemision}</td>
                            <td>${response[i].fecha}</td>
                            <td>${response[i].devolucion}</td>
                            <td>${response[i].proveedor!=null?response[i].proveedor.nombre:''}</td>
                            <td>
                                <button class='btn btn-warning btn-sm' onclick='editarCompra(${response[i].id}, \"${response[i].codigoRemision}\", \"${response[i].fecha}\", \"${response[i].devolucion}\", \"${response[i].proveedor!=null?response[i].proveedor.id:''}\")'>Editar</button>
                                <button class='btn btn-danger btn-sm' onclick='eliminarCompra(${response[i].id})'>Eliminar</button>
                            </td>
                        </tr>`;
                });
            }
                $('#resultdata').html(html);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Ocurrió un error al obtener los datos.');
            }
        });
};
// Funcipon para listar los registros de compras
// Función para llenar el formulario de edición con los datos de la compra seleccionada
function editarCompra(id, codigo_remision, fecha, devolucion, id_proveedor) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_codigo_remision').value = codigo_remision;
    document.getElementById('edit_devolucion').value = devolucion;
    document.getElementById('edit_id_proveedor').value = id_proveedor;
    var fecha = new Date(fecha);

    var anio = fecha.getFullYear();
    var mes = ('0' + (fecha.getMonth() + 1)).slice(-2); // Obtener el mes y asegurar que tiene dos dígitos
    var dia = ('0' + fecha.getDate()).slice(-2); // Obtener el día y asegurar que tiene dos dígitos

    var fechaConvertida = anio + '-' + mes + '-' + dia;
    
    document.getElementById('edit_fecha').value = fechaConvertida;

    $('#editModal').modal('show');
}

// Función para manejar la eliminación de una compra
function eliminarCompra(id) {
    Swal.fire({
        title: "Estas seguro?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'DELETE',
                url: URL+`/${id}`,
                data: '',
                success: function(response) {
                    loadCompras();
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Registro eliminado exitosamente",
                        showConfirmButton: false,
                        timer: 1500
                      });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "Registro no se pudo eliminar",
                        showConfirmButton: false,
                        timer: 1500
                      });
                }
            });
        }
      });
}

$('#createForm').submit(function(e){
    e.preventDefault();
        var datosFormulario = new FormData(this);
        $.ajax({
            url: URL, // Reemplaza esta URL con la URL de tu API
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(datosFormulario),
            success: function(response) {
                loadCompras();
                $('#createModal').modal('hide');
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Registro creado exitosamente",
                    showConfirmButton: false,
                    timer: 1500
                  });
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Registro no se pudo crear",
                    showConfirmButton: false,
                    timer: 1500
                  });
            }
        });
})

$('#editForm').submit(function(e){
    e.preventDefault();
    var datosFormulario = new FormData(this);
    $.ajax({
            url: URL +`/${$('#edit_id').val()}`, // Reemplaza esta URL con la URL de tu API
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(datosFormulario),
            success: function(response) {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Registro actualizado exitosamente",
                    showConfirmButton: false,
                    timer: 1500
                  });
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Registro no se pudo editar",
                    showConfirmButton: false,
                    timer: 1500
                  });
            }
        });
})