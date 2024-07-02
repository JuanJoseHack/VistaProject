<?php
// Obtener los datos de la API
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://ti.app.informaticapp.com:4188/api-ti/proveedores',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
)
);

$response = curl_exec($curl);

if (curl_errno($curl)) {
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
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Consumo de Web Services</title>
  <!-- CSS only -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
    integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <!-- Meta Data -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <!-- Page Title -->
  <title>Dashboard</title>
  <!-- Custom CSS -->
  <link href="../Css/Style.css" rel="stylesheet" />
  <!-- DataTables CSS -->
  <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet"
    crossorigin="anonymous" />
  <!-- Font Awesome JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"
    crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
  <!-- Navbar -->
  <?php include('../componentes/navbar.php')?>
  <div id="layoutSidenav">
    <!-- layout -->
  <?php include('../componentes/layout.php')?>
    <div id="layoutSidenav_content">
      <div class="container col-xl-12">
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
      </div>
      <!-- Footer -->
      <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid">
          <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Copyright &copy; E-Marke Pro 2024</div>
            <div>
              <a href="#">Privacy Policy</a>
              &middot;
              <a href="#">Terms &amp; Conditions</a>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <!-- JS, Popper.js, and jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
    integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
    crossorigin="anonymous"></script>
  <script src="js/proveedores.js"></script>
</body>

</html>