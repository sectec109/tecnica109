<?php
session_start();

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: login-admin.php");
    exit();
}

$nombreAdmin = $_SESSION['usuario'] ?? 'Administrador';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panel de Administración</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fdf8fc;
      font-family: 'Poppins', sans-serif;
      color: #333;
    }

    .admin-header {
      background-color: #800020;
      padding: 1.5rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .admin-header h1 {
      font-size: 1.7rem;
      color: #fff;
    }

    .btn-logout {
      background-color: rgb(192, 192, 192);
      color: #800000;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 0.4rem;
      font-size: 0.9rem;
    }

    .btn-logout:hover {
      background-color: #a00030;
      color: white;
    }

    .welcome {
      text-align: center;
      margin-top: 2rem;
      margin-bottom: 2rem;
    }

    .welcome h3 {
      font-weight: 600;
      color: #800020;
    }

    .dashboard {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 2rem;
      max-width: 1000px;
      margin: auto;
      padding: 2rem;
    }

    .dashboard-card {
      background-color: #fff;
      border-radius: 1rem;
      padding: 2rem 1.5rem;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.06);
      transition: transform 0.3s ease;
      border: 2px solid #ffe0ec;
    }

    .dashboard-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    }

    .dashboard-card h5 {
      font-weight: 600;
      color: #800020;
      font-size: 1.3rem;
    }

    .dashboard-card p {
      font-size: 0.9rem;
      color: #555;
    }

    .dashboard-card a {
      text-decoration: none;
      display: block;
      margin-top: 1rem;
      font-weight: 500;
      color: #d63384;
    }

    footer {
      text-align: center;
      padding: 1rem;
      font-size: 0.85rem;
      color: #999;
      margin-top: 2rem;
    }
  </style>
</head>
<body>

  <header class="admin-header">
    <h1>Panel Administrativo</h1>
    <form method="post" style="margin: 0;">
      <button type="submit" name="logout" class="btn-logout">Cerrar sesión</button>
    </form>
  </header>

  <section class="welcome">
    <h3>Hola, <?php echo htmlspecialchars($nombreAdmin); ?> 👋</h3>
    <p>Selecciona una sección para comenzar a gestionar el sitio.</p>
  </section>

  <section class="dashboard">
    <div class="dashboard-card">
    <h5>🎇 Galeria </h5>
    <p>Publica y edita los eventos.</p>
    <a href="galeria-completa.php">Gestionar</a>
  </div>
  <div class="dashboard-card">
    <h5>📖 Nosotros</h5>
    <p>Edita la misión, visión, historia y organigrama.</p>
    <a href="informacion-MV.php">Gestionar</a>
  </div>
  <div class="dashboard-card">
    <h5>🛠️ Talleres</h5>
    <p>Modifica la información de los talleres disponibles.</p>
    <a href="todos-talleres.php">Gestionar</a>
  </div>
  <div class="dashboard-card">
    <h5>📢 Comunicados</h5>
    <p>Publica y edita los avisos institucionales.</p>
    <a href="todos-comunicados.php">Gestionar</a>
  </div>
  <div class="dashboard-card">
    <h5>📜 Reglamento</h5>
    <p>Sube o edita el reglamento escolar vigente.</p>
    <a href="reglamento-escolar.php">Gestionar</a>
  </div>
  <div class="dashboard-card">
    <h5>📝 Inscripción</h5>
    <p>Modifica los requisitos o fechas de inscripción.</p>
    <a href="informacion-inscripcion.php">Gestionar</a>
  </div>
  <div class="dashboard-card">
    <h5>👥 Directorio</h5>
    <p>Actualiza el personal docente y administrativo.</p>
    <a href="informacion-directivo.php">Gestionar</a>
  </div>
  
  
</section>
  <footer>
    &copy; <?php echo date('Y'); ?> Escuela Secundaria Técnica Núm. 109 | Plataforma Administrativa
  </footer>

<?php
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login-admin.php");
    exit();
}
?>
</body>
</html>
