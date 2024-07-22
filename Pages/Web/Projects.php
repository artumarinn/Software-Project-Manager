<?php

include_once '../../Database/connection.php';

// agregar proyecto
if (isset($_POST['add_project'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $actual_end_date = $_POST['actual_end_date'];
    $customer_id = $_POST['customer_id'];
    $status_id = $_POST['status_id'];
    $payment_id = $_POST['payment_id'];

    $sql = "INSERT INTO project (name, description, start_date, end_date, actual_end_date, customer_id, status_id, payment_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssiis", $name, $description, $start_date, $end_date, $actual_end_date, $customer_id, $status_id, $payment_id);

    if ($stmt->execute()) {
        echo "Proyecto agregado exitosamente";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// editar proyecto
if (isset($_POST['edit_project'])) {
    $project_id = $_POST['project_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $actual_end_date = $_POST['actual_end_date'];
    $customer_id = $_POST['customer_id'];
    $status_id = $_POST['status_id'];
    $payment_id = $_POST['payment_id'];

    $sql = "UPDATE project SET name=?, description=?, start_date=?, end_date=?, actual_end_date=?, customer_id=?, status_id=?, payment_id=?
            WHERE project_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssiiis", $name, $description, $start_date, $end_date, $actual_end_date, $customer_id, $status_id, $payment_id, $project_id);

    if ($stmt->execute()) {
        echo "Proyecto actualizado exitosamente";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// eliminar proyecto
if (isset($_POST['delete_project'])) {
    $project_id = $_POST['project_id'];

    $sql = "DELETE FROM project WHERE project_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $project_id);

    if ($stmt->execute()) {
        echo "Proyecto eliminado exitosamente";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
//obtener id de clientes
$estado = [];
$sql = "SELECT * FROM customer";
$result= $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $customer[] = $row;
    }
}

// obtener id de pagos
$payment = [];
$sql = "SELECT * FROM payment";
$result= $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $payment[] = $row;
    }
}

// obtener estados
$status = [];
$sql = "SELECT * FROM status";
$result= $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $status[] = $row;
    }
}

// Obtener proyectos
$projects = [];
$sql = "SELECT * FROM project";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Gestión de Proyectos</title>
    <style>
        .container {
            display: flex;
            justify-content: space-between;
        }
        .form-container, .table-container {
            width: 48%;
        }
        table {
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <div>
            <a href="http://localhost/UCH/BASE-DE-DATOS/Software-Project-Manager/Pages/Web/ControlPanel.php" class="web-title">Software Project Manager</a>
        </div>
        <nav>
            <a href="http://localhost/UCH/BASE-DE-DATOS/Software-Project-Manager/Pages/Web/Projects.php">Projects</a>
            <a href="http://localhost/UCH/BASE-DE-DATOS/Software-Project-Manager/Pages/Web/Requirements.php">Requirements</a>
            <a href="http://localhost/UCH/BASE-DE-DATOS/Software-Project-Manager/Pages/Web/Employee.php">Employee</a>
            <a href="http://localhost/UCH/BASE-DE-DATOS/Software-Project-Manager/Pages/Web/Customers.php">Customers</a>
            <a href="http://localhost/UCH/BASE-DE-DATOS/Software-Project-Manager/Pages/Web/Payment.php">Payment</a>
        </nav>
    </header>
    <h1>Gestión de Proyectos</h1>

    <div class="container">
        <div class="form-container">
            <!-- Formulario para agregar proyectos -->
            <h2>Agregar Proyecto</h2>
            <form action="" method="POST">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" required><br><br>

                <label for="description">Descripción:</label>
                <input type="text" id="description" name="description" required><br><br>

                <label for="start_date">Fecha de Inicio:</label>
                <input type="date" id="start_date" name="start_date" required><br><br>

                <label for="end_date">Fecha de Fin:</label>
                <input type="date" id="end_date" name="end_date" required><br><br>

                <label for="actual_end_date">Fecha de Fin Real:</label>
                <input type="date" id="actual_end_date" name="actual_end_date" required><br><br>

                <label for="customer_id">ID del Cliente:</label>
                <select id="customer_id" name="customer_id" required>
                    <?php foreach ($customer as $c) { ?>
                        <option value="<?php echo $c['customer_id']; ?>"><?php echo $c['customer_id']; ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="status_id">Estado:</label>
                <select id="status_id" name="status_id" required>
                    <?php foreach ($status as $s) { ?>
                        <option value="<?php echo $s['status_id']; ?>"><?php echo $s['description']; ?></option>
                    <?php } ?>
                </select><br><br>



                <button type="submit" name="add_project">Agregar Proyecto</button>
            </form>

            <!-- Formulario para editar proyectos -->
            <h2>Editar Proyecto</h2>
            <form action="" method="POST">
                <input type="hidden" id="project_id" name="project_id">
                <label for="edit_id">ID:</label>
                <input type="text" id="edit_id" name="project_id" required><br><br>

                <label for="edit_name">Nombre:</label>
                <input type="text" id="edit_name" name="name" required><br><br>

                <label for="edit_description">Descripción:</label>
                <input type="text" id="edit_description" name="description" required><br><br>

                <label for="edit_start_date">Fecha de Inicio:</label>
                <input type="date" id="edit_start_date" name="start_date" required><br><br>

                <label for="edit_end_date">Fecha de Fin:</label>
                <input type="date" id="edit_end_date" name="end_date" required><br><br>

                <label for="edit_actual_end_date">Fecha de Fin Real:</label>
                <input type="date" id="edit_actual_end_date" name="actual_end_date" required><br><br>

                <label for="edit_customer_id">ID del Cliente:</label>
                <select id="edit_customer_id" name="customer_id" required>
                    <?php foreach ($customer as $c) { ?>
                        <option value="<?php echo $c['customer_id']; ?>"><?php echo $c['customer_id']; ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="edit_status_id">Estado:</label>
                <select id="edit_status_id" name="status_id" required>
                    <?php foreach ($status as $s) { ?>
                        <option value="<?php echo $s['status_id']; ?>"><?php echo $s['description']; ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="edit_payment_id">ID del Pago:</label>
                <select id="edit_payment_id" name="payment_id" required>
                    <?php foreach ($payment as $p) { ?>
                        <option value="<?php echo $p['payment_id']; ?>"><?php echo $p['payment_id']; ?></option>
                    <?php } ?>
                </select><br><br>

                <button type="submit" name="edit_project">Actualizar Proyecto</button>
            </form>

            <!-- Formulario para eliminar proyectos -->
            <h2>Eliminar Proyecto</h2>
            <form action="" method="POST">
                <label for="delete_project_id">ID del Proyecto:</label>
                <input type="text" id="delete_project_id" name="project_id" required><br><br>
                <button type="submit" name="delete_project">Eliminar Proyecto</button>
            </form>
        </div>

        <div>
            <!-- Lista de proyectos -->
            <h2>Lista de Proyectos</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
                        <th>Fecha de Fin Real</th>
                        <th>ID del Cliente</th>
                        <th>ID del Estado</th>
                        <th>ID del Pago</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $project) { ?>
                        <tr>
                            <td><?php echo $project['project_id']; ?></td>
                            <td><?php echo $project['name']; ?></td>
                            <td><?php echo $project['description']; ?></td>
                            <td><?php echo $project['start_date']; ?></td>
                            <td><?php echo $project['end_date']; ?></td>
                            <td><?php echo $project['actual_end_date']; ?></td>
                            <td><?php echo $project['customer_id']; ?></td>
                            <td><?php echo $project['status_id']; ?></td>
                            <td><?php echo $project['payment_id']; ?></td>
                            <td><button onclick="editProject('<?php echo $project['project_id']; ?>', '<?php echo $project['name']; ?>', '<?php echo $project['description']; ?>', '<?php echo $project['start_date']; ?>', '<?php echo $project['end_date']; ?>', '<?php echo $project['actual_end_date']; ?>', '<?php echo $project['customer_id']; ?>', '<?php echo $project['status_id']; ?>', '<?php echo $project['payment_id']; ?>')">Editar</button></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        
        function editProject(project_id, name, description, start_date, end_date, actual_end_date, customer_id, status_id, payment_id) {
            document.getElementById('project_id').value = project_id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_start_date').value = start_date;
            document.getElementById('edit_end_date').value = end_date;
            document.getElementById('edit_actual_end_date').value = actual_end_date;
            document.getElementById('edit_customer_id').value = customer_id;
            document.getElementById('edit_status_id').value = status_id;
            document.getElementById('edit_payment_id').value = payment_id;
        }
    </script>
</body>
</html>
