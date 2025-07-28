<?php
session_start();
if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true) {
    header("Location: admin-panel.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Iniciar sesión | Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fff0f5;
      font-family: 'Poppins', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-box {
      background: white;
      padding: 3rem 2rem;
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
      position: relative;
    }

    .login-box h2 {
      font-weight: 600;
      color: #800020;
      margin-bottom: 1.5rem;
      text-align: center;
    }

    .btn-danger {
      background-color: #800020;
      border-color: #800020;
    }

    .btn-danger:hover {
      background-color: #a00030;
      border-color: #a00030;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 1.5rem;
      color: #800020;
      text-decoration: none;
      font-weight: 500;
    }

    .back-link:hover {
      text-decoration: underline;
      color: #a00030;
    }

    .error-message {
      background: #fff;
      border: 2px solid #800020;
      color: #800020;
      padding: 1rem;
      border-radius: 10px;
      text-align: center;
      margin-bottom: 1rem;
    }

    .password-toggle {
      position: relative;
    }

    .password-toggle-icon {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      cursor: pointer;
      color: #800020;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>Panel Administrativo</h2>

    <?php if (isset($_GET['error'])): ?>
      <div id="errorBox" class="error-message">Usuario o contraseña incorrectos</div>
    <?php endif; ?>

    <form action="verificar-login.php" method="POST">
      <div class="mb-3">
        <label class="form-label">Usuario</label>
        <input type="text" name="usuario" class="form-control" required>
      </div>

      <div class="mb-3 password-toggle">
        <label class="form-label">Contraseña</label>
        <input type="password" name="contraseña" id="pass" class="form-control" required>
        <span class="password-toggle-icon" onclick="togglePassword()">👁️</span>
      </div>

      <button type="submit" class="btn btn-danger w-100">Iniciar sesión</button>
    </form>

    <a href="index.html" class="back-link">← Volver a la página principal</a>
  </div>

  <script>
    function togglePassword() {
      const passField = document.getElementById('pass');
      passField.type = passField.type === 'password' ? 'text' : 'password';
    }

    window.onload = function () {
      const errorBox = document.getElementById("errorBox");
      if (errorBox) {
        setTimeout(() => {
          errorBox.style.display = "none";
        }, 5000);
      }
    };
  </script>
</body>
</html>
