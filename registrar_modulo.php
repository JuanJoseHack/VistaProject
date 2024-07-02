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


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'ti.app.informaticapp.com:4188/api-ti/modulos',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode(array(
            "nombre" => $_POST['nombre'],
            "estado" => 1
        )),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    header("Location: modulos.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard</title>
    <link href="Css/Style.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
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
                <h2 class="mb-4 mt-4">Formulario de Modulo</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="usuario">Modulo:</label>
                        <input type="text" name="nombre" placeholder="Nombre del modulo" class="form-control mb-2">
                    </div>
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="modulos.php" class="btn btn-danger">Cancelar</a>
                </form>

            </div>
        </div>
    </div>

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

    <!-- JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>

</html>