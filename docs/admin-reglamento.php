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
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $archivo = '';

    if (!empty($_FILES['archivo']['name'])) {
        $carpeta = 'documentos/reglamento/';
        if (!file_exists($carpeta)) mkdir($carpeta, 0777, true);
        $nombreArchivo = basename($_FILES['archivo']['name']);
        $ruta = $carpeta . time() . '_' . $nombreArchivo;
        move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta);
        $archivo = $ruta;
    }

    $sql = "INSERT INTO reglamento (descripcion, archivo, fecha_publicacion) VALUES ('$descripcion', '$archivo', NOW())";
    if ($conn->query($sql)) {
        $mensaje = "✅ Reglamento publicado correctamente.";
        $tipoMensaje = "success";
    } else {
        $mensaje = "❌ Error al guardar el reglamento.";
        $tipoMensaje = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Admin Reglamento</title>
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
      max-width: 1000px;
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
    }

    label {
      font-weight: 600;
    }

    .alert {
      text-align: center;
    }
  </style>
</head>
<body>

<div class="header d-flex justify-content-between align-items-center">
  <span class="ms-3">📄 Publicar Reglamento</span>
  <a href="reglamento-escolar.php" class="btn volver-btn me-3">Volver</a>
</div>

<div class="form-container">
  <?php if ($mensaje): ?>
    <div id="mensaje" class="alert alert-<?= $tipoMensaje ?>">
      <?= $mensaje ?>
    </div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <div class="row">
      <div class="col-md-7 mb-3">
        <label for="archivo" class="form-label">Archivo (PDF o Imagen)</label>
        <input type="file" name="archivo" class="form-control" accept=".pdf,image/*" required>
      </div>

      <div class="col-md-5 mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="8" required></textarea>
      </div>
    </div>

    <div class="d-flex justify-content-end">
      <button type="submit" class="btn publicar-btn">📤 Publicar</button>
    </div>
  </form>
</div>

<script>
  window.onload = function () {
    const alerta = document.getElementById('mensaje');
    if (alerta) {
      setTimeout(() => alerta.style.display = 'none', 3000);
    }
  };
</script>

</body>
</html>
