<?php
include 'conexion.php';

$comunicados = $conn->query("SELECT * FROM comunicados ORDER BY id DESC");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $idEliminar = intval($_POST['eliminar_id']);
    $conn->query("DELETE FROM comunicados WHERE id = $idEliminar");
    header("Location: todos-comunicados.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Todos los Comunicados</title>
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
      margin: 1rem;
      background-color: #800020;
      color: white;
      font-weight: 500;
    }

    .btn-agregar:hover {
      background-color: #a01a3a;
    }

    .card {
      border: 1px solid #e2d4dd;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      height: 100%;
    }

    .card-title {
      color: #800020;
      font-weight: 600;
    }

    .card-text {
      max-height: 150px;
      overflow-y: auto;
      font-size: 0.95rem;
    }

    .btn-editar, .btn-eliminar {
      font-size: 0.85rem;
    }

    .btn-editar {
      background-color: #ffc107;
      border: none;
      color: #800020;
    }

    .btn-eliminar {
      background-color: #800020;
      color: white;
      border: none;
    }

    .btn-eliminar:hover {
      background-color: #a01a3a;
    }

    .modal-content {
      border: 2px solid #800020;
      border-radius: 10px;
    }

    .modal-title {
      font-weight: 600;
      color: #800020;
    }

    .modal-footer .btn-secondary {
      border: 1px solid #800020;
      color: #800020;
      background-color: white;
    }

    .modal-footer .btn-secondary:hover {
      background-color: #f8d7da;
    }

    .modal-footer .btn-danger {
      background-color: #800020;
      border: none;
    }

    .modal-body {
      font-size: 0.95rem;
    }
  </style>
</head>
<body>

<div class="header">
  Comunicados
  <a href="admin-panel.php" class="btn btn-volver">Volver</a>
</div>

<div class="container mt-3">
  <div class="text-end">
    <a href="admin-comunicados.php" class="btn btn-agregar">➕ Agregar información</a>
  </div>

  <div class="row mt-4">
    <?php while ($row = $comunicados->fetch_assoc()): ?>
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
          <?php if (!empty($row['imagen'])): ?>
            <img src="<?= htmlspecialchars($row['imagen']) ?>" class="card-img-top" alt="Imagen comunicado" style="height: 300px; object-fit: cover;">
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($row['titulo']) ?></h5>
            <p class="card-text"><?= nl2br(htmlspecialchars($row['descripcion'])) ?></p>
          </div>
          <div class="card-footer d-flex justify-content-between">
            <a href="editar-comunicados.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-editar">✏️ Editar</a>
            <button class="btn btn-sm btn-eliminar" data-bs-toggle="modal" data-bs-target="#modalEliminar<?= $row['id'] ?>">🗑️ Eliminar</button>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="modalEliminar<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header text-center">
              <h5 class="modal-title w-100">Confirmar Eliminación</h5>
            </div>
            <div class="modal-body text-center">
              ¿Estás segur@ de eliminar <strong><?= htmlspecialchars($row['titulo']) ?></strong>?
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <form method="POST">
                <input type="hidden" name="eliminar_id" value="<?= $row['id'] ?>">
                <button type="submit" class="btn btn-danger">Eliminar</button>
              </form>
            </div>
          </div>
        </div>
      </div>

    <?php endwhile; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
