<?php
session_start();
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: login-admin.php");
    exit();
}

include 'conexion.php';

$mensaje = '';
$tipoMensaje = '';

if (!isset($_GET['seccion'])) {
    header("Location: informacion-MN.php");
    exit();
}

$seccion = $_GET['seccion'];
$query = "SELECT * FROM nosotros WHERE seccion = '$seccion' LIMIT 1";
$resultado = $conn->query($query);

if ($resultado->num_rows === 0) {
    header("Location: informacion-MN.php");
    exit();
}

$info = $resultado->fetch_assoc();

// Función para limpiar etiquetas <img>
function limpiarContenido($html) {
    return preg_replace('/<img[^>]*>/', '', $html);
}

// Obtener solo texto para el textarea (sin <img>)
$contenidoSoloTexto = limpiarContenido($info['contenido']);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contenido = $_POST['contenido'];
    $contenidoFinal = $contenido;

    // Si es organigrama y se sube una imagen, reemplazar imagen
    if ($seccion === 'organigrama' && !empty($_FILES['organigrama_img']['name'])) {
        $carpeta = 'fotos/organigrama/';
        if (!file_exists($carpeta)) mkdir($carpeta, 0777, true);

        $archivo = basename($_FILES['organigrama_img']['name']);
        $ruta = $carpeta . time() . '_' . preg_replace('/\s+/', '_', $archivo);

        if (move_uploaded_file($_FILES['organigrama_img']['tmp_name'], $ruta)) {
            // Reemplazar la imagen anterior en el contenido si existe
            $contenidoSinImg = limpiarContenido($contenidoFinal);
            $contenidoFinal = $contenidoSinImg . "\n" . '<img src="' . $ruta . '" alt="Organigrama" class="img-fluid mt-2">';
        }
    }

    // Preparar consulta con comillas escapadas correctamente
    $contenidoFinalSQL = $conn->real_escape_string($contenidoFinal);
    $sql = "UPDATE nosotros SET contenido = '$contenidoFinalSQL', fecha_actualizacion = NOW() WHERE seccion = '$seccion'";

    if ($conn->query($sql)) {
        $mensaje = "✅ Se actualizó correctamente.";
        $tipoMensaje = "success";
        $resultado = $conn->query("SELECT * FROM nosotros WHERE seccion = '$seccion' LIMIT 1");
        $info = $resultado->fetch_assoc();
        $contenidoSoloTexto = limpiarContenido($info['contenido']);
    } else {
        $mensaje = "❌ Error al actualizar.";
        $tipoMensaje = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar <?php echo ucfirst($seccion); ?></title>
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
      max-width: 800px;
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
  </style>
</head>
<body>

  <div class="header-galeria d-flex justify-content-between align-items-center">
    <span class="ms-3">Editar sección: <?php echo ucfirst($seccion); ?></span>
    <a href="informacion-MV.php" class="btn volver-btn me-3">Volver</a>
  </div>

  <div class="form-container">
    <?php if ($mensaje): ?>
      <div id="mensaje" class="alert alert-<?php echo $tipoMensaje; ?>">
        <?php echo $mensaje; ?>
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Contenido</label>
        <textarea name="contenido" class="form-control" rows="6" required><?php echo htmlspecialchars($contenidoSoloTexto); ?></textarea>
      </div>

      <?php if ($seccion === 'organigrama'): ?>
        <div class="mb-3">
          <label class="form-label">Cambiar imagen del organigrama</label>
          <input type="file" name="organigrama_img" class="form-control" accept="image/*">
        </div>
      <?php endif; ?>

      <div class="d-flex justify-content-end">
        <button type="submit" class="btn publicar-btn">💾 Guardar cambios</button>
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
