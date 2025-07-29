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
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);

    $imagen = '';
    if (!empty($_FILES['imagen']['name'])) {
        $carpeta = 'fotos/comunicados/';
        if (!file_exists($carpeta)) mkdir($carpeta, 0777, true);
        $archivo = basename($_FILES['imagen']['name']);
        $ruta = $carpeta . time() . '_' . $archivo;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);
        $imagen = $ruta;
    }

    $sql = "INSERT INTO comunicados (titulo, descripcion, imagen, fecha_creacion)
            VALUES ('$titulo', '$descripcion', '$imagen', NOW())";

    if ($conn->query($sql)) {
        $mensaje = "✅ Comunicado publicado correctamente.";
        $tipoMensaje = "success";
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
  <title>Admin Comunicados</title>
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
    }
    .publicar-btn:hover {
      background-color: #a01a3a;
    }
    .alert {
      text-align: center;
    }
    label {
      font-weight: 600;
    }
  </style>
</head>
<body>

  <div class="header d-flex justify-content-between align-items-center">
    <span class="ms-3">Publicar Comunicado</span>
    <a href="todos-comunicados.php" class="btn volver-btn me-3">Volver</a>
  </div>

  <div class="form-container">
    <?php if ($mensaje): ?>
      <div id="mensaje" class="alert alert-<?= $tipoMensaje ?>">
        <?= $mensaje ?>
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="titulo" class="form-label">Título</label>
        <input type="text" name="titulo" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="8" style="min-height: 200px;" required></textarea>
      </div>

      <div class="mb-3">
        <label for="imagen" class="form-label">Imagen (opcional)</label>
        <input type="file" name="imagen" class="form-control" accept="image/*">
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
        setTimeout(() => {
          alerta.style.display = 'none';
        }, 3000);
      }
    };
  </script>

</body>
</html>
