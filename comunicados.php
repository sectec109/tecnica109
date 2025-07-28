<?php
$conexion = new mysqli("localhost", "root", "", "escuela109");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$comunicados = $conexion->query("SELECT * FROM comunicados ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Comunicados | Técnica 109</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fff0f5;
    }

    .top-header {
      background-color: #fce7ed;
      color: #800020;
      padding: 1rem 0;
    }

    .logo-text-container {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 15px;
      flex-wrap: wrap;
    }

    .logo-text-container img {
      height: 80px;
    }

    .top-header h1 {
      font-size: 2rem;
      font-weight: 600;
      margin: 0;
    }

    .top-header .lead {
      margin-top: 5px;
      font-size: 1.1rem;
    }

    nav.navbar {
      background-color: #800020;
    }

    nav .nav-link {
      color: white !important;
      font-weight: bold;
    }

    nav .nav-link:hover {
      color: #ffccdd !important;
    }

    .contenedor-comunicados {
      padding: 2rem 1rem;
    }

    .card {
      margin-bottom: 1.5rem;
      border-radius: 10px;
      box-shadow: 0 0 8px rgba(0,0,0,0.08);
      max-width: 800px;
      margin-left: auto;
      margin-right: auto;
      font-size: 0.95rem;
     padding: 1rem;
    }

    .card-title {
      font-size: 1.25rem;
      color: #800020;
      font-weight: 600;
      margin-bottom: 0.6rem;
    }

    .card-text {
      font-size: 1rem;
      margin-bottom: 0.8rem;
    }

    .comunicado-img {
      width: 100%;
      height: auto;
      max-height: 500px;
      object-fit: cover;
      border-radius: 8px;
      margin-top: 1rem;
    }

    .fecha-publicacion {
      font-size: 0.85rem;
      color: #6c757d;
      margin-top: 0.5rem;
    }

    .footer {
      background-color: #800020;
      color: white;
      padding: 2rem 1rem 1rem;
    }

    .footer p, .footer svg {
      font-size: 0.9rem;
    }

    .footer svg {
      margin-right: 0.5rem;
      fill: white;
    }

    .footer-copy {
      font-size: 0.8rem;
      text-align: center;
      margin-top: 1rem;
      color: #f0cbd4;
    }
  </style>
</head>
<body>

<!-- ENCABEZADO -->
<div class="top-header text-center">
  <div class="logo-text-container">
    <img src="fotos/109.png" alt="Logotipo de la Escuela Secundaria Técnica Núm. 109">
    <div>
      <h1>Escuela Secundaria Técnica Núm. 109</h1>
      <p class="lead">"Aprender haciendo: la esencia de la educación técnica."</p>
    </div>
  </div>
</div>

<!-- MENÚ -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand text-white fw-bold" href="index.html">Inicio</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon bg-light rounded"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="nosotrosDropdown" role="button" data-bs-toggle="dropdown">Nosotros</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="nosotros.php?seccion=misionvision">Misión / Visión</a></li>
              <li><a class="dropdown-item" href="nosotros.php?seccion=historia">Historia</a></li>
              <li><a class="dropdown-item" href="nosotros.php?seccion=organigrama">Organigrama</a></li>
            </ul>
          </li>
          <li class="nav-item"><a class="nav-link" href="talleres.php">Talleres-Disiplinas</a></li>
          <li class="nav-item"><a class="nav-link" href="comunicados.php">Comunicados</a></li>
          <li class="nav-item"><a class="nav-link" href="reglamento.php">Reglamento</a></li>
          <li class="nav-item"><a class="nav-link" href="inscripcion.php">Inscripción</a></li>
          <li class="nav-item"><a class="nav-link" href="directorio.php">Directorio</a></li>
        </ul>
      </div>
    </div>
  </nav>

<!-- COMUNICADOS -->
<div class="container contenedor-comunicados">
  <h2 class="text-center text-uppercase mb-4" style="color: #800020;">Comunicados</h2>
  <?php while ($comunicado = $comunicados->fetch_assoc()): ?>
    <div class="card">
      <div class="card-body">
        <h5 class="card-title"><?= htmlspecialchars($comunicado['titulo']) ?></h5>
        <p class="card-text"><?= nl2br(htmlspecialchars($comunicado['descripcion'])) ?></p>
        <?php if (!empty($comunicado['imagen'])): ?>
          <img src="<?= htmlspecialchars($comunicado['imagen']) ?>" class="comunicado-img mt-3" alt="Imagen del comunicado">
        <?php endif; ?>
        <p class="fecha-publicacion">Publicado el: <?= date('d/m/Y', strtotime($comunicado['fecha_creacion'])) ?></p>
      </div>
    </div>
  <?php endwhile; ?>
</div>

<!-- FOOTER -->
<footer class="footer">
  <div class="container">
    <div class="d-flex justify-content-end">
      <div class="text-start ms-auto">
        <p>
          <svg aria-hidden="true" viewBox="0 0 24 24" width="16" height="16">
            <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
          </svg>
          sec.tec.num109@gmail.com
        </p>
        <p>
          <svg aria-hidden="true" viewBox="0 0 24 24" width="16" height="16">
            <path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.11-.21c1.2.48 2.5.74 3.85.74a1 1 0 011 1v3.5a1 1 0 01-1 1C9.39 21.5 2.5 14.61 2.5 6a1 1 0 011-1h3.5a1 1 0 011 1c0 1.35.26 2.65.74 3.85a1 1 0 01-.21 1.11l-2.2 2.2z"/>
          </svg>
          971 131 4152
        </p>
        <p>
          <svg aria-hidden="true" viewBox="0 0 24 24" width="14" height="14">
            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM12 11.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
          </svg>
          Carretera Chicapa de Castro - Unión Hidalgo <br>
          <svg aria-hidden="true" viewBox="0 0 24 24" width="14" height="14">
          </svg>
          Col.  Cheguigo, Chicapa de Castro <br>
          <svg aria-hidden="true" viewBox="0 0 24 24" width="14" height="14">
          </svg>
          H.  CD. de Juchitan, Oax.
        </p>
      </div>
    </div>
    <div class="footer-copy text-center mt-3">
      &copy; 2025 Escuela Secundaria Técnica Núm. 109 | Todos los derechos reservados
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
