<?php
include("./bd/conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $usuario = $_POST['usuario'];
  $clave = $_POST['clave'];
  $tipo = $_POST['tipo'];

  $sql = "INSERT INTO usuarios (usuario, clave, tipo) VALUES ('$usuario','$clave','$tipo')";
  if (mysqli_query($conexion, $sql)) { ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Correcto!</strong> El usuario se registró correctamente!.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php } else { ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> El usuario no se pudo registrar!.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php }
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Registro</title>
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
            <h2>Registrar Usuario</h2>
        </div>
        <div class="mb-3">
            <label for="usuario" class="form-label">Usuario</label>
            <input type="text" name="usuario" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="clave" class="form-label">Clave</label>
            <input type="password" name="clave" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo de usuario:</label>
            <select name="tipo" class="form-control">
                <option value="comun" class="form-control">Común</option>
            </select>
        </div>
        <div class="mb-3 d-grid gap-3">
            <button type="submit" class="btn btn-primary form-control">Registrar</button>
            <a href="login.php" class="form-control btn btn-success">Con cuenta? | Login!</a>
        </div>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
