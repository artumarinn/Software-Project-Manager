<?php
include_once '../../Database/connection.php';

$registerMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['createAccount'])) {
    $dni = $_POST['dni'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password == $confirm_password) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        try {
            $sql = "INSERT INTO employee(dni, password) VALUES ('" . $conn->real_escape_string($dni) . "','" . $conn->real_escape_string($password_hashed) . "')";
            if ($conn->query($sql) === TRUE) {
                $registerMessage = "Registration successful.";
            } else {
                throw new Exception($conn->error, $conn->errno);
            }
        } catch (Exception $e) {
            if ($e->getCode() == 1062) {
                $registerMessage = "User with DNI $dni is already registered.";
            } else {
                $registerMessage = "Error: " . $e->getMessage();
            }
        }
    } else {
        $registerMessage = "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>
    <div>
        <?php if ($registerMessage) { echo "<p>$registerMessage</p>"; } ?>
        <form method="POST">
            <label for="dni">DNI:</label><br>
            <input type="number" id="dni" name="dni" required><br><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <label for="confirm_password">Confirm Password:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required><br><br>
            <button type="submit" name="createAccount">Create Account</button>
            <a href="http://localhost/UCH/BASE-DE-DATOS/Software-Project-Manager/Pages/Auth/Login.php">Login</a>
        </form>
    </div>
</body>
</html>
