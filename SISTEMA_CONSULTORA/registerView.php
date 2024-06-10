<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>
<body>
    <h1>Registro de Usuario</h1>
    <form action="register.php" method="POST">

        <label for="nombre_completo">Nombre Completo:</label><br>
        <input type="text" id="nombre_completo" name="nombre_completo" required><br><br>

        <label for="usuario">Usuario:</label><br>
        <input type="text" id="usuario" name="usuario" required><br><br>

        <label for="correo">Correo:</label><br>
        <input type="email" id="correo" name="correo" required><br><br>

        <label for="contraseña">Contraseña</label><br>
        <input type="password" id="contraseña" name="contraseña" required><br><br>

        <label for="rol">Rol</label><br>
        <select id="rol" name="rol" required><br><br>

            <?php

            $servername = "localhost";
            $username = "root";
            $password = "marin";
            $database = "sistemacosultora";

            $conn = new mysqli($servername, $username, $password, $database);

            if ($conn->connect_error) {
                die("Conexion fallida". $conn->connect_error);
            }

            $sql = "SELECT id_rol, Nombre_rol FROM rol";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='". $row["id_rol"] .">". $row["Nombre_rol"] . "</option>";
                }

            } else {
                echo "<option value=''>No hay roles disponibles</option>";
            }
            
            $conn->close();

            ?>

        </select>

        <button type="submit">Registrar</button>

    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Crear conexión
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // obtener datos del formulario
        $nombre_completo = $_POST['nombre_completo'];
        $correo = $_POST['correo'];
        $usuario = $_POST['usuario'];
        $contraseña = password_hash($_POST['contraseña'], PASSWORD_BCRYPT);
        $rol = $_POST['rol'];

        // Insertar datos en la tabla usuario
        $sql = "INSERT INTO usuario (Usuario, Nombre_completo, Correo, Contraseña, Rol) VALUES ('$usuario', '$nombre_completo', '$correo', '$contraseña', '$rol')";

        if ($conn->query($sql) === TRUE) {
            echo "<p>Registro exitoso</p>";
        } else {
            echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }

        $conn->close();
    }
    ?>
    
</body>
</html>

</body>
</html>

