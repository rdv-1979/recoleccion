<?php
session_start();
include("../bd/conexion.php");

if (!isset($_SESSION['usuario_id'])) exit("No autenticado");

$usuario_id = $_SESSION['usuario_id'];
$tipo = $_SESSION['tipo'];

if ($tipo == 'superusuario') {
  $sql = "SELECT s.*, u.usuario FROM salvar_marcadores s JOIN usuarios u ON s.usuario_id=u.id";
} else {
  $sql = "SELECT s.*, u.usuario FROM salvar_marcadores s JOIN usuarios u ON s.usuario_id=u.id WHERE s.usuario_id='$usuario_id'";
}

$res = mysqli_query($conexion, $sql);
$datos = [];
while ($row = mysqli_fetch_assoc($res)) $datos[] = $row;
echo json_encode($datos);
?>
