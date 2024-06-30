<?php

include_once '../Database/connection.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])){
    $id_proyecto = $_POST['id_proyecto'];
    $Nombre = $_POST['Nombre'];
    $Descripcion = $_POST['Descripcion'];
    $Fecha_inicio = $_POST['Fecha_inicio'];
    $Fecha_final = $_POST['Fecha_final'];
    $Fecha_final_real = $_POST['Fecha_final_real'];
    $id_cliente = $_POST['id_cliente'];
    $id_estado = $_POST['id_estado'];
    $id_pago = $_POST['id_pago'];

    $sql = "UPDATE proyecto SET 
            Nombre='$Nombre',
            Descripcion='$Descripcion', 
            Fecha_inicio='$Fecha_inicio', 
            Fecha_final='$Fecha_final', 
            Fecha_final_real='$Fecha_final_real', 
            id_cliente='$id_cliente', 
            id_estado='$id_estado', 
            id_pago='$id_pago' 
            WHERE id_proyecto='$id_proyecto'";
            
    if ($conn->query($sql) === TRUE) {
        echo "Proyecto actualizado";
    }else {
        echo "Error al actualizar proyecto" . $conn->error;
    }
            
    $sql = "SELECT * FROM proyecto";
    $result = $conn->query($sql);
}



?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Proyectos</title>
</head>
<body>
    <h2>Proyectos</h2>
    <table border="1">
        <tr>
            <th>ID Proyecto</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Fecha Inicio</th>
            <th>Fecha Final</th>
            <th>Fecha Final Real</th>
            <th>ID Cliente</th>
            <th>Estado</th>
            <th>ID Pago</th>
            <th>Acciones</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <form method='POST' action=''>
                    <td><input type='text' name='id_proyecto' value='" . $row['id_proyecto'] . "' readonly></td>
                    <td><input type='text' name='Nombre' value='" . $row['Nombre'] . "'></td>
                    <td><input type='text' name='Descripcion' value='" . $row['Descripcion'] . "'></td>
                    <td><input type='date' name='Fecha_inicio' value='" . $row['Fecha_inicio'] . "'></td>
                    <td><input type='date' name='Fecha_final' value='" . $row['Fecha_final'] . "'></td>
                    <td><input type='date' name='Fecha_final_real' value='" . $row['Fecha_final_real'] . "'></td>
                    <td><input type='number' name='id_cliente' value='" . $row['id_cliente'] . "'></td>
                    <td><input type='number' name='id_estado' value='" . $row['id_estado'] . "'></td>
                    <td><input type='number' name='id_pago' value='" . $row['id_pago'] . "'></td>
                    <td><button type='submit' name='update'>Actualizar</button></td>
                    </form>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='10'>No hay proyectos</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$onn->close();
?>
