<!DOCTYPE html>
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
                <h1 class="text-center mb-5 mt-4">Lista de Compras</h1>
                <a href="#" class="btn btn-primary" id="add-compra">Registrar compra</a>
                <table class="table ">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Código Remisión</th>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="resultdata">
                    </tbody>
                </table>
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
    <!-- Modal para editar compra -->
    <div class="modal fade" id="detalleModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Compra:</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="codigo_remision">Código Remisión: </label>
                            <strong id="st-codigoRemision"></strong>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="fecha">Fecha</label>
                            <strong id="st-fecha"></strong>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="id_proveedor">Proveedor: </label><strong id="st-proveedor"></strong>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="devolucion">Estado:</label>
                            <strong id="st-estado"></strong>
                        </div>
                        <div class="form-group col-md-12">
                            <h5>Detalle de la compra</h5>
                            <table class="table" style="max-height: 400px; overflow-y: auto;">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Accion</th>
                                    </tr>
                                </thead>
                                <tbody id="dataDetalleCompraItems"></tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para crear nueva compra -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Crear Nueva Compra</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createForm">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="codigo_remision">Código Remisión</label>
                                <input type="hidden" id="id">
                                <input type="text" class="form-control" id="codigo_remision" name="codigo_remision"
                                    required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fecha">Fecha</label>
                                <input type="datetime-local" class="form-control" id="fecha" name="fecha" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="id_proveedor">Proveedor</label>
                                <select class="form-control" id="id_proveedor" name="id_proveedor" required>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="devolucion">Estado:</label>
                                <input type="checkbox" id="estado" name="estado">
                            </div>
                            <div class="form-group col-md-6">
                                <h5>Lista de productos</h5>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dataProducts">
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group col-md-6">
                                <h5>Lista de pedido</h5>
                                <table class="table" style="max-height: 400px; overflow-y: auto;">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dataCartProducts"></tbody>

                                </table>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
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
    <script src="js/compras.js"></script>
</body>

</html>