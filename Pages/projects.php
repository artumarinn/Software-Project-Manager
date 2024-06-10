<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects</title>
    <!--<link rel="stylesheet" type="text/css" href="styleProjects.css">-->
</head>
<body>
    <div class="container">
        <h1>Projects</h1>
        <div class="cards">
            <a href="#" class="card">
                <h2>Add Project</h2>
            </a>
            <?php
               
                $servername = "localhost";
                $username = "root";
                $password = "marin";
                $dbname = "sistemaconsultora";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
                    if (isset($_POST['nombre']) && isset($_POST['descripcion']) && isset($_POST['fecha_inicio']) && isset($_POST['fecha_final'])) {
                        
                        $nombre = $_POST['nombre'];
                        $descripcion = $_POST['descripcion'];
                        $fecha_inicio = $_POST['fecha_inicio'];
                        $fecha_final = $_POST['fecha_final'];

                        
                        $sql = "INSERT INTO proyecto (Nombre, Descripcion, Fecha_inicio, Fecha_final) VALUES ('$nombre', '$descripcion', '$fecha_inicio', '$fecha_final')";

                        if ($conn->query($sql) === TRUE) {
                            echo "<script>alert('Proyecto creado con exito');</script>";
                        } else {
                            echo "<script>alert('Error: " . $conn->error . "');</script>";
                        }
                    }
                }

                
                $sql = "SELECT * FROM proyecto";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="card">';
                        echo '<h2>' . $row["Nombre"] . '</h2>';
                        echo '<p>' . $row["Descripcion"] . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo "No hay proyectos";
                }
                $conn->close();
            ?>
        </div>
    </div>

    <div class="container">
        <h1>Add Project</h1>
        <form action="#" method="POST">
            <label for="nombre">Nombre:</label><br>
            <input type="text" id="nombre" name="nombre"><br>
            <label for="descripcion">Descripción:</label><br>
            <textarea id="descripcion" name="descripcion"></textarea><br>
            <label for="fecha_inicio">Fecha de Inicio:</label><br>
            <input type="date" id="fecha_inicio" name="fecha_inicio"><br>
            <label for="fecha_final">Fecha Final:</label><br>
            <input type="date" id="fecha_final" name="fecha_final"><br>
            <input type="submit" name="submit" value="Submit">
        </form>
    </div>
</body>
</html>
