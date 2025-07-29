<?php
include 'conexion.php';

$sql = "SELECT * FROM galeria ORDER BY fecha_publicacion DESC";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Galería de Fotos | Técnica 109</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #fff0f5;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .encabezado {
      background-color: #800020;
      color: white;
      padding: 2.5rem 1rem 3.5rem;
      text-align: center;
      position: relative;
    }

    .encabezado h1 {
      font-size: 2.2rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .encabezado p {
      font-size: 1.1rem;
      margin: 0;
      font-weight: 400;
    }

    .boton-inicio {
      position: absolute;
      bottom: 1rem;
      left: 1rem;
      background-color: white;
      color: #800020;
      font-weight: 600;
      border: none;
      padding: 0.45rem 1.1rem;
      border-radius: 6px;
      text-decoration: none;
      box-shadow: 0 0 6px rgba(0,0,0,0.2);
    }

    .boton-inicio:hover {
      background-color: #f0cbd4;
      color: #800020;
    }

    .galeria-contenido {
      flex: 1;
      padding: 2rem 1rem;
    }

    .evento-card {
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      overflow: hidden;
    }

    .evento-card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
    }

    .evento-card .card-body {
      padding: 1rem;
    }

    .evento-card .descripcion {
      font-size: 0.95rem;
      color: #333;
    }

    .footer {
      background-color: #800020;
      color: #f0cbd4;
      padding: 1rem;
      text-align: center;
      font-size: 0.9rem;
      margin-top: auto;
    }
  </style>
</head>
<body>

  <!-- Encabezado -->
  <div class="encabezado">
    <h1>📸 Galería de Fotos</h1>
    <p>Una mirada a nuestros momentos más especiales</p>
    <a href="index.html" class="boton-inicio">Inicio</a>
  </div>

  <!-- Contenido de galería -->
  <div class="container galeria-contenido">
    <div class="row g-4">
      <?php if ($resultado->num_rows > 0): ?>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
          <div class="col-md-6 col-lg-4">
            <div class="evento-card">
              <img src="<?php echo $fila['imagen']; ?>" alt="Evento publicado">
              <div class="card-body">
                <p class="descripcion"><?php echo htmlspecialchars($fila['descripcion']); ?></p>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="col-12 text-center">
          <p class="text-muted">No hay eventos publicados por el momento.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Pie de página -->
  <footer class="footer">
    &copy; 2025 Escuela Secundaria Técnica Núm. 109 | Todos los derechos reservados
  </footer>

</body>
</html>
