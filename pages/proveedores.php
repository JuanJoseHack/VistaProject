<?php
// Obtener los datos de la API
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://ti.app.informaticapp.com:4170/api-ti/proveedores',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

if(curl_errno($curl)) {
    echo 'Error:' . curl_error($curl);
}

curl_close($curl);

// Decodificar la respuesta JSON a un array
$data = json_decode($response, true);

// Verificar que los datos se han decodificado correctamente
if ($data === null) {
    echo 'Error al decodificar la respuesta JSON: ' . json_last_error_msg();
} else {
    // Aquí puede ir cualquier lógica adicional de manejo de datos
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Proveedores</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h1 class="mb-4">Lista de Proveedores</h1>
    <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addModal">Agregar Proveedor</button>
    <table class="table table-bordered table-striped">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Dirección</th>
          <th>Email</th>
          <th>Teléfono</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody id="proveedores-lista">
        <tr>
          <td colspan="7" class="text-center">Cargando datos...</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Modal para agregar proveedor -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Agregar Proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addForm" method="POST" action="guardar.php">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" required>
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <input type="text" class="form-control" id="estado" name="estado" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

  <!-- Modal para editar proveedor -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Editar Proveedor</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="editForm">
            <input type="hidden" id="edit-id" name="id">
            <div class="form-group">
              <label for="edit-nombre">Nombre</label>
              <input type="text" class="form-control" id="edit-nombre" name="nombre" required>
            </div>
            <div class="form-group">
              <label for="edit-direccion">Dirección</label>
              <input type="text" class="form-control" id="edit-direccion" name="direccion" required>
            </div>
            <div class="form-group">
              <label for="edit-email">Email</label>
              <input type="email" class="form-control" id="edit-email" name="email" required>
            </div>
            <div class="form-group">
              <label for="edit-telefono">Teléfono</label>
              <input type="text" class="form-control" id="edit-telefono" name="telefono" required>
            </div>
            <div class="form-group">
              <label for="edit-estado">Estado</label>
              <input type="text" class="form-control" id="edit-estado" name="estado" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="../pages/js/proveedores.js"></script>
</body>
</html>