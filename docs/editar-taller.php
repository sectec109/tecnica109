<?php
include 'conexion.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: todos-talleres.php");
    exit();
}

$mensaje = '';
$tipoMensaje = '';

$resultado = $conn->query("SELECT * FROM talleres WHERE id = $id LIMIT 1");
$taller = $resultado->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'];
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);

    if (!empty($_FILES['imagen']['name'])) {
        $carpeta = 'fotos/talleres/';
        if (!file_exists($carpeta)) mkdir($carpeta, 0777, true);
        $archivo = basename($_FILES['imagen']['name']);
        $ruta = $carpeta . time() . '_' . $archivo;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);
    } else {
        $ruta = $taller['imagen'];
    }

    $sql = "UPDATE talleres SET tipo='$tipo', titulo='$titulo', descripcion='$descripcion', imagen='$ruta' WHERE id=$id";
    if ($conn->query($sql)) {
        $mensaje = '✅ Información actualizada correctamente.';
        $tipoMensaje = 'success';
        $resultado = $conn->query("SELECT * FROM talleres WHERE id = $id LIMIT 1");
        $taller = $resultado->fetch_assoc();
    } else {
        $mensaje = '❌ Ocurrió un error al actualizar.';
        $tipoMensaje = 'danger';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Taller o Disciplina</title>
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
      max-width: 800px;
      margin: 2rem auto;
      padding: 2rem;
      background: white;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }
    .btn-publicar {
      background-color: #800020;
      color: white;
      font-weight: 500;
      border: none;
    }
    .btn-publicar:hover {
      background-color: #a01a3a;
      color: white;
    }
    label {
      font-weight: 600;
    }
  </style>
</head>
<body>

  <div class="header d-flex justify-content-between align-items-center">
    Editar Taller / Disciplina
    <a href="todos-talleres.php" class="btn volver-btn">Volver</a>
  </div>

  <div class="form-container">
    <?php if ($mensaje): ?>
      <div id="mensaje" class="alert alert-<?= $tipoMensaje ?>" role="alert">
        <?= $mensaje ?>
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="tipo" class="form-label">Tipo</label>
        <select name="tipo" id="tipo" class="form-select" required>
          <option value="">-- Selecciona --</option>
          <option value="taller" <?= $taller['tipo'] === 'taller' ? 'selected' : '' ?>>Taller</option>
          <option value="disciplina" <?= $taller['tipo'] === 'disciplina' ? 'selected' : '' ?>>Disciplina</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="titulo" class="form-label">Título</label>
        <input type="text" name="titulo" id="titulo" class="form-control" value="<?= htmlspecialchars($taller['titulo']) ?>" required>
      </div>

      <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea name="descripcion" id="descripcion" class="form-control" rows="4" required><?= htmlspecialchars($taller['descripcion']) ?></textarea>
      </div>

      <div class="mb-3">
        <label for="imagen" class="form-label">Cambiar imagen (opcional)</label>
        <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*">
        <?php if (!empty($taller['imagen'])): ?>
          <div class="mt-2">
            <strong>Imagen actual:</strong><br>
            <img src="<?= $taller['imagen'] ?>" alt="Imagen actual" class="img-fluid mt-1" style="max-height: 150px;">
          </div>
        <?php endif; ?>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-publicar">💾 Guardar cambios</button>
      </div>
    </form>
  </div>

  <script>
    window.onload = () => {
      const msg = document.getElementById("mensaje");
      if (msg) {
        setTimeout(() => msg.style.display = "none", 3000);
      }
    };
  </script>

</body>
</html>
