<?php
$conexion = new mysqli("localhost", "root", "", "escuela109");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$seccion = $_GET['seccion'] ?? '';
$titulo = '';
$contenido = '';

if ($seccion === 'misionvision') {
    $stmt1 = $conexion->prepare("SELECT contenido FROM nosotros WHERE seccion = 'mision'");
    $stmt2 = $conexion->prepare("SELECT contenido FROM nosotros WHERE seccion = 'vision'");
    $stmt1->execute(); $stmt1->bind_result($mision); $stmt1->fetch(); $stmt1->close();
    $stmt2->execute(); $stmt2->bind_result($vision); $stmt2->fetch(); $stmt2->close();
    $titulo = 'Misión y Visión';
    $contenido = "<h4 class='fw-semibold text-start text-decoration-underline'>Misión</h4><p>$mision</p>";
    $contenido .= "<h4 class='fw-semibold text-start text-decoration-underline mt-3'>Visión</h4><p>$vision</p>";
} elseif (in_array($seccion, ['historia', 'organigrama'])) {
    $stmt = $conexion->prepare("SELECT titulo, contenido FROM nosotros WHERE seccion = ?");
    $stmt->bind_param("s", $seccion);
    $stmt->execute();
    $stmt->bind_result($titulo, $contenido);
    $stmt->fetch();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $titulo ? htmlspecialchars($titulo) . ' - ' : '' ?>Nosotros | Técnica 109</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fff0f5;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .top-header {
      background-color: #fce7ed;
      color: #800020;
      padding: 1rem;
      text-align: center;
    }

    .top-header h1 {
      font-size: 2rem;
      font-weight: 600;
      margin-bottom: 0.3rem;
    }

    .top-header .lead {
      font-size: 1rem;
      font-weight: 400;
    }

    nav.navbar {
      background-color: #800020;
    }

    nav .nav-link {
      color: white !important;
      font-weight: bold;
    }

    nav .nav-link:hover {
      color: #ffcbd4 !important;
      text-decoration: underline;
    }

    .contenido-nosotros {
      flex: 1;
      padding: 2rem 1rem;
      background-color: #fff;
    }

    .contenido-nosotros h2 {
      color: #800020;
      font-weight: 600;
      margin-bottom: 1.5rem;
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

    @media (max-width: 768px) {
      .top-header h1 {
        font-size: 1.5rem;
      }
      .top-header .lead {
        font-size: 0.95rem;
      }
    }
  </style>
</head>
<body>

  <!-- ENCABEZADO -->
  <div class="top-header">
  <div class="container d-flex align-items-center justify-content-center gap-3 flex-wrap text-center text-md-start">
    <img src="fotos/109.png" alt="Logo Técnica 109" style="height: 80px;">
    <div>
      <h1 class="fw-bold m-0" style="color: #800020;">Escuela Secundaria Técnica Núm. 109</h1>
      <p class="m-0" style="font-size: 0.95rem; color: #800020;">"Aprender haciendo: la esencia de la educación técnica."</p>
    </div>
  </div>
</div>


  <!-- BARRA DE NAVEGACIÓN -->
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

  <!-- CONTENIDO -->
<section class="contenido-nosotros">
  <div class="container">
    <?php if ($seccion && $contenido): ?>
      <h2><?= htmlspecialchars($titulo) ?></h2>

      <?php if ($seccion === 'organigrama'): ?>
        <?php
          // Extraer la etiqueta <img> del contenido
          preg_match('/<img[^>]+>/', $contenido, $imagenMatch);
          $imagenHTML = $imagenMatch[0] ?? '';
          $descripcion = trim(str_replace($imagenHTML, '', $contenido));
        ?>
        <!-- Primero imagen -->
        <div class="mb-3 text-center">
          <?= $imagenHTML ?>
        </div>
        <!-- Después la descripción -->
        <div class="text-justify">
          <?= nl2br(htmlspecialchars_decode($descripcion)) ?>
        </div>

      <?php else: ?>
        <div><?= $contenido ?></div>
      <?php endif; ?>
      
    <?php else: ?>
      <h2>Bienvenido</h2>
      <p>Selecciona una sección del menú <strong>Nosotros</strong> para conocer más sobre nuestra institución.</p>
    <?php endif; ?>
  </div>
</section>


  <!-- PIE DE PÁGINA -->
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
