<?php
session_start();
include("../bd/conexion.php");

if (!isset($_SESSION['usuario_id'])) exit("No autenticado");

$usuario_id = $_SESSION['usuario_id'];
$tipo = $_POST['tipo'];
$lat = $_POST['lat'];
$lon = $_POST['lon'];
$calle = $_POST['calle'];

$sql = "INSERT INTO salvar_marcadores (usuario_id, tipo_marcador, lat, lon, calle)
        VALUES ('$usuario_id','$tipo','$lat','$lon','$calle')";
if (mysqli_query($conexion, $sql)) {
  echo "Marcador guardado.";
} else {
  echo "Error: " . mysqli_error($conexion);
}
?>
