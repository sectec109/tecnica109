<?php
include 'conexion.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: informacion-inscripcion.php");
    exit();
}

$mensaje = '';
$tipoMensaje = '';

$resultado = $conn->query("SELECT * FROM inscripcion WHERE id = $id LIMIT 1");
$inscripcion = $resultado->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $requisitos_inscripcion = $conn->real_escape_string($_POST['requisitos_inscripcion']);
    $requisitos_reinscripcion = $conn->real_escape_string($_POST['requisitos_reinscripcion']);
    $fechas_importantes = $conn->real_escape_string($_POST['fechas_importantes']);
    $preguntas_frecuentes = $conn->real_escape_string($_POST['preguntas_frecuentes']);

    $sql = "UPDATE inscripcion SET 
                titulo = '$titulo',
                requisitos_inscripcion = '$requisitos_inscripcion',
                requisitos_reinscripcion = '$requisitos_reinscripcion',
                fechas_importantes = '$fechas_importantes',
                preguntas_frecuentes = '$preguntas_frecuentes'
            WHERE id = $id";

    if ($conn->query($sql)) {
        $mensaje = "✅ Información actualizada correctamente.";
        $tipoMensaje = "success";
        $resultado = $conn->query("SELECT * FROM inscripcion WHERE id = $id LIMIT 1");
        $inscripcion = $resultado->fetch_assoc();
    } else {
        $mensaje = "❌ Error al actualizar: " . $conn->error;
        $tipoMensaje = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Inscripción</title>
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
    }

    .publicar-btn:hover {
      background-color: #a01a3a;
    }

    textarea {
      min-height: 1000px;
      resize: vertical;
      overflow-y: auto;
    }

    label {
      font-weight: 600;
    }
  </style>
</head>
<body>

<div class="header d-flex justify-content-between align-items-center">
  <span class="ms-3">Editar Información de Inscripción</span>
  <a href="informacion-inscripcion.php" class="btn volver-btn me-3">Volver</a>
</div>

<div class="form-container">
  <?php if ($mensaje): ?>
    <div id="mensaje" class="alert alert-<?= $tipoMensaje ?>">
      <?= $mensaje ?>
    </div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label for="titulo" class="form-label">Título</label>
      <input type="text" name="titulo" id="titulo" class="form-control" value="<?= htmlspecialchars($inscripcion['titulo']) ?>" required>
    </div>

    <div class="mb-3">
      <label for="requisitos_inscripcion" class="form-label">Requisitos para Inscripción</label>
      <textarea name="requisitos_inscripcion" id="requisitos_inscripcion" class="form-control" required><?= htmlspecialchars($inscripcion['requisitos_inscripcion']) ?></textarea>
    </div>

    <div class="mb-3">
      <label for="requisitos_reinscripcion" class="form-label">Requisitos para Reinscripción</label>
      <textarea name="requisitos_reinscripcion" id="requisitos_reinscripcion" class="form-control" required><?= htmlspecialchars($inscripcion['requisitos_reinscripcion']) ?></textarea>
    </div>

    <div class="mb-3">
      <label for="fechas_importantes" class="form-label">Fechas Importantes</label>
      <textarea name="fechas_importantes" id="fechas_importantes" class="form-control" required><?= htmlspecialchars($inscripcion['fechas_importantes']) ?></textarea>
    </div>

    <div class="mb-3">
      <label for="preguntas_frecuentes" class="form-label">Preguntas Frecuentes</label>
      <textarea name="preguntas_frecuentes" id="preguntas_frecuentes" class="form-control" required><?= htmlspecialchars($inscripcion['preguntas_frecuentes']) ?></textarea>
    </div>

    <div class="text-end">
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
<script>
  function autoResizeTextarea(el) {
    el.style.height = 'auto';  // Reinicia
    el.style.height = (el.scrollHeight) + 'px'; // Ajusta a contenido
  }

  document.querySelectorAll('textarea').forEach(textarea => {
    textarea.addEventListener('input', function () {
      autoResizeTextarea(this);
    });
    autoResizeTextarea(textarea); // Ajusta al cargar
  });

  window.onload = function () {
    const alerta = document.getElementById('mensaje');
    if (alerta) {
      setTimeout(() => alerta.style.display = 'none', 3000);
    }
  };
</script>
</body>
</html>
