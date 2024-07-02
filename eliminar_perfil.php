<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'ti.app.informaticapp.com:4189/api-ti/perfiles/'.$_GET['id'],
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'DELETE',
));

$response = curl_exec($curl);

curl_close($curl);
header("Location: perfiles.php")

?>
