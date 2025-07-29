<?php
include 'conexion.php';

$reglamento = $conn->query("SELECT * FROM reglamento ORDER BY id DESC LIMIT 1")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $idEliminar = intval($_POST['eliminar_id']);
    $conn->query("DELETE FROM reglamento WHERE id = $idEliminar");
    header("Location: reglamento-escolar.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reglamento Escolar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
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
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .btn-volver {
      background-color: white;
      color: #800020;
      border: 2px solid #800020;
      font-weight: 500;
    }

    .btn-volver:hover {
      background-color: #800020;
      color: white;
    }

    .btn-agregar {
      background-color: #800020;
      color: white;
      font-weight: 500;
    }

    .btn-agregar:hover {
      background-color: #a01a3a;
    }

    .documento {
      width: 90%;
      height: auto;
      max-height: 500px;
      object-fit: contain;
      border: 1px solid #ddd;
      border-radius: 9px;
    }

    .descripcion {
      font-size: 1rem;
    }

    .acciones {
      margin-top: 1rem;
    }

    /* Modal personalizado */
    .modal-content {
      border: 2px solid #800020;
      border-radius: 10px;
    }

    .modal-header {
      border-bottom: none;
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
  </style>
</head>
<body>

  <div class="header">
  Reglamento Escolar
  <a href="admin-panel.php" class="btn btn-volver">Volver</a>
</div>

<div class="container mt-3">
  <div class="text-end">
    <a href="admin-reglamento.php" class="btn btn-agregar">➕ Agregar información</a>
  </div>
  <div class="container my-4">
    <?php if ($reglamento): ?>
      <div class="row align-items-start">
        <div class="col-md-7 mb-3">
          <?php if (preg_match('/\.pdf$/i', $reglamento['archivo'])): ?>
            <embed src="<?= $reglamento['archivo'] ?>" type="application/pdf" width="100%" height="500px" />
          <?php else: ?>
            <img src="<?= $reglamento['archivo'] ?>" class="documento" alt="Reglamento">
          <?php endif; ?>
        </div>
        <div class="col-md-5">
          <div class="descripcion"><?= nl2br(htmlspecialchars($reglamento['descripcion'])) ?></div>
          <p class="text-muted mt-3">Publicado el: <?= date('d/m/Y', strtotime($reglamento['fecha_publicacion'])) ?></p>
          <div class="acciones d-flex gap-2">
            <a href="editar-reglamento.php?id=<?= $reglamento['id'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminar<?= $reglamento['id'] ?>">🗑️ Eliminar</button>
          </div>
        </div>
      </div>

      <!-- Modal de eliminación -->
      <div class="modal fade" id="modalEliminar<?= $reglamento['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header text-center">
              <h5 class="modal-title w-100">Confirmar Eliminación</h5>
            </div>
            <div class="modal-body text-center">
              <p>¿Estás segur@ de eliminar este reglamento?</p>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <form method="POST">
                <input type="hidden" name="eliminar_id" value="<?= $reglamento['id'] ?>">
                <button type="submit" class="btn btn-danger">Eliminar</button>
              </form>
            </div>
          </div>
        </div>
      </div>

    <?php else: ?>
      <div class="alert alert-info text-center">
        No se ha publicado ningún reglamento aún.
      </div>
    <?php endif; ?>
  </div>

</body>
</html>
