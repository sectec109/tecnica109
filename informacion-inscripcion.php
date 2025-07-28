<?php
include 'conexion.php';

$inscripciones = $conn->query("SELECT * FROM inscripcion ORDER BY id DESC");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $id = intval($_POST['eliminar_id']);
    $conn->query("DELETE FROM inscripcion WHERE id = $id");
    header("Location: informacion-inscripcion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Información de Inscripción</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
      position: relative;
    }

    .btn-volver {
      position: absolute;
      top: 1rem;
      right: 1rem;
      background-color: white;
      color: #800020;
      border: 2px solid #800020;
    }

    .btn-volver:hover {
      background-color: #800020;
      color: white;
    }

    .btn-agregar {
      background-color: #800020;
      color: white;
      font-weight: 500;
      margin: 1rem 0;
    }

    .btn-agregar:hover {
      background-color: #a01a3a;
    }

    .card {
      margin-bottom: 2rem;
      border: 1px solid #e3d1dc;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .card-title {
      color: #800020;
      font-weight: 600;
    }

    .section-title {
      font-weight: 600;
      margin-top: 1rem;
      color: #800020;
    }

    .btn-editar {
      background-color: #ffc107;
      color: #800020;
      font-weight: 500;
    }

    .btn-eliminar {
      background-color: #800020;
      color: white;
      font-weight: 500;
    }

    .modal-content {
      border: 2px solid #800020;
      border-radius: 10px;
    }

    .modal-title {
      color: #800020;
      font-weight: 600;
    }

    .modal-footer .btn-secondary {
      border: 1px solid #800020;
      color: #800020;
      background-color: white;
    }

    .modal-footer .btn-danger {
      background-color: #800020;
      border: none;
    }

    .modal-footer .btn-danger:hover {
      background-color: #a01a3a;
    }

    .modal-footer .btn-secondary:hover {
      background-color: #fce7ed;
    }
  </style>
</head>
<body>

<div class="header">
  Información de Inscripción
  <a href="admin-panel.php" class="btn btn-volver">Volver</a>
</div>

<div class="container mt-4">
  <div class="text-end">
    <a href="admin-inscripcion.php" class="btn btn-agregar">+ Agregar información</a>
  </div>

  <?php while ($info = $inscripciones->fetch_assoc()): ?>
    <div class="card p-4">
      <h4 class="card-title"><?= htmlspecialchars($info['titulo']) ?></h4>

      <div class="section-title">📌 Requisitos de inscripción:</div>
      <p><?= nl2br(htmlspecialchars($info['requisitos_inscripcion'])) ?></p>

      <div class="section-title">🔁 Requisitos de reinscripción:</div>
      <p><?= nl2br(htmlspecialchars($info['requisitos_reinscripcion'])) ?></p>

      <div class="section-title">📅 Fechas importantes:</div>
      <p><?= nl2br(htmlspecialchars($info['fechas_importantes'])) ?></p>

      <div class="section-title">❓ Preguntas frecuentes:</div>
      <p><?= nl2br(htmlspecialchars($info['preguntas_frecuentes'])) ?></p>

      <p class="text-muted mt-3 mb-1">📅 Publicado el: <?= date('d/m/Y', strtotime($info['fecha_creacion'])) ?></p>

      <div class="d-flex justify-content-end gap-2">
        <a href="editar-inscripcion.php?id=<?= $info['id'] ?>" class="btn btn-editar">✏️ Editar</a>
        <button class="btn btn-eliminar" data-bs-toggle="modal" data-bs-target="#modalEliminar<?= $info['id'] ?>">🗑️ Eliminar</button>
      </div>
    </div>

    <!-- Modal de eliminación -->
    <div class="modal fade" id="modalEliminar<?= $info['id'] ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header text-center">
            <h5 class="modal-title w-100">Confirmar Eliminación</h5>
          </div>
          <div class="modal-body text-center">
            <p>¿Estás segur@ de eliminar <strong><?= htmlspecialchars($info['titulo']) ?></strong>?</p>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <form method="POST">
              <input type="hidden" name="eliminar_id" value="<?= $info['id'] ?>">
              <button type="submit" class="btn btn-danger">Eliminar</button>
            </form>
          </div>
        </div>
      </div>
    </div>

  <?php endwhile; ?>
</div>

</body>
</html>
