<?php
require_once('C:\xampp\htdocs\UCH\BASE_DE_DATOS\CONSULTORA_SOFTWARE\DataBase\connection.php');

session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Obtener datos del formulario
    $usuario = $conn->real_escape_string($_POST['usuario']);
    $contraseña = $conn->real_escape_string($_POST['contraseña']);

    // Obtener datos del usuario de la base de datos
    $result = $conn->query("SELECT * FROM usuario WHERE Usuario='$usuario' OR Correo='$usuario'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verificar la contraseña
        if (password_verify($contraseña, $row['Contraseña'])) {

            // Iniciar sesión
            $_SESSION['usuario'] = $row['Usuario'];
            $_SESSION['correo'] = $row['Correo'];
            header('Location: control_panel.php');
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario o correo electrónico no encontrado.";
    }

    // Cerrar conexión
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" type="text/css" href="../Css/style.css">
</head>
<body>
    <h2>Inicio de Sesión</h2>
    <div>
        <form method="POST" action="login.php">
            <label>Usuario o Correo Electrónico:</label><br>
            <input type="text" name="usuario" required><br><br>
            <label>Contraseña:</label><br>
            <input type="password" name="contraseña" required><br><br>
            <button type="submit">Iniciar Sesión</button>
        </form>
    </div>
    <?php
    if (isset($error)) {
        echo "<p style='color:red;'>$error</p>";
    }
    ?>
</body>
</html>