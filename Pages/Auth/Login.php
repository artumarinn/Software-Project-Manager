<?php
session_start();

include_once '../../Database/connection.php';

$loginMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $dni = $_POST['dni'];
    $password = $_POST['password'];

    $sql = "SELECT contraseña FROM empleado WHERE DNI = '$dni'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['contraseña'])) {
            $_SESSION['dni'] = $dni; // guardar el DNI en la sesión
            header("Location: ../ControlPanel.php"); // redirigir a una pagina de bienvenida
            exit();
        } else {
            $loginMessage = "Contraseña incorrecta.";
        }
    } else {
        $loginMessage = "DNI no encontrado.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div>
        <h1>Login</h1>
        <?php if ($loginMessage) { echo "<p>$loginMessage</p>"; } ?>
        <form method="POST">
            <label for="dni">DNI:</label><br>
            <input type="number" id="dni" name="dni" required><br><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>
</html>
