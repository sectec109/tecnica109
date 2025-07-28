<?php
$conexion = new mysqli("localhost", "root", "", "escuela109");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$usuario = "administrador";  // el mismo nombre de usuario que ya tienes
$clave_plana = "admin109";   // la contraseña que quieres usar
$hash = password_hash($clave_plana, PASSWORD_DEFAULT);

// Actualizar el campo de contraseña
$sql = "UPDATE administradores SET contrasena = ? WHERE usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ss", $hash, $usuario);

if ($stmt->execute()) {
    echo "✅ Contraseña actualizada con éxito.";
} else {
    echo "❌ Error al actualizar: " . $stmt->error;
}
?>
