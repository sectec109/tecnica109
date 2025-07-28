<?php
include 'conexion.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: informacion-directivo.php");
    exit();
}

$mensaje = '';
$tipoMensaje = '';

$resultado = $conn->query("SELECT * FROM directorio WHERE id = $id LIMIT 1");
$registro = $resultado->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $cargo = $conn->real_escape_string($_POST['cargo']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);

    $sql = "UPDATE directorio SET 
                nombre='$nombre', 
                cargo='$cargo', 
                telefono='$telefono', 
                correo='$correo', 
                descripcion='$descripcion' 
            WHERE id=$id";

    if ($conn->query($sql)) {
        $mensaje = "✅ Información actualizada correctamente.";
        $tipoMensaje = "success";
        $resultado = $conn->query("SELECT * FROM directorio WHERE id = $id LIMIT 1");
        $registro = $resultado->fetch_assoc();
    } else {
        $mensaje = "❌ Ocurrió un error al actualizar.";
        $tipoMensaje = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Directorio</title>
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

    .btn-guardar {
      background-color: #800020;
      color: white;
      font-weight: 500;
      border: none;
    }

    .btn-guardar:hover {
      background-color: #a01a3a;
    }

    label {
      font-weight: 600;
    }

    textarea {
      min-height: 120px;
    }
  </style>
</head>
<body>

  <div class="header d-flex justify-content-between align-items-center">
    Editar Información del Directorio
    <a href="informacion-directivo.php" class="btn volver-btn">Volver</a>
  </div>

  <div class="form-container">
    <?php if ($mensaje): ?>
      <div id="mensaje" class="alert alert-<?= $tipoMensaje ?>">
        <?= $mensaje ?>
      </div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($registro['nombre']) ?>">
      </div>

      <div class="mb-3">
        <label for="cargo">Cargo</label>
        <input type="text" name="cargo" id="cargo" class="form-control" value="<?= htmlspecialchars($registro['cargo']) ?>">
      </div>

      <div class="mb-3">
        <label for="telefono">Teléfono</label>
        <input type="text" name="telefono" id="telefono" class="form-control" value="<?= htmlspecialchars($registro['telefono']) ?>">
      </div>

      <div class="mb-3">
        <label for="correo">Correo electrónico</label>
        <input type="email" name="correo" id="correo" class="form-control" value="<?= htmlspecialchars($registro['correo']) ?>">
      </div>

      <div class="mb-3">
        <label for="descripcion">Descripción</label>
        <textarea name="descripcion" id="descripcion" class="form-control"><?= htmlspecialchars($registro['descripcion']) ?></textarea>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-guardar">💾 Guardar cambios</button>
      </div>
    </form>
  </div>

  <script>
    window.onload = () => {
      const msg = document.getElementById("mensaje");
      if (msg) setTimeout(() => msg.style.display = "none", 3000);
    };
  </script>

</body>
</html>
