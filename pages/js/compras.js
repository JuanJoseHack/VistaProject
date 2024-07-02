var URL = 'http://ti.app.informaticapp.com:4188/api-ti';

$(document).ready(function () {
    loadCompras();
    loadProveedores();
});

var detail = [];

function loadProveedores() {
    $.ajax({
        url: URL + '/proveedores',
        type: 'GET',
        success: function (response) {
            var html = '<option value="">Seleccione...</option>';
            $.each(response, function (i, item) {
                html += '<option value="' + item.id + '">' + item.nombre + '</option>';
            });
            $('#id_proveedor').html(html);
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar proveedores:', error);
            alert('Ocurrió un error al cargar los proveedores.');
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
                        <td>${item.nombre}</td>
                        <td><input type="text" style="width: 60px;" class="form-control" id="cantidad${item.id}"></td>
                        <td><input type="text" style="width: 60px;" class="form-control" id="precio${item.id}"></td>
                        <td><button type="button" class="btn btn-success" onclick="addProductToList(${item.id}, '${item.nombre}')">Agregar</button></td>
                    </tr>`;
            });
            $('#dataProducts').html(html);
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar productos:', error);
            alert('Ocurrió un error al cargar los productos.');
        }
    });
}

function addProductToList(id, nombre) {
    var cantidad = $("#cantidad" + id).val();
    var precio = $("#precio" + id).val();
    
    if (!$('#product' + id).html()) {
        $("#dataCartProducts").append(`<tr id="product${id}">
                                            <td>${nombre}</td>
                                            <td>${cantidad}</td>
                                            <td>S/. ${precio}</td>
                                            <td><button type="button" onclick="deleteProductCart(${id})" class="btn btn-danger">Eliminar</button></td>
                                        </tr>`);
                                        
        detail.push({
            id_producto: id,
            cantidad: cantidad,
            precio: precio,
            estado: 1
        });
    } else {
        alert('El producto ya está en la lista');
    }
}

function deleteProductCart(id) {
    $('#product' + id).remove();
    
    var indexToDelete = detail.findIndex(function(item) {
        return item.id_producto === id;
    });

    if (indexToDelete !== -1) {
        detail.splice(indexToDelete, 1);
        console.log('Elemento eliminado correctamente.');
    } else {
        console.log('No se encontró ningún elemento con el id especificado.');
    }
}

function loadCompras() {
    $.ajax({
        url: URL + '/compras',
        type: 'GET',
        success: function (response) {
            var html = '';
            if (response.length === 0) {
                html += '<tr><td colspan="7" class="text-center">No hay datos disponibles</td></tr>';
            } else {
                $.each(response, function (i, item) {
                    html += `<tr>
                            <td>${item.id}</td>
                            <td>${item.codigoRemision}</td>
                            <td>${item.fecha}</td>
                            <td>${item.proveedor ? item.proveedor.nombre : ''}</td>
                            <td>
                                <button class='btn btn-warning btn-sm' onclick='editarCompra(${item.id}, "${item.codigoRemision}", "${item.fecha}", ${item.estado}, ${item.proveedor ? item.proveedor.id : null})'>Editar</button>
                                <button class='btn btn-info btn-sm' onclick='DetalleCompra(${item.id})'>Detalles</button>
                                <button class='btn btn-danger btn-sm' onclick='eliminarCompra(${item.id})'>Eliminar</button>
                            </td>
                        </tr>`;
                });
            }
            $('#resultdata').html(html);
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar compras:', error);
            alert('Ocurrió un error al cargar las compras.');
        }
    });
}

function DetalleCompra(id) {
    $('#detalleModal').modal('show');
    
    $.ajax({
        url: URL + '/compras/' + id,
        type: 'GET',
        success: function (response) {
            $("#st-codigoRemision").text(response.codigoRemision);
            $("#st-fecha").text(response.fecha);
            $("#st-proveedor").text(response.proveedor ? response.proveedor.nombre : 'Sin proveedor');
            $("#st-estado").text(response.estado == 1 ? "Aprobado" : "Nulo");
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar detalle de compra:', error);
            alert('Ocurrió un error al cargar los detalles de la compra.');
        }
    });

    $.ajax({
        url: URL + '/DetalleCompras/compra/' + id,
        type: 'GET',
        success: function (response) {
            var html = '';
            $.each(response, function (i, item) {
                var subtotal = (item.cantidad * item.precio).toFixed(2);
                html += `<tr>
                            <td>${item.producto ? item.producto.nombre : ''}</td>
                            <td>${item.cantidad}</td>
                            <td>S/. ${item.precio.toFixed(2)}</td>
                            <td>S/. ${subtotal}</td>
                        </tr>`;
            });
            $("#dataDetalleCompraItems").html(html);
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar detalle de items de compra:', error);
            alert('Ocurrió un error al cargar los detalles de los items de la compra.');
        }
    });
}

function editarCompra(id, codigo_remision, fecha, estado, id_proveedor) {
    loadProductos();
    detail.length = [];
    $("#dataCartProducts").html('');

    $('#id').val(id);
    $('#codigo_remision').val(codigo_remision);

    var fecha = new Date(fecha);
    var anio = fecha.getFullYear();
    var mes = ('0' + (fecha.getMonth() + 1)).slice(-2);
    var dia = ('0' + fecha.getDate()).slice(-2);
    var hora = ('0' + fecha.getHours()).slice(-2);
    var minuto = ('0' + fecha.getMinutes()).slice(-2);
    var fechaConvertida = anio + '-' + mes + '-' + dia + 'T' + hora + ':' + minuto;

    $('#fecha').val(fechaConvertida);

    if (estado == 1) {
        $('#estado').prop("checked", true);
    } else {
        $('#estado').prop("checked", false);
    }

    if (id_proveedor) {
        $('#id_proveedor').val(id_proveedor);
    }

    $('#createModal').modal('show');
}

function eliminarCompra(id) {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminarlo"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'DELETE',
                url: URL + `/compras/${id}`,
                success: function (response) {
                    loadCompras();
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "¡Registro eliminado!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                error: function (xhr, status, error) {
                    console.error('Error al eliminar compra:', error);
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "Error al eliminar registro",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    });
}

$('#add-compra').click(function (e) {
    $('#id').val('');
    $('#codigo_remision').val('');
    $('#fecha').val('');
    $('#id_proveedor').val('');
    $('#estado').prop('checked', false);
    $('#createModal').modal('show');
    loadProductos();
    detail = [];
    $("#dataCartProducts").html('');
});

$('#createForm').submit(function (e) {
    e.preventDefault();
    
    var formData = {
        codigoRemision: $('#codigo_remision').val(),
        fecha: $('#fecha').val(),
        proveedor: { id: parseInt($('#id_proveedor').val()) },
        estado: $('#estado').prop('checked') ? 1 : 0
    };

    var method = 'POST';
    var url = URL + '/compras';
    var compraId = $('#id').val();

    if (compraId) {
        method = 'PUT';
        url += `/${compraId}`;
        formData.id = parseInt(compraId);
    }

    $.ajax({
        type: method,
        url: url,
        contentType: 'application/json',
        data: JSON.stringify(formData),
        success: function (response) {
            if (method === 'POST') {
                saveDetail(response.id);
            } else {
                updateDetail(response.id);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al guardar compra:', error);
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Error al guardar registro",
                showConfirmButton: false,
                timer: 1500
            });
        }
    });
});

function saveDetail(compraId) {
    detail.forEach(function (item) {
        var detailData = {
            cantidad: item.cantidad,
            precio: item.precio,
            producto: { id: item.id_producto },
            compra: { id: compraId }
        };

        $.ajax({
            type: 'POST',
            url: URL + '/DetallesCompra',
            contentType: 'application/json',
            data: JSON.stringify(detailData),
            success: function (response) {
                console.log('Detalle guardado correctamente:', response);
            },
            error: function (xhr, status, error) {
                console.error('Error al guardar detalle:', error);
            }
        });
    });

    loadCompras();
    $('#createModal').modal('hide');
    Swal.fire({
        position: "center",
        icon: "success",
        title: "¡Compra registrada correctamente!",
        showConfirmButton: false,
        timer: 1500
    });
}

function updateDetail(compraId) {
    $.ajax({
        type: 'DELETE',
        url: URL + `/DetalleCompras/compra/${compraId}`,
        success: function (response) {
            saveDetail(compraId);
        },
        error: function (xhr, status, error) {
            console.error('Error al actualizar detalle:', error);
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Error al actualizar detalle",
                showConfirmButton: false,
                timer: 1500
            });
        }
    });
}


