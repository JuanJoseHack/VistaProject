<?php
// Función para realizar una solicitud cURL
function realizarSolicitud($url) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));
    $response = curl_exec($curl);
    if ($response === false) {
        echo "Error en la solicitud al servidor: " . curl_error($curl);
        curl_close($curl);
        exit;
    }
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if ($http_code >= 400) {
        echo "Error en la solicitud al servidor. Código de error: " . $http_code;
        curl_close($curl);
        exit;
    }
    curl_close($curl);
    return json_decode($response);
}

// Obtener categorías y unidades de medida
$categorias = realizarSolicitud('http://ropa.app.informaticapp.com:4224/api_ropa/categoria');
$unidadesMedida = realizarSolicitud('http://ropa.app.informaticapp.com:4224/api_ropa/unidadesMedida');

// Procesar formulario si se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $precio_mayor = $_POST['precio_mayor'];
    $precio_menor = $_POST['precio_menor'];
    $precio_promocion = $_POST['precio_promocion'];
    $stock = $_POST['stock'];
    $id_categoria = $_POST['id_categoria'];
    $id_unidadmedida = $_POST['id_unidadmedida'];

    // Validar los datos del formulario
    if (empty($nombre) || empty($precio_mayor) || empty($precio_menor) || empty($precio_promocion) || empty($stock) || empty($id_categoria) || empty($id_unidadmedida)) {
        echo "Todos los campos son obligatorios.";
        exit;
    }

    // Construir datos para enviar a la API
    $data = array(
        'nombre' => $nombre,
        'precio_mayor' => $precio_mayor,
        'precio_menor' => $precio_menor,
        'precio_promocion' => $precio_promocion,
        'stock' => $stock,
        'estado' => 1,
        'categoria' => array(
            'id' => (int)$id_categoria
        ),
        'unidadesMedida' => array(
            'id' => (int)$id_unidadmedida
        )
    );

    // Enviar datos a la API para registrar el producto
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://ropa.app.informaticapp.com:4224/api_ropa/productos',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));
    $response = curl_exec($curl);
    if ($response === false) {
        echo "Error en la solicitud al servidor de registro: " . curl_error($curl);
        curl_close($curl);
        exit;
    }
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if ($http_code >= 400) {
        echo "Error en la solicitud al servidor de registro. Código de error: " . $http_code;
        echo " Respuesta del servidor: " . htmlspecialchars($response);
        curl_close($curl);
        exit;
    }
    curl_close($curl);

    // Redireccionar o mostrar mensaje de éxito
    echo "Producto registrado con éxito.";
    header("Location: indexProd.php");
    exit;
}
?>