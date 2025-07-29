<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "escuela109"; // Cambia si tu base de datos se llama diferente

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
