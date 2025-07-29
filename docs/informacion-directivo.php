<?php
include 'conexion.php';

// Eliminar si se recibe ID por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $idEliminar = intval($_POST['eliminar_id']);
    $conn->query("DELETE FROM directorio WHERE id = $idEliminar");
}

// Obtener la información
$resultado = $conn->query("SELECT * FROM directorio ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Información del Directorio</title>
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
    .btn-volver, .btn-agregar {
      font-weight: 500;
    }
    .btn-volver {
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
      border: none;
    }
    .btn-agregar:hover {
      background-color: #a01a3a;
    }
    .card-directivo {
      background: white;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }
    .card-directivo h5 {
      color: #800020;
      font-weight: 600;
    }
    .card-directivo p {
      margin-bottom: 0.4rem;
    }
    .acciones {
      margin-top: 1rem;
    }
    .acciones a, .acciones form {
      display: inline-block;
    }
    .fecha {
      font-size: 0.9rem;
      color: gray;
    }

    /* Modal estilo institucional */
    .modal-content {
      border: 2px solid #800020;
      border-radius: 12px;
    }
    .modal-header {
      border-bottom: none;
      text-align: center;
    }
    .modal-title {
      color: #800020;
      font-weight: bold;
      width: 100%;
    }
    .modal-body {
      text-align: center;
    }
    .btn-cancelar {
      background-color: white;
      color: #800020;
      border: 2px solid #800020;
    }
    .btn-cancelar:hover {
      background-color: #800020;
      color: white;
    }
    .btn-eliminar {
      background-color: #800020;
      color: white;
    }
    .btn-eliminar:hover {
      background-color: #a01a3a;
    }
  </style>
</head>
<body>

  <div class="header d-flex justify-content-between align-items-center">
    <span class="ms-3">Directorio</span>
    <a href="admin-panel.php" class="btn btn-volver me-3">Volver</a>
  </div>

  <div class="container mt-3">
    <div class="text-end mb-3">
      <a href="admin-directorio.php" class="btn btn-agregar">➕ Agregar información</a>
    </div>

    <?php if ($resultado->num_rows > 0): ?>
      <?php while ($fila = $resultado->fetch_assoc()): ?>
        <div class="card-directivo">
          <h5><?= htmlspecialchars($fila['nombre']) ?></h5>
          <?php if (!empty($fila['cargo'])): ?>
            <p><strong>Cargo:</strong> <?= htmlspecialchars($fila['cargo']) ?></p>
          <?php endif; ?>
          <?php if (!empty($fila['telefono'])): ?>
            <p><strong>Teléfono:</strong> <?= htmlspecialchars($fila['telefono']) ?></p>
          <?php endif; ?>
          <?php if (!empty($fila['correo'])): ?>
            <p><strong>Correo:</strong> <?= htmlspecialchars($fila['correo']) ?></p>
          <?php endif; ?>
          <?php if (!empty($fila['descripcion'])): ?>
            <p><strong>Descripción:</strong><br><?= nl2br(htmlspecialchars($fila['descripcion'])) ?></p>
          <?php endif; ?>
          <p class="fecha">📅 Publicado el <?= date("d/m/Y", strtotime($fila['fecha_creacion'])) ?></p>

          <div class="acciones">
            <a href="editar-directorio.php?id=<?= $fila['id'] ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminar<?= $fila['id'] ?>">🗑️ Eliminar</button>
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalEliminar<?= $fila['id'] ?>" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
              </div>
              <div class="modal-body">
                ¿Estás segur@ de eliminar a <strong><?= htmlspecialchars($fila['nombre']) ?></strong>?
              </div>
              <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" style="display:inline;">
                  <input type="hidden" name="eliminar_id" value="<?= $fila['id'] ?>">
                  <button type="submit" class="btn btn-eliminar">Eliminar</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="alert alert-info text-center">Aún no hay información registrada.</div>
    <?php endif; ?>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
