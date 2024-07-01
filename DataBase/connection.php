<?php
$conn = new mysqli('localhost', 'root', 'marin', 'software_project_manager');

// Verificar conexión bd
if ($conn->connect_error) {
    die('Failed connection: ' . $conn->connect_error);
}
?>

