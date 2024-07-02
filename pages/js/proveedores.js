var URL = 'http://ti.app.informaticapp.com:4170/api-ti/proveedores';
    $(document).ready(function() {
      // Función para cargar datos
      function loadData() {
        $.ajax({
          url: URL,
          type: 'GET',
          success: function(data) {
            var tbody = $('#proveedores-lista');
            tbody.empty();
            if (data.length === 0) {
              tbody.append('<tr><td colspan="7" class="text-center">No hay datos disponibles</td></tr>');
            } else {
              data.forEach(function(item) {
                tbody.append(
                  '<tr>' +
                    '<td>' + item.id + '</td>' +
                    '<td>' + item.nombre + '</td>' +
                    '<td>' + item.direccion + '</td>' +
                    '<td>' + item.email + '</td>' +
                    '<td>' + item.telefono + '</td>' +
                    '<td>' + item.estado + '</td>' +
                    '<td>' +
                      '<button class="btn btn-primary btn-sm edit-btn" data-toggle="modal" data-target="#editModal" data-id="' + item.id + '" data-nombre="' + item.nombre + '" data-direccion="' + item.direccion + '" data-email="' + item.email + '" data-telefono="' + item.telefono + '" data-estado="' + item.estado + '">Editar</button>' +
                      '<button class="btn btn-danger btn-sm delete-btn" data-id="' + item.id + '">Eliminar</button>' +
                    '</td>' +
                  '</tr>'
                );
              });
            }
          },
          error: function(xhr, status, error) {
            console.error('Error al cargar los datos:', error);
            var tbody = $('#proveedores-lista');
            tbody.empty();
            tbody.append('<tr><td colspan="7" class="text-center">Error al cargar los datos</td></tr>');
          }
        });
      }

      // Cargar datos iniciales
      loadData();

      // Crear proveedor
      $('#addForm').submit(function(event) {
        event.preventDefault();

        var proveedor = {
          nombre: $('#nombre').val(),
          direccion: $('#direccion').val(),
          email: $('#email').val(),
          telefono: $('#telefono').val(),
          estado: $('#estado').val()
        };

        $.ajax({
          url: URL,
          type: 'POST',
          contentType: 'application/json',
          data: JSON.stringify(proveedor),
          success: function(response) {
            alert('Proveedor agregado exitosamente');
            $('#addModal').modal('hide');
            loadData(); // Recargar la tabla con los nuevos datos
          },
          error: function(xhr, status, error) {
            console.error('Error:', error);
            alert('Ocurrió un error al agregar el proveedor');
          }
        });
      });

      // Editar proveedor
      $('#editForm').submit(function(event) {
        event.preventDefault();

        var id = $('#edit-id').val();
        var proveedor = {
          nombre: $('#edit-nombre').val(),
          direccion: $('#edit-direccion').val(),
          email: $('#edit-email').val(),
          telefono: $('#edit-telefono').val(),
          estado: $('#edit-estado').val()
        };

        $.ajax({
          url: URL +'/'+ id,
          type: 'PUT',
          contentType: 'application/json',
          data: JSON.stringify(proveedor),
          success: function(response) {
            alert('Proveedor editado exitosamente');
            $('#editModal').modal('hide');
            loadData(); // Recargar la tabla con los nuevos datos
          },
          error: function(xhr, status, error) {
            console.error('Error:', error);
            alert('Ocurrió un error al editar el proveedor');
          }
        });
      });

      // Eliminar proveedor
      $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');

        if (confirm('¿Está seguro de que desea eliminar este registro?')) {
          $.ajax({
            url: URL +'/'+ id,
            type: 'DELETE',
            success: function(response) {
              alert('Proveedor eliminado exitosamente');
              loadData(); // Recargar la tabla con los nuevos datos
            },
            error: function(xhr, status, error) {
              console.error('Error:', error);
              alert('Ocurrió un error al eliminar el proveedor');
            }
          });
        }
      });

      // Rellenar el formulario de edición con los datos del proveedor
      $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nombre = button.data('nombre');
        var direccion = button.data('direccion');
        var email = button.data('email');
        var telefono = button.data('telefono');
        var estado = button.data('estado');

        var modal = $(this);
        modal.find('.modal-body #edit-id').val(id);
        modal.find('.modal-body #edit-nombre').val(nombre);
        modal.find('.modal-body #edit-direccion').val(direccion);
        modal.find('.modal-body #edit-email').val(email);
        modal.find('.modal-body #edit-telefono').val(telefono);
        modal.find('.modal-body #edit-estado').val(estado);
      });
    });