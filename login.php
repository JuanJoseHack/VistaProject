<?php
// Paso 1: Obtener los datos del usuario desde la API
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://ti.app.informaticapp.com:4179/api-ti/usuarios',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

curl_close($curl);
$data = json_decode($response, true); // Decodificar el JSON como array asociativo

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_found = false;

    // Verifica si $data está definido y no está vacío
    if (isset($data) && !empty($data)) {
        // Recorrer todos los usuarios en el array $data
        foreach ($data as $user) {
            // Verifica si existen las claves 'usuario' y 'password' en el array $user
            if (isset($user['usuario']) && isset($user['password'])) {
                // Comparar el usuario y contraseña ingresados con los datos del usuario
                if ($user['usuario'] === $username && $user['password'] === $password) {
                    $user_found = true;
                    break; // Detiene el bucle si se encuentra un usuario válido
                }
            }
        }
    }

    if ($user_found) {
        // Inicio de sesión exitoso, redirige a index.php
        header('Location: index.php');
        exit; // Detiene la ejecución del script
    } else {
        // Si no se encuentra usuario o la contraseña no coincide
        $error_message = "Usuario o contraseña equivocada";

        // Generar código JavaScript para mostrar el mensaje de error en una ventana emergente
        echo '<script language="javascript">';
        echo 'alert("' . $error_message . '")';
        echo '</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5 col-xl-12">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form action="login.php" method="post">
                            <div class="form-group">
                                <label for="username">Usuario:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js" integrity="sha384-8VNOr9iAR4Jl8YL6Svgr5hZ63ulDzV2Lx84V2AvZK3SO6uQC5qdP3UlSzNwOg/z" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>

</html>