<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: login-admin.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: galeria-completa.php");
    exit();
}

$id = intval($_GET['id']);
$mensaje = '';
$tipoMensaje = '';

// Obtener datos del evento
$sql = "SELECT * FROM galeria WHERE id = $id";
$resultado = $conn->query($sql);

if ($resultado->num_rows === 0) {
    $mensaje = "⚠️ Evento no encontrado.";
    $tipoMensaje = 'warning';
} else {
    $evento = $resultado->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevaDescripcion = $conn->real_escape_string($_POST['descripcion']);
    $nuevaRuta = $evento['imagen'];

    if (!empty($_FILES['imagen']['name'])) {
        $carpetaDestino = 'fotos/galeria/';
        if (!file_exists($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        $nombreArchivo = basename($_FILES['imagen']['name']);
        $nuevaRuta = $carpetaDestino . $nombreArchivo;

        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $nuevaRuta)) {
            $mensaje = '❌ Error al subir la nueva imagen.';
            $tipoMensaje = 'danger';
        }
    }

    if (empty($mensaje)) {
        $sqlActualizar = "UPDATE galeria SET imagen = '$nuevaRuta', descripcion = '$nuevaDescripcion' WHERE id = $id";
        if ($conn->query($sqlActualizar)) {
            $mensaje = '✅ Evento actualizado correctamente.';
            $tipoMensaje = 'success';
            $resultado = $conn->query("SELECT * FROM galeria WHERE id = $id");
            $evento = $resultado->fetch_assoc();
        } else {
            $mensaje = '❌ Error al actualizar en la base de datos.';
            $tipoMensaje = 'danger';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Evento | Galería</title>
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

    .preview-img {
      max-height: 200px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>

  <!-- ENCABEZADO -->
  <div class="header-galeria d-flex justify-content-between align-items-center">
    <span class="ms-3">Editar Evento</span>
    <a href="galeria-completa.php" class="btn volver-btn me-3">Volver</a>
  </div>

  <div class="form-container">
    <?php if ($mensaje): ?>
      <div id="mensaje" class="alert alert-<?php echo $tipoMensaje; ?>">
        <?php echo $mensaje; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($evento)): ?>
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Imagen actual</label><br>
          <img src="<?php echo $evento['imagen']; ?>" class="img-fluid preview-img" alt="Imagen actual">
        </div>
        <div class="mb-3">
          <label class="form-label">Cambiar imagen (opcional)</label>
          <input type="file" name="imagen" class="form-control" accept="image/*">
        </div>
        <div class="mb-3">
          <label class="form-label">Descripción</label>
          <textarea name="descripcion" class="form-control" rows="3" required><?php echo htmlspecialchars($evento['descripcion']); ?></textarea>
        </div>
        <div class="d-flex justify-content-end">
          <button type="submit" class="btn publicar-btn">💾 Guardar cambios</button>
        </div>
      </form>
    <?php endif; ?>
  </div>

  <script>
    // Ocultar el mensaje después de 3 segundos
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
