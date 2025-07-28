<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: login-admin.php");
    exit();
}

// Eliminar si llega un POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $idEliminar = intval($_POST['eliminar_id']);
    $sql = "DELETE FROM galeria WHERE id = $idEliminar";
    $conn->query($sql);
    header("Location: galeria-completa.php");
    exit();
}

$sql = "SELECT * FROM galeria ORDER BY id DESC";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Galería completa | Admin</title>
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

    .agregar-btn {
      background-color: #800020;
      color: white;
      font-weight: 500;
      padding: 0.5rem 1.2rem;
      border-radius: 6px;
      border: none;
      margin: 1.5rem;
    }

    .agregar-btn:hover {
      background-color: #a01a3a;
    }

    .card-img-top {
      height: 200px;
      object-fit: cover;
    }

    .event-card {
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      border-radius: 12px;
    }

    .btn-editar {
      background-color: #ffc107;
      color: black;
    }

    .btn-eliminar {
      background-color: #dc3545;
      color: white;
    }

    .btn-editar:hover {
      background-color: #e0a800;
      color: white;
    }

    .btn-eliminar:hover {
      background-color: #bd2130;
    }

    .modal-content {
      border: 2px solid #800020;
      border-radius: 12px;
    }

    .modal-header h5 {
      color: #800020;
      font-weight: bold;
    }

    .modal-footer .btn-cancelar {
      background-color: white;
      color: #800020;
      border: 2px solid #800020;
    }

    .modal-footer .btn-cancelar:hover {
      background-color: #800020;
      color: white;
    }

    .modal-footer .btn-eliminar {
      background-color: #800020;
      color: white;
      border: none;
    }

    .modal-footer .btn-eliminar:hover {
      background-color: #a01a3a;
    }
  </style>
</head>
<body>

  <!-- ENCABEZADO -->
  <div class="header-galeria d-flex justify-content-between align-items-center">
    <span class="ms-3">Galería</span>
    <a href="admin-panel.php" class="btn volver-btn me-3">Volver</a>
  </div>

  <!-- BOTÓN AGREGAR -->
  <div class="text-end">
    <a href="admin-galeria.php" class="btn agregar-btn">➕ Agregar información</a>
  </div>

  <!-- GALERÍA -->
  <div class="container my-4">
    <div class="row g-4">
      <?php while ($fila = $resultado->fetch_assoc()): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card event-card">
            <img src="<?php echo $fila['imagen']; ?>" class="card-img-top" alt="Evento">
            <div class="card-body">
              <p class="card-text"><?php echo htmlspecialchars($fila['descripcion']); ?></p>
              <div class="d-flex justify-content-between">
                <a href="editar-galeria.php?id=<?php echo $fila['id']; ?>" class="btn btn-editar btn-sm">✏️ Editar</a>
                <button class="btn btn-eliminar btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminar" 
                        data-id="<?php echo $fila['id']; ?>" data-descripcion="<?php echo htmlspecialchars($fila['descripcion']); ?>">
                  🗑️ Eliminar
                </button>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <!-- MODAL ELIMINACIÓN -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" class="modal-content p-3" style="border: 2px solid #800020; border-radius: 10px;">
      <div class="text-center mb-3">
        <h5 style="color: #800020; font-weight: bold;">Confirmar Eliminación</h5>
      </div>
      <div class="modal-body text-center">
        <p>¿Estás segur@ de eliminar <strong id="textoDescripcion"></strong>?</p>
        <input type="hidden" name="eliminar_id" id="inputEliminarId">
      </div>
      <div class="modal-footer justify-content-center border-top-0">
        <button type="button" class="btn" data-bs-dismiss="modal" style="border: 1.8px solid #800020; color: #800020; font-weight: 500;">
          Cancelar
        </button>
        <button type="submit" class="btn" style="background-color: #800020; color: white; font-weight: 500;">
          Eliminar
        </button>
      </div>
    </form>
  </div>
</div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  const modal = document.getElementById('modalEliminar');
  modal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    const descripcion = button.getAttribute('data-descripcion');

    document.getElementById('inputEliminarId').value = id;
    document.getElementById('textoDescripcion').innerText = descripcion;
  });
</script>

</body>
</html>
