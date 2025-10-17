<?php
  session_start();
  $usuario_nombre = $_SESSION['usuario_nombre'];
  $usuario_tipo = $_SESSION['tipo'];

  if(!isset($usuario_nombre)){
    header('location: ../login.php');
    exit();
  }

  if(isset($_GET['valor'])){
    session_unset();
    session_destroy();
    header('location: ../login.php');
    exit();
  }

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mapa de recolección</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<style>
  body { 
    margin:0; 
    display:flex; 
    height:100vh; 
  }
  #sidebar { 
    width:100px; 
    background:#eee; 
    padding:10px; 
    display:flex; 
    flex-direction:column; 
    align-items:center; 
    gap: 5px; 
    border: 2px solid blue;
    border-radius: 10px;
  }
  #sidebar img {
    width: 40px;
    height: 40px;
  }
  #map { 
    flex:1; 
  }
</style>
</head>
<body>

<div id="sidebar">
  <h3>Menú</h3>
  <img src="../icons/organica.png" draggable="true" data-tipo="organica">
  <img src="../icons/papel.png" draggable="true" data-tipo="papel">
  <img src="../icons/metal.png" draggable="true" data-tipo="metal">
  <label class="text-info bg-dark rounded"><?php echo $usuario_nombre; ?></label>
  <label class="text-info bg-dark rounded"><?php echo $usuario_tipo; ?></label>
  <?php if($usuario_tipo == 'superusuario') {?>
      <a href="listar_marcadores.php" class="btn btn-info" target="_blank">Listar</a>
  <?php } ?>
  <a href="mapa.php?valor=<?php echo $usuario_nombre; ?>"
     onclick="return confirm('¿Desea salir de la app?');"
     class="btn btn-primary">Salir</a>
</div>
<div id="contenedor-mapa">

  <!-- 📢 ALERTA FIJA -->
  <div id="alerta-usuario" class="alert alert-info alert-dismissible fade show shadow"
       style="position: absolute; top: 10px; left: 50%; transform: translateX(-50%);
              z-index: 1000; max-width: 90%; text-align: center;">
    <strong>ℹ️ Atención:</strong> Arrastra un marcador y suéltalo en el mapa para indicar un punto de recolección.
    Una vez confirmado, <b>no podrás moverlo ni eliminarlo</b>.
  </div>
  </div>
<div id="map"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>
let map, draggedType = null;

if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(pos => {
    const lat = pos.coords.latitude, lon = pos.coords.longitude;
    iniciarMapa(lat, lon);
  }, () => iniciarMapa(-34.6, -58.4));
} else iniciarMapa(-34.6, -58.4);

function iniciarMapa(lat, lon) {
  map = L.map('map').setView([lat, lon], 15);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

  cargarMarcadores();
  activarDragAndDrop();

  map.on('popupopen', function(e) {
  const popup = e.popup;
  const boton = popup._contentNode.querySelector('.btn-eliminar');
  
  if (boton) {
    boton.addEventListener('click', function() {
      if (confirm("¿Deseas eliminar este marcador?")) {
        map.removeLayer(e.popup._source); // elimina del mapa

        // 🔥 opcional: eliminar también de la base de datos
        const lat = boton.dataset.lat;
        const lon = boton.dataset.lon;

        fetch('eliminar_marcador.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          body: `lat=${lat}&lon=${lon}`
        }).then(r => r.text()).then(resp => {
          console.log(resp);
        });
      }
    });
  }
});

}

function activarDragAndDrop() {
  document.querySelectorAll('#sidebar img').forEach(img => {
    img.addEventListener('dragstart', e => draggedType = e.target.dataset.tipo);
  });

  document.getElementById('map').addEventListener('dragover', e => e.preventDefault());
  document.getElementById('map').addEventListener('drop', async e => {
    e.preventDefault();
    const rect = map.getContainer().getBoundingClientRect();
    const x = e.clientX - rect.left, y = e.clientY - rect.top;
    const latlng = map.containerPointToLatLng([x, y]);

    const calle = await obtenerCalle(latlng.lat, latlng.lng);
    const fecha = new Date().toLocaleString();

    if (confirm(`¿Guardar marcador ${draggedType}?`)) {
      fetch('guardar_marcador.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: `tipo=${draggedType}&lat=${latlng.lat}&lon=${latlng.lng}&calle=${encodeURIComponent(calle)}`
      }).then(r=>r.text()).then(console.log);

      crearMarker(draggedType, latlng.lat, latlng.lng, calle, fecha, "Tú");
    }
  });
}

function crearMarker(tipo, lat, lon, calle, fecha, usuario) {
  const icon = L.icon({ iconUrl: `../icons/${tipo}.png`, iconSize: [40,40] });
  const marker = L.marker([lat, lon], { icon }).addTo(map);
  marker.bindPopup(`<b>Tipo: ${tipo}</b><br>
                    <b>Usuario: ${usuario}</b><br>
                    <b>Calle: ${calle}</b><br>
                    <b>Fecha: ${fecha}</b>
                    <button class="btn-eliminar" data-lat="${lat}" data-lon="${lon}">🗑️ Eliminar</button>`);
}


async function obtenerCalle(lat, lon) {
  try {
    const res = await fetch(`proxy_nominatim.php?lat=${lat}&lon=${lon}`);
    const data = await res.json();
    return data.address.road || "Calle desconocida";
  } catch {
    return "Calle desconocida";
  }
}

function cargarMarcadores() {
  fetch('obtener_marcadores.php')
    .then(r=>r.json())
    .then(datos=>{
      datos.forEach(m=>{
        crearMarker(m.tipo_marcador, m.lat, m.lon, m.calle, m.fecha_hora, m.usuario);
      });
    });
}
</script>
</body>
</html>
