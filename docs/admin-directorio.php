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
    $nombre = $conn->real_escape_string($_POST['nombre'] ?? '');
    $cargo = $conn->real_escape_string($_POST['cargo'] ?? '');
    $telefono = $conn->real_escape_string($_POST['telefono'] ?? '');
    $correo = $conn->real_escape_string($_POST['correo'] ?? '');
    $descripcion = $conn->real_escape_string($_POST['descripcion'] ?? '');

    $sql = "INSERT INTO directorio (nombre, cargo, telefono, correo, descripcion, fecha_creacion)
            VALUES ('$nombre', '$cargo', '$telefono', '$correo', '$descripcion', NOW())";

    if ($conn->query($sql)) {
        $mensaje = "✅ Información registrada correctamente.";
        $tipoMensaje = "success";
    } else {
        $mensaje = "❌ Error al guardar: " . $conn->error;
        $tipoMensaje = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Admin Directorio</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fdf8fc;
      font-family: 'Poppins', sans-serif;
    }

    .header {
      background-color: #800020;
      color: white;
      padding: 1.5rem;
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

    label {
      font-weight: 600;
    }

    textarea {
      min-height: 120px;
      resize: vertical;
    }
  </style>
</head>
<body>

<div class="header d-flex justify-content-between align-items-center">
  <span class="ms-3">Agregar Personal al Directorio</span>
  <a href="informacion-directivo.php" class="btn volver-btn me-3">Volver</a>
</div>

<div class="form-container">
  <?php if ($mensaje): ?>
    <div class="alert alert-<?= $tipoMensaje ?>">
      <?= $mensaje ?>
    </div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label for="nombre" class="form-label">Nombre</label>
      <input type="text" name="nombre" id="nombre" class="form-control">
    </div>

    <div class="mb-3">
      <label for="cargo" class="form-label">Cargo</label>
      <input type="text" name="cargo" id="cargo" class="form-control">
    </div>

    <div class="mb-3">
      <label for="telefono" class="form-label">Teléfono</label>
      <input type="text" name="telefono" id="telefono" class="form-control">
    </div>

    <div class="mb-3">
      <label for="correo" class="form-label">Correo</label>
      <input type="email" name="correo" id="correo" class="form-control">
    </div>

    <div class="mb-3">
      <label for="descripcion" class="form-label">Descripción</label>
      <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
    </div>

    <div class="text-end">
      <button type="submit" class="btn publicar-btn">📤 Publicar</button>
    </div>
  </form>
</div>

<script>
  window.onload = function () {
    const alerta = document.querySelector('.alert');
    if (alerta) {
      setTimeout(() => alerta.style.display = 'none', 3000);
    }
  };
</script>

</body>
</html>
