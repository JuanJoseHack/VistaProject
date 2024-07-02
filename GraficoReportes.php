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
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Gráficos de Ventas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="Css/Style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>

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
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Lista Compras
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="pages/compras.php">Registar compra</a>
                                            <a class="nav-link" href="#">Compras</a>
                                            <a class="nav-link" href="#">Detalles Compras</a>
                                        </nav>
                                    </div>
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                        Proveedores
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>

                                    <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="401.html">Registrar Proveedor</a>
                                            <a class="nav-link" href="404.html">Proveedores</a>
                                        </nav>
                                    </div>
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
                <h1 class="mt-4 mb-4">Gráficos de Ventas</h1>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="form-row">
                                <div class="form-group col-md-5">
                                    <label for="sucursal">Selecciona una sucursal:</label>
                                    <select class="form-control" name="sucursal" id="sucursal">
                                        <option value="">Todas las sucursales</option>
                                        <?php
                                        // Obtener todas las sucursales disponibles desde la API
                                        $url = 'http://ti.app.informaticapp.com:4188/api-ti/ventas';
                                        $data = json_decode(file_get_contents($url), true);

                                        $sucursales = [];
                                        foreach ($data as $venta) {
                                            $sucursalNombre = $venta['sucursal']['nombre'];
                                            $sucursales[$sucursalNombre] = true; // Usamos un array asociativo para evitar duplicados
                                        }

                                        // Mostrar opciones en el select y mantener seleccionada la opción enviada
                                        foreach ($sucursales as $sucursal => $_) {
                                            $selected = ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['sucursal'] == $sucursal) ? 'selected' : '';
                                            echo "<option value='$sucursal' $selected>$sucursal</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="fecha">Selecciona una fecha:</label>
                                    <input type="date" class="form-control" name="fecha" id="fecha" value="<?php echo $_SERVER["REQUEST_METHOD"] == "POST" ? $_POST['fecha'] : ''; ?>">
                                </div>
                                <div class="form-group col-md-2 align-self-end">
                                    <button type="submit" class="btn btn-primary btn-block">Filtrar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Gráficos</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <canvas id="ventasPorSucursalChart" width="400" height="200"></canvas>
                                        <div id="totalProductosPorSucursal" class="text-center mt-2"></div>
                                        <div id="gananciaGlobal" class="text-center mt-2"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <canvas id="productosMasVendidosChart" width="400" height="200"></canvas>
                                        <div id="productoMasVendido" class="text-center mt-2"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <canvas id="clientesQueCompranChart" width="400" height="200"></canvas>
                                        <div id="clientesQueCompran" class="text-center mt-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="contaisner">
                    <div class="row">
                        <div class="col-md-10 d-flex top-2px mt-3 mb-3">
                            <a href="ListaReportes.php" class="btn btn-primary">Ver la lista de ventas</a>
                        </div>
                    </div>
                </div>

            </div>

            <?php
            // Procesar el formulario y filtrar los datos si se envió
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $selectedSucursal = $_POST['sucursal'];
                $selectedFecha = $_POST['fecha'];

                // Filtrar datos por la sucursal y fecha seleccionada
                $filteredData = array_filter($data, function ($venta) use ($selectedSucursal, $selectedFecha) {
                    $sucursalMatch = empty($selectedSucursal) || $venta['sucursal']['nombre'] == $selectedSucursal;
                    $fechaMatch = empty($selectedFecha) || date('Y-m-d', strtotime($venta['fecha'])) == $selectedFecha;
                    return $sucursalMatch && $fechaMatch;
                });
            } else {
                // Si no se ha enviado el formulario, mostrar todos los datos
                $filteredData = $data;
            }

            // Procesar los datos filtrados o no para obtener la información necesaria
            $sucursalesProductosTotal = [];
            $gananciaGlobal = 0;

            foreach ($filteredData as $venta) {
                // Obtener la sucursal
                $sucursalNombre = $venta['sucursal']['nombre'];
                if (!isset($sucursalesProductosTotal[$sucursalNombre])) {
                    $sucursalesProductosTotal[$sucursalNombre]['total_productos'] = 0;
                    $sucursalesProductosTotal[$sucursalNombre]['ganancia'] = 0;
                }
                // Obtener el producto vendido y precio de ganancia total
                $cantidadProductos = $venta['detalles'][0]['cantidad'];
                $gananciaVenta = $venta['total'];

                // Sumar cantidad de productos y ganancia por sucursal
                $sucursalesProductosTotal[$sucursalNombre]['total_productos'] += $cantidadProductos;
                $sucursalesProductosTotal[$sucursalNombre]['ganancia'] += $gananciaVenta;

                // Sumar a la ganancia global
                $gananciaGlobal += $gananciaVenta;
            }

            // Encontrar el producto más vendido y cantidad vendida globalmente
            $productoMasVendido = '';
            $cantidadProductoMasVendido = 0;

            foreach ($filteredData as $venta) {
                foreach ($venta['detalles'] as $detalle) {
                    $cantidad = $detalle['cantidad'];
                    if ($cantidad > $cantidadProductoMasVendido) {
                        $cantidadProductoMasVendido = $cantidad;
                        $productoMasVendido = $detalle['producto']['nombre'];
                    }
                }
            }

            // Encontrar los clientes que más compran
            $clientesCompras = [];

            foreach ($filteredData as $venta) {
                $clienteNombre = $venta['cliente']['nombre'] . ' ' . $venta['cliente']['apellido'];
                if (!isset($clientesCompras[$clienteNombre])) {
                    $clientesCompras[$clienteNombre] = 0;
                }
                $clientesCompras[$clienteNombre] += 1; // Contar cada compra como una unidad
            }

            // Preparar los datos para los gráficos
            $labelsVentasPorSucursal = [];
            $dataVentasPorSucursal = [];
            foreach ($sucursalesProductosTotal as $sucursal => $datos) {
                $labelsVentasPorSucursal[] = $sucursal;
                $dataVentasPorSucursal[] = $datos['total_productos'];
            }

            $labelsClientesCompras = array_keys($clientesCompras);
            $dataClientesCompras = array_values($clientesCompras);

            ?>

            <script>
                // Gráfico de ventas por sucursal (Barras)
                var ctx1 = document.getElementById('ventasPorSucursalChart').getContext('2d');
                var myChart1 = new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode($labelsVentasPorSucursal); ?>,
                        datasets: [{
                            label: 'Total de productos vendidos por sucursal',
                            data: <?php echo json_encode($dataVentasPorSucursal); ?>,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value + ' unidades';
                                    }
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y.toLocaleString('es-ES') + ' unidades';
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                });

                // Mostrar total de productos por sucursal
                document.getElementById('totalProductosPorSucursal').innerHTML = '<strong>Total de productos:</strong> <?php echo array_sum($dataVentasPorSucursal); ?> unidades';

                // Mostrar ganancia global
                document.getElementById('gananciaGlobal').innerHTML = '<strong>Ganancia total:</strong> $<?php echo number_format($gananciaGlobal, 2); ?>';

                // Gráfico de productos más vendidos (Tarta)
                var ctx2 = document.getElementById('productosMasVendidosChart').getContext('2d');
                var myChart2 = new Chart(ctx2, {
                    type: 'pie',
                    data: {
                        labels: <?php echo json_encode([$productoMasVendido]); ?>,
                        datasets: [{
                            label: 'Producto más vendido',
                            data: <?php echo json_encode([$cantidadProductoMasVendido]); ?>,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y.toLocaleString('es-ES') + ' unidades';
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                });

                // Mostrar producto más vendido
                document.getElementById('productoMasVendido').innerHTML = '<strong>Producto más vendido:</strong> <?php echo $productoMasVendido; ?>';

                // Gráfico de clientes que más compran (Tarta)
                var ctx3 = document.getElementById('clientesQueCompranChart').getContext('2d');
                var myChart3 = new Chart(ctx3, {
                    type: 'pie',
                    data: {
                        labels: <?php echo json_encode($labelsClientesCompras); ?>,
                        datasets: [{
                            label: 'Clientes que más compran',
                            data: <?php echo json_encode($dataClientesCompras); ?>,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y.toLocaleString('es-ES') + ' compras';
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                });

                // Mostrar clientes que más compran
                document.getElementById('clientesQueCompran').innerHTML = '<strong>Clientes que más compran:</strong> <?php echo implode(', ', array_slice(array_keys($clientesCompras), 0, 3)); ?>, etc.';
            </script>


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


            <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
            <script src="js/scripts.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
            <script src="assets/demo/chart-area-demo.js"></script>
            <script src="assets/demo/chart-bar-demo.js"></script>
            <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
            <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
            <script src="assets/demo/datatables-demo.js"></script>
        </div>
</body>

</html>