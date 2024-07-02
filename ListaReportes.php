<?php

session_start(); // Iniciar la sesión

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_data'])) {
    header('Location: login.php');
    exit;
}

$user_data = $_SESSION['user_data'];
$user_accesos = $_SESSION['user_accesos'];

// Función para verificar el acceso a un módulo
function tieneAcceso($modulo_id, $accesos)
{
    foreach ($accesos as $acceso) {
        if ($acceso['modulo']['id'] == $modulo_id && $acceso['estado'] == 1) {
            return true;
        }
    }
    return false;
}


// Función para obtener los datos de la API de ventas
function obtenerDatosVentas()
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://ti.app.informaticapp.com:4188/api-ti/ventas',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    return json_decode($response); // Decodificar la respuesta JSON y devolverla como objeto
}

// Obtener los datos de ventas desde la API
$data = obtenerDatosVentas();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Ventas</title>
    <link href="Css/Style.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.0/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>
    <style>
        .modal-content {
            padding: 20px;
        }

        .filter-container {
            display: flex;
            align-items: center;
        }

        .filter-container>div {
            margin-right: 30px;
        }

        #filtrarSucursal,
        #filtrarFecha {
            width: auto;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">E-Market Pro</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
        <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Buscar..." aria-label="Search" aria-describedby="basic-addon2" />
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
        <ul class="navbar-nav ml-auto ml-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">Configuración</a>
                    <a class="dropdown-item" href="#">Perfiles</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="login.php">Cerrar Sesión</a>
                </div>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Modulos</div>

                        <?php if (tieneAcceso(1, $user_accesos)) : ?>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#seguridad" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Seguridad
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="seguridad" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="usuarios.php">Usuarios</a>
                                    <a class="nav-link" href="perfiles.php">Perfiles</a>
                                    <a class="nav-link" href="accesos.php">Accesos</a>
                                    <a class="nav-link" href="modulos.php">Modulos</a>
                                </nav>
                            </div>
                        <?php endif; ?>

                        <?php if (tieneAcceso(2, $user_accesos)) : ?>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts" aria-expanded="false" aria-controls="collapsePages">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                Ventas
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="layout-static.html">Lista Ventas</a>
                                    <a class="nav-link" href="layout-static.html">Registrar Venta</a>
                                    <a class="nav-link" href="layout-static.html">Detalle Venta</a>
                                    <a class="nav-link" href="layout-sidenav-light.html">Clientes</a>
                                </nav>
                            </div>
                        <?php endif; ?>

                        <?php if (tieneAcceso(3, $user_accesos)) : ?>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#devoluciones" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Devoluciones
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="devoluciones" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="layout-static.html">Lista Devoluciones </a>
                                    <a class="nav-link" href="layout-sidenav-light.html">Garantias</a>
                                </nav>
                            </div>
                        <?php endif; ?>

                        <?php if (tieneAcceso(4, $user_accesos)) : ?>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#reportes" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Reportes
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="reportes" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="ListaReportes.php">Lista reportes</a>
                                    <a class="nav-link" href="GraficoReportes.php">Reportes Ventas </a>
                                </nav>
                            </div>
                        <?php endif; ?>

                        <?php if (tieneAcceso(5, $user_accesos)) : ?>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Compras
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                    <a class="nav-link" href="pages/compras.php">
                                        Lista Compras
                                    </a>
                                    <a class="nav-link" href="pages/proveedores.php">
                                        Proveedores
                                    </a>
                                </nav>
                            </div>
                        <?php endif; ?>

                        <?php if (tieneAcceso(6, $user_accesos)) : ?>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#almacen" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Almacén
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="almacen" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="layout-static.html">Productos</a>
                                    <a class="nav-link" href="layout-sidenav-light.html">Sucursales </a>
                                    <a class="nav-link" href="layout-static.html">Categorias</a>
                                    <a class="nav-link" href="layout-static.html">Lugar</a>
                                </nav>
                            </div>
                        <?php endif; ?>

                        <div class="sb-sidenav-menu-heading">Complementos</div>
                        <a class="nav-link" href="charts.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Gráficos
                        </a>
                        <a class="nav-link" href="tables.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Tablas
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">BIENVENIDO</div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">

            <div class="container">
                <h2 class="my-4">Tabla de Ventas</h2>

                <div class="filter-container">
                    <div class="form-group">
                        <label for="filtrarSucursal">Filtrar por Sucursal:</label>
                        <select id="filtrarSucursal" class="form-control">
                            <option value="">Todas las Sucursales</option>
                            <?php
                            $sucursales = [];
                            foreach ($data as $venta) {
                                $sucursales[$venta->sucursal->id] = $venta->sucursal->nombre;
                            }
                            foreach ($sucursales as $id => $nombre) {
                                echo "<option value='$nombre'>$nombre</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="filtrarFecha">Filtrar por Fecha:</label>
                        <input type="date" id="filtrarFecha" class="form-control">
                    </div>
                </div>

                <button id="btnExportExcel" class="btn btn-success mb-3"><i class="fas fa-file-excel"></i> Descargar Excel</button>
                <a href="GraficoReportes.php" class="btn btn-primary mb-3"><i></i> Regresar</a>

                <table id="tablaVentas" class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Email</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Sucursal</th>
                            <th>Representante Sucursal</th>
                            <th>País</th>
                            <th>Región</th>
                            <th>Provincia</th>
                            <th>Distrito</th>
                            <th>Dirección Sucursal</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Tipo de Pago</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Iterar sobre cada venta
                        foreach ($data as $venta) {
                            echo '<tr>';
                            echo '<td>' . $venta->id . '</td>';
                            echo '<td>' . date('d/m/Y', strtotime($venta->fecha)) . '</td>';
                            echo '<td><a href="#" data-toggle="modal" data-target="#clienteModal' . $venta->cliente->id . '">' . $venta->cliente->nombre . ' ' . $venta->cliente->apellido . '</a></td>';
                            echo '<td>' . $venta->cliente->email . '</td>';
                            echo '<td>' . $venta->cliente->direccion . '</td>';
                            echo '<td>' . $venta->cliente->telefono . '</td>';
                            echo '<td>' . $venta->sucursal->nombre . '</td>';
                            echo '<td>' . $venta->sucursal->representante . '</td>';
                            echo '<td>' . $venta->sucursal->lugar->pais . '</td>';
                            echo '<td>' . $venta->sucursal->lugar->region . '</td>';
                            echo '<td>' . $venta->sucursal->lugar->provincia . '</td>';
                            echo '<td>' . $venta->sucursal->lugar->distrito . '</td>';
                            echo '<td>' . $venta->sucursal->lugar->direccionEspecifica . '</td>';
                            // Mostrar detalles del producto
                            foreach ($venta->detalles as $detalle) {
                                echo '<td>' . $detalle->producto->nombre . '</td>';
                                echo '<td>' . $detalle->cantidad . '</td>'; // Mostrar la cantidad comprada
                                echo '<td>' . $detalle->subtotal . '</td>'; // Mostrar el precio subtotal del producto
                            }
                            echo '<td>' . $venta->tipoPago . '</td>';
                            echo '<td>' . $venta->total . '</td>';
                            echo '</tr>';

                            // Modal para detalles del cliente y productos comprados
                            echo '<div class="modal fade" id="clienteModal' . $venta->cliente->id . '" tabindex="-1" role="dialog" aria-labelledby="clienteModalLabel' . $venta->cliente->id . '" aria-hidden="true">';
                            echo '<div class="modal-dialog modal-lg" role="document">';
                            echo '<div class="modal-content">';
                            echo '<div class="modal-header">';
                            echo '<h5 class="modal-title" id="clienteModalLabel' . $venta->cliente->id . '">' . $venta->cliente->nombre . ' ' . $venta->cliente->apellido . '</h5>';
                            echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                            echo '<span aria-hidden="true">&times;</span>';
                            echo '</button>';
                            echo '</div>';
                            echo '<div class="modal-body">';
                            echo '<p><strong>Email:</strong> ' . $venta->cliente->email . '</p>';
                            echo '<p><strong>Dirección:</strong> ' . $venta->cliente->direccion . '</p>';
                            echo '<p><strong>Teléfono:</strong> ' . $venta->cliente->telefono . '</p>';
                            echo '<p><strong>Total Gastado:</strong> $' . $venta->total . '</p>'; // Mostrar el total gastado por el cliente
                            echo '<p><strong>Productos Comprados:</strong></p>';
                            echo '<ul>';
                            foreach ($venta->detalles as $detalle) {
                                echo '<li>' . $detalle->producto->nombre . ' - Cantidad: ' . $detalle->cantidad . '</li>';
                            }
                            echo '</ul>';
                            echo '</div>';
                            echo '<div class="modal-footer">';
                            echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>';
                            // Botón para descargar en PDF
                            echo '<button type="button" class="btn btn-danger" onclick="exportarPDF(' . $venta->cliente->id . ')"><i class="fas fa-file-pdf"></i> Descargar PDF</button>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        ?>
                    </tbody>
                </table>

            </div>

            <script>
                $(document).ready(function() {
                    // Inicializar DataTables
                    var table = $('#tablaVentas').DataTable({
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
                        }
                    });

                    // Filtrar por sucursal
                    $('#filtrarSucursal').on('change', function() {
                        var sucursal = $(this).val();
                        table.columns(6).search(sucursal).draw();
                    });

                    // Filtrar por fecha
                    $('#filtrarFecha').on('change', function() {
                        table.draw();
                    });

                    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                        var fechaFiltro = $('#filtrarFecha').val();
                        var fechaVenta = data[1].split('/').reverse().join('-'); // Convertir fecha a formato YYYY-MM-DD

                        if (fechaFiltro === "" || fechaVenta === fechaFiltro) {
                            return true;
                        }
                        return false;
                    });

                    // Exportar a Excel
                    $('#btnExportExcel').on('click', function() {
                        var wb = XLSX.utils.table_to_book(document.getElementById('tablaVentas'), {
                            sheet: "Ventas"
                        });
                        return XLSX.writeFile(wb, 'ventas.xlsx');
                    });
                });

                // Función para exportar a PDF desde el modal del cliente
                function exportarPDF(clienteId) {
                    var pdfContent = document.getElementById('clienteModal' + clienteId).innerHTML;
                    var docDefinition = {
                        content: [{
                                text: 'Detalles del Cliente y Productos Comprados',
                                style: 'header'
                            },
                            {
                                text: pdfContent
                            }
                        ],
                        styles: {
                            header: {
                                fontSize: 18,
                                bold: true,
                                margin: [0, 0, 0, 10]
                            }
                        }
                    };
                    pdfMake.createPdf(docDefinition).download('detalle_cliente.pdf');
                }
            </script>

            <!-- Footer -->
            <footer class="py-4 bg-light mt-3 mb-3">
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
</body>

</html>