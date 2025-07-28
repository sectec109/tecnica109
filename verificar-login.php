<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración base de datos
$host = "localhost";
$usuario_db = "root";
$contrasena_db = "";
$base_datos = "escuela109";

$conn = new mysqli($host, $usuario_db, $contrasena_db, $base_datos);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Solo continuar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login-admin.php");
    exit;
}

// Validar entrada del formulario
if (!isset($_POST['usuario']) || !isset($_POST['contraseña'])) {
    header("Location: login-admin.php?error=1");
    exit;
}

$usuario = $_POST['usuario'];
$clave = $_POST['contraseña'];

// Buscar al usuario
$sql = "SELECT * FROM administradores WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $fila = $resultado->fetch_assoc();
    $hash_guardado = $fila['contrasena'];

    if (password_verify($clave, $hash_guardado)) {
        $_SESSION['logueado'] = true;
        $_SESSION['usuario'] = $usuario;
        header("Location: admin-panel.php");
        exit;
    }
}

// Si falla el login
header("Location: login-admin.php?error=1");
exit;
?>
