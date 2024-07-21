<?php
session_start();

include_once '../../Database/connection.php';

$loginMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {

    $dni = $_POST['dni'];
    $password = $_POST['password'];

    $sql = "SELECT password FROM employee WHERE dni = '$dni'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['dni'] = $dni; 
            header("Location: ../Web/ControlPanel.php"); 
            exit();
        } else {
            $loginMessage = "Incorrect password.";
        }
    } else {
        $loginMessage = "DNI not found.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/styleAuth.css">
    <title>Login</title>
</head>
<body>
<header>
        <h1>Software Project Manager</h1>
        <nav>
        <a href="http://localhost/UCH/BASE-DE-DATOS/Software-Project-Manager/Pages/Auth/Register.php">Register</a>
        </nav>
    </header>
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
