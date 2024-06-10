<?php
$conn = new mysqli('localhost', 'root', 'marin', 'sistemaconsultora');

// Verificar conexión
if ($conn->connect_error) {
    die('Conexión fallida: ' . $conn->connect_error);
}
?>

