<?php
include_once '../Database/connection.php';

$registerMessage="";

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    // verificar si se envian los fomularios
    if(isset($_POST['register'])) {
    $dni=$_POST['dni'];
    $password=$_POST['password'];
    $confirm_password=$_POST['confirm_password'];
    }

    if($password == $confirm_password) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO empleado(DNI, contraseña) VALUES ('$dni','$password_hashed')";

        if($conn->query($sql) === TRUE) {
            $registerMessage = "Registro exitoso";

        }else{
            $registerMessage = "Error" . $conn->error;
        }
            
    }else {
        $registerMessage = "Las contraseñas no coinciden";
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
            <label for="dni">Dni:</label><br>
            <input type="text" id="dni" name="dni" required><br><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <label for="confirm_password">Confirm password:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required><br><br>
            <button type="submit" name="register">Regsiter</button>
        </form>
    </div>
</body>
</html>