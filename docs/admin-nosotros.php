<?php
session_start();
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: login-admin.php");
    exit();
}

include 'conexion.php';

$mensaje = '';
$tipoMensaje = '';

// Obtener datos existentes
$datos = [];
$secciones = ['mision', 'vision', 'historia', 'organigrama'];
foreach ($secciones as $seccion) {
    $query = "SELECT * FROM nosotros WHERE seccion = '$seccion' LIMIT 1";
    $resultado = $conn->query($query);
    $datos[$seccion] = $resultado->fetch_assoc();
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($secciones as $seccion) {
        $contenido = $_POST[$seccion];
        $titulo = ucfirst($seccion);

        // Imagen para organigrama
        if ($seccion === 'organigrama' && !empty($_FILES['organigrama_img']['name'])) {
            $carpeta = 'fotos/organigrama/';
            if (!file_exists($carpeta)) mkdir($carpeta, 0777, true);
            $archivo = basename($_FILES['organigrama_img']['name']);
            $ruta = $carpeta . $archivo;

            if (move_uploaded_file($_FILES['organigrama_img']['tmp_name'], $ruta)) {
                $contenido .= "\n<img src=\"$ruta\" alt=\"Organigrama\" class=\"img-fluid mt-2\">";
            }
        }

        // Escapar contenido para SQL
        $contenido_sql = $conn->real_escape_string($contenido);
        $titulo_sql = $conn->real_escape_string($titulo);

        $sql = "UPDATE nosotros 
                SET contenido = '$contenido_sql', titulo = '$titulo_sql', fecha_actualizacion = NOW()
                WHERE seccion = '$seccion'";
        $conn->query($sql);
    }

    $mensaje = '✅ Contenido actualizado correctamente.';
    $tipoMensaje = 'success';

    // Recargar datos
    foreach ($secciones as $seccion) {
        $query = "SELECT * FROM nosotros WHERE seccion = '$seccion' LIMIT 1";
        $resultado = $conn->query($query);
        $datos[$seccion] = $resultado->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Nosotros | Admin</title>
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
  </style>
</head>
<body>

  <div class="header-galeria d-flex justify-content-between align-items-center">
    <span class="ms-3">Administrar Contenido: Nosotros</span>
    <a href="informacion-MV.php" class="btn volver-btn me-3">Volver</a>
  </div>

  <div class="form-container">
    <?php if ($mensaje): ?>
      <div id="mensaje" class="alert alert-<?php echo $tipoMensaje; ?>">
        <?php echo $mensaje; ?>
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <?php foreach ($secciones as $seccion): ?>
        <div class="mb-4">
          <label for="<?php echo $seccion; ?>" class="form-label text-capitalize">
            <?php echo ucfirst($seccion); ?>
          </label>

          <?php if ($seccion === 'organigrama'): ?>
            <input type="file" name="organigrama_img" class="form-control mb-2" accept="image/*">
          <?php endif; ?>

          <textarea name="<?php echo $seccion; ?>" class="form-control" rows="4"><?php echo htmlspecialchars($datos[$seccion]['contenido'] ?? ''); ?></textarea>
        </div>
      <?php endforeach; ?>

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
