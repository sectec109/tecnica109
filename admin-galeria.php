<?php
session_start();

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: login-admin.php");
    exit();
}

include 'conexion.php'; // Asegúrate de tener este archivo correctamente configurado

$mensaje = '';
$tipoMensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['imagen']['name']) && !empty($_POST['descripcion'])) {
        $carpetaDestino = 'fotos/galeria/';
        if (!file_exists($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        $nombreArchivo = basename($_FILES['imagen']['name']);
        $rutaCompleta = $carpetaDestino . $nombreArchivo;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
            $descripcion = $conn->real_escape_string($_POST['descripcion']);

            $sql = "INSERT INTO galeria (imagen, descripcion) VALUES ('$rutaCompleta', '$descripcion')";
            if ($conn->query($sql)) {
                $mensaje = '📸 Imagen publicada exitosamente.';
                $tipoMensaje = 'success';
            } else {
                $mensaje = '❌ Error al guardar en la base de datos.';
                $tipoMensaje = 'danger';
            }
        } else {
            $mensaje = '❌ Error al subir la imagen.';
            $tipoMensaje = 'danger';
        }
    } else {
        $mensaje = '⚠️ Todos los campos son obligatorios.';
        $tipoMensaje = 'warning';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Galería | Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fdf8fc;
      font-family: 'Poppins', sans-serif;
    }

    .header-galeria {
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
      max-width: 600px;
      margin: 2rem auto;
      padding: 2rem;
      background: white;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }

    .alert {
      text-align: center;
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
  </style>
</head>
<body>

  <!-- ENCABEZADO CON BOTÓN VOLVER -->
  <div class="header-galeria d-flex justify-content-between align-items-center">
    <span class="ms-3">Galería</span>
    <a href="galeria-completa.php" class="btn volver-btn me-3">Volver</a>
  </div>

  <div class="form-container">
    <?php if ($mensaje): ?>
      <div id="mensaje" class="alert alert-<?php echo $tipoMensaje; ?>">
        <?php echo $mensaje; ?>
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Imagen</label>
        <input type="file" name="imagen" class="form-control" accept="image/*" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="3" required></textarea>
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
    }
  </script>

</body>
</html>
