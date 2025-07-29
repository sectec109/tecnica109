<?php
session_start();
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: login-admin.php");
    exit();
}

include 'conexion.php';

// Eliminar contenido de sección
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['seccion_eliminar'])) {
    $seccionEliminar = $conn->real_escape_string($_POST['seccion_eliminar']);
    $conn->query("UPDATE nosotros SET contenido = '' WHERE seccion = '$seccionEliminar'");
    header("Location: informacion-MN.php");
    exit();
}

// Obtener datos
$datos = [];
$secciones = ['mision', 'vision', 'historia', 'organigrama'];
foreach ($secciones as $seccion) {
    $query = "SELECT * FROM nosotros WHERE seccion = '$seccion' LIMIT 1";
    $resultado = $conn->query($query);
    if ($resultado && $resultado->num_rows > 0) {
        $datos[$seccion] = $resultado->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Información: Nosotros</title>
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
      padding: 1.5rem;
      color: white;
      font-size: 1.5rem;
      font-weight: 600;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .volver-btn, .agregar-btn {
      background-color: white;
      color: #800020;
      border: 2px solid #800020;
      font-weight: 500;
    }

    .volver-btn:hover, .agregar-btn:hover {
      background-color: #800020;
      color: white;
    }

    .contenedor {
      max-width: 1000px;
      margin: 2rem auto;
      padding: 1rem;
    }

    .tarjeta {
      background: white;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
      margin-bottom: 2rem;
      padding: 1.5rem;
    }

    .tarjeta h4 {
      font-weight: bold;
      color: #800020;
    }

    .btn-opciones {
      margin-right: 0.5rem;
    }

    .tarjeta img {
      max-width: 100%;
      height: auto;
      margin-top: 1rem;
      border-radius: 8px;
    }
    .header {
  background-color: #800020;
  padding: 1.5rem;
  color: white;
  font-size: 1.5rem;
  font-weight: 600;
}

.btn-agregar {
  background-color: #800020;
  color: white;
  font-weight: 500;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 6px;
}

.btn-agregar:hover {
  background-color: #a01a3a;
  color: white;
}

.modal-confirmacion .modal-content {
  border: 2px solid #800020;
  border-radius: 10px;
  padding: 1rem;
  text-align: center;
}

.modal-confirmacion .modal-header {
  border-bottom: none;
  justify-content: center;
}

.modal-confirmacion .modal-title {
  font-weight: 600;
  color: #800020;
}

.modal-confirmacion .modal-body {
  font-size: 1rem;
  color: #333;
}

.modal-confirmacion .btn-cancelar {
  border: 1.5px solid #800020;
  color: #800020;
  background-color: white;
  font-weight: 500;
}

.modal-confirmacion .btn-cancelar:hover {
  background-color: #800020;
  color: white;
}

.modal-confirmacion .btn-eliminar {
  background-color: #800020;
  color: white;
  font-weight: 500;
  border: none;
}

.modal-confirmacion .btn-eliminar:hover {
  background-color: #a01a3a;
}

  </style>
</head>
<body>

  <!-- Encabezado -->
<div class="header d-flex justify-content-between align-items-center">
  <span class="ms-3">Información de nosotros</span>
  <a href="admin-panel.php" class="btn volver-btn me-3">Volver</a>
</div>

<!-- Botón Agregar debajo del encabezado -->
<div class="text-end mt-3 me-4">
  <a href="admin-nosotros.php" class="btn btn-agregar">➕ Agregar información</a>
</div>


  <div class="contenedor">
    <?php foreach ($datos as $seccion => $info): ?>
      <div class="tarjeta">
        <h4><?php echo htmlspecialchars($info['titulo']); ?></h4>
        <div><?php echo nl2br($info['contenido']); ?></div>

        <div class="mt-3 d-flex justify-content-end">
          <a href="editar-nosotros.php?seccion=<?php echo $seccion; ?>" class="btn btn-warning btn-opciones">✏️ Editar</a>

          <!-- Botón Eliminar con Modal -->
          <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminar_<?php echo $seccion; ?>">🗑️ Eliminar</button>
        </div>
      </div>

      <!-- Modal Confirmación Eliminar -->
<div class="modal fade modal-confirmacion" id="modalEliminar_<?php echo $seccion; ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmar Eliminación</h5>
      </div>
      <div class="modal-body">
        ¿Estás segura de eliminar la sección <strong><?php echo htmlspecialchars($info['titulo']); ?></strong>?
      </div>
      <div class="modal-footer justify-content-center border-0">
        <button type="button" class="btn btn-cancelar me-2" data-bs-dismiss="modal">Cancelar</button>
        <form method="POST">
          <input type="hidden" name="seccion_eliminar" value="<?php echo $seccion; ?>">
          <button type="submit" class="btn btn-eliminar">Eliminar</button>
        </form>
      </div>
    </div>
  </div>
</div>

    <?php endforeach; ?>
  </div>

</body>
</html>
