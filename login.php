<?php
session_start();
include("./bd/conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $usuario = $_POST['usuario'];
  $clave = $_POST['clave'];

  $consulta = "SELECT * FROM usuarios WHERE usuario='$usuario' and clave='$clave'";
  $resultado = mysqli_query($conexion, $consulta);
  $cantidad = mysqli_num_rows($resultado);

  if($cantidad > 0){
    while($fila = mysqli_fetch_assoc($resultado)) {
        $_SESSION['usuario_id'] = $fila['id'];
        $_SESSION['usuario_nombre'] = $fila['usuario'];
        $_SESSION['tipo'] = $fila['tipo'];
        header("Location: ./sistema/mapa.php");
        exit();
    }
  }else{
    $error = 'El usuario o contraseña son incorrectos!';
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body style="background-image: url('./imagenes/fondo_reciclar.jpg');
             background-repeat: no-repeat;
             background-position: center;
             background-attachment: fixed;
             background-size: cover;">
    <div class="container-md col-md-6 border border-primary border-5 rounded-3 shadow-lg p-3 mb-5 bg-body-tertiary mt-5 bg-info-subtle">
    <form method="POST" class="bg-primary-subtle">
        <div class="mb-3">
            <h2>Ingreso</h2>
        </div>
        <div class="mb-3">
            <label for="usuario" class="form-label">Usuario</label>
            <input type="text" name="usuario" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="clave" class="form-label">Clave</label>
            <input type="password" name="clave" class="form-control" required>
        </div>
        <div class="mb-3 d-grid gap-3">
            <button type="submit" class="btn btn-success">Login</button>
            <a href="registro.php" class="btn btn-info">Sin cuenta? | Registrar!</a>
        </div>
    </form>
    <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
