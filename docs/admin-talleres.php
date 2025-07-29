<?php
session_start();
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: login-admin.php");
    exit();
}

include 'conexion.php';

$mensaje = '';
$tipoMensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'];
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);

    $imagen = '';
    if (!empty($_FILES['imagen']['name'])) {
        $carpeta = 'fotos/talleres/';
        if (!file_exists($carpeta)) mkdir($carpeta, 0777, true);
        $archivo = basename($_FILES['imagen']['name']);
        $ruta = $carpeta . time() . "_" . preg_replace('/\s+/', '_', $archivo);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);
        $imagen = $ruta;
    }

    $sql = "INSERT INTO talleres (tipo, titulo, descripcion, imagen, fecha_creacion)
            VALUES ('$tipo', '$titulo', '$descripcion', '$imagen', NOW())";

    if ($conn->query($sql)) {
        header("Location: admin-talleres.php?publicado=1");
        exit();
    } else {
        $mensaje = "❌ Ocurrió un error al guardar.";
        $tipoMensaje = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Admin Talleres</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fdf8fc;
      font-family: 'Poppins', sans-serif;
    }

    .header {
      background-color: #800020;
      padding: 1.5rem;
      color: white;
      font-size: 1.5rem;
      font-weight: 600;
    }

    .volver-btn {
      background-color: white;
      color: #800020;
      border: 2px solid #800020;
      font-weight: 500;
    }

    .volver-btn:hover {
      background-color: #800020;
      color: white;
    }

    .form-container {
      max-width: 900px;
      margin: 2rem auto;
      padding: 2rem;
      background: white;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }

    .publicar-btn {
      background-color: #800020;
      color: white;
      font-weight: 500;
      border: none;
      padding: 0.5rem 1.2rem;
      border-radius: 6px;
    }

    .publicar-btn:hover {
      background-color: #a01a3a;
      color: white;
    }

    .alert {
      text-align: center;
    }

    label {
      font-weight: 600;
    }

    .form-section {
      display: none;
    }

    .form-section.active {
      display: block;
    }
  </style>
</head>
<body>

  <div class="header d-flex justify-content-between align-items-center">
    <span class="ms-3">Administrar Talleres / Disciplinas</span>
    <a href="todos-talleres.php" class="btn volver-btn me-3">Volver</a>
  </div>

  <div class="form-container">
    <?php if (isset($_GET['publicado'])): ?>
      <div id="mensaje" class="alert alert-success">✅ Información publicada correctamente.</div>
    <?php elseif ($mensaje): ?>
      <div id="mensaje" class="alert alert-<?= $tipoMensaje ?>">
        <?= $mensaje ?>
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="tipo" class="form-label">Selecciona tipo:</label>
        <select name="tipo" id="tipo" class="form-select" required>
          <option value="">-- Selecciona --</option>
          <option value="taller">Taller</option>
          <option value="disciplina">Disciplina</option>
        </select>
      </div>

      <div id="formCampos" class="form-section">
        <div class="mb-3">
          <label for="titulo" class="form-label">Título</label>
          <input type="text" name="titulo" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="descripcion" class="form-label">Descripción</label>
          <textarea name="descripcion" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
          <label for="imagen" class="form-label">Imagen</label>
          <input type="file" name="imagen" class="form-control" accept="image/*" required>
        </div>
      </div>

      <div class="d-flex justify-content-end">
        <button type="submit" id="publicarBtn" class="btn publicar-btn" disabled>💾 Publicar</button>
      </div>
    </form>
  </div>

  <script>
    const tipoSelect = document.getElementById('tipo');
    const formCampos = document.getElementById('formCampos');
    const publicarBtn = document.getElementById('publicarBtn');

    tipoSelect.addEventListener('change', function () {
      if (this.value === 'taller' || this.value === 'disciplina') {
        formCampos.classList.add('active');
        publicarBtn.disabled = false;
      } else {
        formCampos.classList.remove('active');
        publicarBtn.disabled = true;
      }
    });

    window.onload = function () {
      const alerta = document.getElementById('mensaje');
      if (alerta) {
        setTimeout(() => {
          alerta.style.display = 'none';
        }, 3000);
      }
    };
  </script>

</body>
</html>
