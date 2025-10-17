<?php
    include '../bd/conexion.php';

    $sql = mysqli_query($conexion, "SELECT *, s.id as id_marcadores FROM salvar_marcadores s INNER JOIN usuarios u ON
                                    s.usuario_id=u.id");

    $resultado = mysqli_num_rows($sql);

    if($resultado > 0){ ?>
        <div class="alert alert-danger" role="alert">
            Listado de marcadores!
        </div>
        <div class="table-responsive bg-info border border-4 border-primary">
            <table id="tabla" class="table table-striped table-hover table-primary">
                <thead>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Tipo usuario</th>
                    <th>Tipo marcador</th>
                    <th>Calle</th>
                    <th>Fecha - hora</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </thead>
                <tbody>
                    <?php while($datos = mysqli_fetch_array($sql)){ ?>
                        <tr>
                            <td><?php echo $datos['id_marcadores'];?></td>
                            <td><?php echo $datos['usuario'];?></td>
                            <td><?php echo $datos['tipo'];?></td>
                            <td><?php echo $datos['tipo_marcador'];?></td>
                            <td><?php echo $datos['calle'];?></td>
                            <td><?php echo $datos['fecha_hora'];?></td>                            
                            <td>
                                <?php if($datos['estado'] == '1'){ ?>
                                        Pendiente
                                <?php } else { ?>
                                        Borrado
                                <?php } ?>
                            </td>                            
                            <td>
                                <a href="reporte_marcadores.php?id=<?php echo $datos['id_marcadores']; ?>" class="btn btn-success"
                                   target="_blank">Imprimir</a>
                            </td>
                        </tr>
    <?php } }else{ ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Advertencia!</strong> No hay secciones para mostrar.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php }

?>

</tbody>
</table>
</div>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de marcadores</title>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="//cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <script src="//cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <body style="background-image: url('../imagenes/fondo_reciclar.jpg');
             background-repeat: no-repeat;
             background-position: center;
             background-attachment: fixed;
             background-size: cover;
             height: 100vh;
             min-height: 100vh;
             width: 100vw;">
    <script>
        let table = new DataTable('#tabla');
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>