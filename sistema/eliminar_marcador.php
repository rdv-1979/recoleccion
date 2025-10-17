<?php
session_start();
include("../bd/conexion.php");

if (!isset($_SESSION['usuario_id'])) exit("No autenticado");

$usuario_id = $_SESSION['usuario_id'];
$lat = $_POST['lat'];
$lon = $_POST['lon'];

$sql = "UPDATE salvar_marcadores SET estado=0
        WHERE usuario_id='$usuario_id' AND lat='$lat' AND lon='$lon'";

if (mysqli_query($conexion, $sql)) {
  echo "Marcador eliminado correctamente.";
} else {
  echo "Error al eliminar marcador: " . mysqli_error($conexion);
}
?>
