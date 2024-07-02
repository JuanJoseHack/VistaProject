var URL = 'http://localhost:8081/api-ti';
loadCompras();
loadProveedores();
var detail = Array();
function loadProveedores() {
    $.ajax({
        url: URL + '/proveedores',
        type: 'GET',
        success: function (response) {
            var html = '<option value="">Seleccione...</option>';
            $.each(response, function (i, item) {
                html += '<option value="' + response[i].id + '">' + response[i].nombre + '</option>';
            });
            $('#id_proveedor').html(html);
        },
        error: function (error) {
            console.log(error.responseText)
        }
    });
}

function loadProductos() {
    $.ajax({
        url: URL + '/productos',
        type: 'GET',
        success: function (response) {
            var html = '';
            $.each(response, function (i, item) {
                html += `<tr>
                                        <td>${response[i].nombre}</td>
                                        <td><input type="text" style="width: 60px;" class="form-control" id="cantidad${response[i].id}"></td>
                                        <td><input type="text"  style="width: 60px;" class="form-control" id="precio${response[i].id}"></td>
                                        <td><button type="button" class="btn btn-success" onclick="addProductToList(${response[i].id}, '${response[i].nombre}')">Agregar</button></td>
                                    </tr>`;
            });
            $('#dataProducts').html(html);
        },
        error: function (error) {
            console.log(error.responseText)
        }
    });
}


function addProductToList(id, nombre) {
    var cantidad = $("#cantidad" + id).val();
    var precio = $("#precio" + id).val();
    if(!$('#product'+id).html()){
        $("#dataCartProducts").append(`<tr id="product${id}">
                                            <td>${nombre}</td>
                                            <td>${cantidad}</td>
                                            <td>S/. ${precio}</td>
                                            <td><button type="button" onclick="deleteProductCart(${id})" class="btn btn-danger">Eliminar</button></td>
                                        </tr>`);
                                        
    detail.push({
        id_producto:id,
        cantidad: cantidad,
        precio: precio,
        estado: 1
    })
    }else{
        alert('producto ya esta en la lista')
    }
}

function deleteProductCart(id){
    $('#product'+id).remove();
    // Buscamos el índice del elemento con id igual a idToDelete
var indexToDelete = detail.findIndex(function(item) {
    return item.id_producto === id;
});

// Verificamos si encontramos el elemento con el id especificado
if (indexToDelete !== -1) {
    // Usamos el método splice para eliminar el elemento en el índice indexToDelete
    detail.splice(indexToDelete, 1);
    console.log('Elemento eliminado correctamente.');
} else {
    console.log('No se encontró ningún elemento con el id especificado.');
}
}

function loadCompras() {
    $.ajax({
        url: URL + '/compras', // Reemplaza esta URL con la URL de tu API
        type: 'GET',
        success: function (response) {
            var html = '';
            if (response.length === 0) {
                html += '<tr><td colspan="7" class="text-center">No hay datos disponibles</td></tr>';
            } else {
                $.each(response, function (i, item) {
                    html += `<tr>
                            <td>${response[i].id}</td>
                            <td>${response[i].codigoRemision}</td>
                            <td>${response[i].fecha}</td>
                            <td>${response[i].proveedor != null ? response[i].proveedor.nombre : ''}</td>
                            <td>
                                <button class='btn btn-warning btn-sm' onclick='editarCompra(${response[i].id}, \"${response[i].codigoRemision}\", \"${response[i].fecha}\", \"${response[i].estado}\", \"${response[i].proveedor != null ? response[i].proveedor.id : ''}\")'>Editar</button>
                                <button class='btn btn-danger btn-sm' onclick='eliminarCompra(${response[i].id})'>Eliminar</button>
                            </td>
                        </tr>`;
                });
            }
            $('#resultdata').html(html);
        },
        error: function (xhr, status, error) {
            console.error('Error:', error);
            alert('Ocurrió un error al obtener los datos.');
        }
    });
};
// Funcipon para listar los registros de compras
// Función para llenar el formulario de edición con los datos de la compra seleccionada
function editarCompra(id, codigo_remision, fecha, estado, id_proveedor) {
loadProductos();
detail.length = [];
$("#dataCartProducts").html('');
    document.getElementById('id').value = id;
    document.getElementById('codigo_remision').value = codigo_remision;
    document.getElementById('id_proveedor').value = id_proveedor;
    var fecha = new Date(fecha);

    var anio = fecha.getFullYear();
    var mes = ('0' + (fecha.getMonth() + 1)).slice(-2); // Obtener el mes y asegurar que tiene dos dígitos
    var dia = ('0' + fecha.getDate()).slice(-2); // Obtener el día y asegurar que tiene dos dígitos
    var hora = ('0' + fecha.getHours()).slice(-2); // Obtener el día y asegurar que tiene dos dígitos
    var minuto = ('0' + fecha.getMinutes()).slice(-2); // Obtener el día y asegurar que tiene dos dígitos

    var fechaConvertida = anio + '-' + mes + '-' + dia + ' ' + hora + ':' + minuto;

    document.getElementById('fecha').value = fechaConvertida;
    if (estado == 1) {
        $('#estado').prop("checked", true);
    } else {
        $('#estado').prop("checked", false);
    }

    $('#createModal').modal('show');
}

$('#add-compra').click(function (e) {
    $('#id').val('');
    $('#codigo_remision').val('');
    $('#fecha').val('');
    $('#id_proveedor').val('');
    $('#estado').val('');
    $('#createModal').modal('show');
    loadProductos();
    detail.length = [];
    $("#dataCartProducts").html('');
})


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
                url: URL + `/compras/${id}`,
                data: '',
                success: function (response) {
                    loadCompras();
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Registro eliminado exitosamente",
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                error: function (xhr, status, error) {
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

$('#createForm').submit(function (e) {
    e.preventDefault();
    if ($('#id').val().length > 0) {
        const data = {
            codigoRemision: $('#codigo_remision').val(),
            fecha: $('#fecha').val(),  // Ajusta la fecha según el formato correcto
            proveedor: {
                id: parseInt($('#id_proveedor').val())
            },
            estado: document.getElementById('estado').checked ? 1 : 0
        };
        $.ajax({
            url: URL + `/compras/${$('#id').val()}`, // Reemplaza esta URL con la URL de tu API
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function (response) {
                loadCompras();
                $('#createModal').modal('hide');
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Registro actualizado exitosamente",
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            error: function (xhr, status, error) {
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
    } else {
        const data = {
            codigo_remision: $('#codigo_remision').val(),
            fecha: $('#fecha').val(),  // Ajusta la fecha según el formato correcto
            proveedor: {
                id: parseInt($('#id_proveedor').val())
            },
            estado: document.getElementById('estado').checked ? 1 : 0
        };
        $.ajax({
            url: URL + '/compras', // Reemplaza esta URL con la URL de tu API
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function (response) {
                detail.forEach(function(item) {
                    const datadetail = {
                        cantidad: item.cantidad,
                        precio: item.precio,  // Ajusta la fecha según el formato correcto
                        producto: {
                            id: item.id_producto
                        },
                        compra: {
                            id: response.id
                        }
                    };
                    $.ajax({
                        url: URL+ '/DetallesCompra',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify(datadetail),
                        success: function (data) {
                            console.log('Datos enviados correctamente:', data);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error al enviar datos:', error);
                        }
                    });
                })
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
            error: function (xhr, status, error) {
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
    }

})


