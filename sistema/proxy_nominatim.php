<?php
if (!isset($_GET['lat']) || !isset($_GET['lon'])) {
  http_response_code(400);
  echo json_encode(["error" => "Faltan parámetros"]);
  exit;
}

$lat = $_GET['lat'];
$lon = $_GET['lon'];

$url = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=$lat&lon=$lon";

// inicializa cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, "reciclaje-app/1.0"); // obligatorio para Nominatim
$response = curl_exec($ch);
curl_close($ch);

// devuelve al navegador
header('Content-Type: application/json');
echo $response;
