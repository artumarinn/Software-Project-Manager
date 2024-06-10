<?php
require_once('C:\xampp\htdocs\UCH\BASE_DE_DATOS\CONSULTORA_SOFTWARE\DataBase\connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Obtener datos del formulario
    $nombre_completo = $conn->real_escape_string($_POST['nombre_completo']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $usuario = $conn->real_escape_string($_POST['usuario']);
    $contraseña = password_hash($conn->real_escape_string($_POST['contraseña']), PASSWORD_DEFAULT);
    $rol = $conn->real_escape_string($_POST['rol']);

    // Insertar datos en la tabla usuario
    $sql = "INSERT INTO usuario (Nombre_completo, Correo, Usuario, Contraseña, Rol) VALUES ('$nombre_completo','$correo','$usuario', '$contraseña', '$rol')";

    if ($conn->query($sql) === TRUE) {
        echo "Registro exitoso. ";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Cerrar conexión
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de Usuario</title>
    <link rel="stylesheet" type="text/css" href="../Css/style.css">
</head>
<body>
    <h2>Registro de Usuario</h2>
    <div class="form-container">
        <form method="POST" action="register.php">
            <label>Nombre Completo:</label>
            <input type="text" name="nombre_completo" required>

            <label>Correo:</label>
            <input type="email" name="correo" required>

            <label>Usuario:</label>
            <input type="text" name="usuario" required>

            <label>Contraseña:</label>
            <input type="password" name="contraseña" required>

            <label>Rol:</label>
            <select name="rol" required>
                <option value="" selected disabled>Seleccione un rol</option>
                <option value="Gerente de Proyecto">Gerente de Proyecto</option>
                <option value="Desarrollador">Desarrollador</option>
                <option value="Consultor">Consultor</option>
            </select>

            <button type="submit">Registrar</button>
            <a href='login.php'>Iniciar sesión</a>
        </form>
    </div>
</body>
</html>
