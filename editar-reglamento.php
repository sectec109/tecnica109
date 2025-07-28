<?php
include 'conexion.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: reglamento-escolar.php");
    exit();
}

$mensaje = '';
$tipoMensaje = '';

$resultado = $conn->query("SELECT * FROM reglamento WHERE id = $id LIMIT 1");
$reglamento = $resultado->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = $conn->real_escape_string($_POST['descripcion']);

    // Subida de archivo opcional
    if (!empty($_FILES['archivo']['name'])) {
        $carpeta = 'fotos/reglamento/';
        if (!file_exists($carpeta)) mkdir($carpeta, 0777, true);
        $nombreArchivo = basename($_FILES['archivo']['name']);
        $ruta = $carpeta . time() . '_' . $nombreArchivo;
        move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta);
    } else {
        $ruta = $reglamento['archivo']; // conservar archivo anterior si no se cambia
    }

    $sql = "UPDATE reglamento SET descripcion='$descripcion', archivo='$ruta' WHERE id=$id";
    if ($conn->query($sql)) {
        $mensaje = '✅ Reglamento actualizado correctamente.';
        $tipoMensaje = 'success';
        $resultado = $conn->query("SELECT * FROM reglamento WHERE id = $id LIMIT 1");
        $reglamento = $resultado->fetch_assoc();
    } else {
        $mensaje = '❌ Error al actualizar.';
        $tipoMensaje = 'danger';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Reglamento</title>
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
    label {
      font-weight: 600;
    }
    .preview {
      margin-top: 1rem;
      text-align: center;
    }
    iframe, img {
      max-width: 100%;
      height: auto;
      border: 1px solid #ccc;
    }
  </style>
</head>
<body>

  <div class="header d-flex justify-content-between align-items-center">
    <span class="ms-3">Editar Reglamento Escolar</span>
    <a href="reglamento-escolar.php" class="btn volver-btn me-3">Volver</a>
  </div>

  <div class="form-container">
    <?php if ($mensaje): ?>
      <div id="mensaje" class="alert alert-<?= $tipoMensaje ?>">
        <?= $mensaje ?>
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="5" required><?= htmlspecialchars($reglamento['descripcion']) ?></textarea>
      </div>

      <div class="mb-3">
        <label for="archivo" class="form-label">Cambiar PDF o Imagen (opcional)</label>
        <input type="file" name="archivo" class="form-control" accept="application/pdf,image/*">
      </div>

      <?php if (!empty($reglamento['archivo'])): ?>
        <div class="preview">
          <label><strong>Archivo actual:</strong></label><br>
          <?php if (str_ends_with($reglamento['archivo'], '.pdf')): ?>
            <iframe src="<?= $reglamento['archivo'] ?>" width="100%" height="400px"></iframe>
          <?php else: ?>
            <img src="<?= $reglamento['archivo'] ?>" alt="Archivo actual">
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <div class="d-flex justify-content-end mt-3">
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
