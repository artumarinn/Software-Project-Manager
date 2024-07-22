<?php

include_once '../../Database/connection.php';

// Agregar requisito
if (isset($_POST['add_requirement'])) {
    $project_id = $_POST['project_id'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $actual_end_date = $_POST['actual_end_date'];
    $employee_id = $_POST['employee_id'];
    $priority_id = $_POST['priority_id'];
    $status_id = $_POST['status_id'];

    $sql = "INSERT INTO requirements (project_id, description, start_date, end_date, actual_end_date, employee_id, priority_id, status_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssiii", $project_id, $description, $start_date, $end_date, $actual_end_date, $employee_id, $priority_id, $status_id);

    if ($stmt->execute()) {
        echo "Requisito agregado exitosamente";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Editar requisito
if (isset($_POST['edit_requirement'])) {
    $requirement_id = $_POST['requirement_id'];
    $project_id = $_POST['project_id'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $actual_end_date = $_POST['actual_end_date'];
    $employee_id = $_POST['employee_id'];
    $priority_id = $_POST['priority_id'];
    $status_id = $_POST['status_id'];

    $sql = "UPDATE requirements SET project_id=?, description=?, start_date=?, end_date=?, actual_end_date=?, employee_id=?, priority_id=?, status_id=?
            WHERE requirement_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssiiii", $project_id, $description, $start_date, $end_date, $actual_end_date, $employee_id, $priority_id, $status_id, $requirement_id);

    if ($stmt->execute()) {
        echo "Requisito actualizado exitosamente";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Eliminar requisito
if (isset($_POST['delete_requirement'])) {
    $requirement_id = $_POST['requirement_id'];

    $sql = "DELETE FROM requirements WHERE requirement_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $requirement_id);

    if ($stmt->execute()) {
        echo "Requisito eliminado exitosamente";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Obtener proyectos
$projects = [];
$sql = "SELECT project_id, name FROM project";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}

// Obtener empleados
$employees = [];
$sql = "SELECT employee_id, CONCAT(first_name, ' ', last_name) AS employee_name FROM employee";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}

// Obtener prioridades
$priorities = [];
$sql = "SELECT priority_id, description FROM priority";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $priorities[] = $row;
    }
}

// Obtener estados
$statuses = [];
$sql = "SELECT status_id, description FROM status";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $statuses[] = $row;
    }
}

// Obtener requisitos
$requirements = [];
$sql = "SELECT r.requirement_id, p.name AS project_name, r.description, r.start_date, r.end_date, r.actual_end_date, CONCAT(e.first_name, ' ', e.last_name) AS employee_name, pr.description AS priority_description, s.description AS status_description
        FROM requirements r
        JOIN project p ON r.project_id = p.project_id
        JOIN employee e ON r.employee_id = e.employee_id
        JOIN priority pr ON r.priority_id = pr.priority_id
        JOIN status s ON r.status_id = s.status_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $requirements[] = $row;
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
    <title>Gestión de Requisitos</title>
    <style>
        .container {
            display: flex;
            justify-content: center;
        }
        .table-container {
            width: 48%; 
            margin-left: 10px; 
        }
        .form-container, .table-container {
            width: 48%;
        }
        .table-scroll {
            max-height: 600px; 
            overflow-y: auto; 
            border: 1px solid #ddd; 
            padding: 10px; 
        }
        table {
            width: 150%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
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
    <h1>Gestión de Requisitos</h1>

    <div class="container">
        <div class="form-container">
            <!-- Formulario para agregar requisitos -->
            <h2>Agregar Requisito</h2>
            <form action="" method="POST">
                <label for="project_id">Proyecto:</label>
                <select id="project_id" name="project_id" required>
                    <?php foreach ($projects as $project) { ?>
                        <option value="<?php echo $project['project_id']; ?>"><?php echo $project['name']; ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="description">Descripción:</label>
                <textarea id="description" name="description" required></textarea><br><br>

                <label for="start_date">Fecha de Inicio:</label>
                <input type="date" id="start_date" name="start_date" required><br><br>

                <label for="end_date">Fecha de Fin:</label>
                <input type="date" id="end_date" name="end_date" required><br><br>

                <label for="actual_end_date">Fecha Real de Fin:</label>
                <input type="date" id="actual_end_date" name="actual_end_date"><br><br>

                <label for="employee_id">Empleado:</label>
                <select id="employee_id" name="employee_id" required>
                    <?php foreach ($employees as $employee) { ?>
                        <option value="<?php echo $employee['employee_id']; ?>"><?php echo $employee['employee_name']; ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="priority_id">Prioridad:</label>
                <select id="priority_id" name="priority_id" required>
                    <?php foreach ($priorities as $priority) { ?>
                        <option value="<?php echo $priority['priority_id']; ?>"><?php echo $priority['description']; ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="status_id">Estado:</label>
                <select id="status_id" name="status_id" required>
                    <?php foreach ($statuses as $status) { ?>
                        <option value="<?php echo $status['status_id']; ?>"><?php echo $status['description']; ?></option>
                    <?php } ?>
                </select><br><br>

                <button type="submit" name="add_requirement">Agregar Requisito</button>
            </form>

            <!-- Formulario para editar requisitos -->
            <h2>Editar Requisito</h2>
            <form action="" method="POST">
                <input type="hidden" id="requirement_id" name="requirement_id">
                <label for="edit_project_id">Proyecto:</label>
                <select id="edit_project_id" name="project_id" required>
                    <?php foreach ($projects as $project) { ?>
                        <option value="<?php echo $project['project_id']; ?>"><?php echo $project['name']; ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="edit_description">Descripción:</label>
                <textarea id="edit_description" name="description" required></textarea><br><br>

                <label for="edit_start_date">Fecha de Inicio:</label>
                <input type="date" id="edit_start_date" name="start_date" required><br><br>

                <label for="edit_end_date">Fecha de Fin:</label>
                <input type="date" id="edit_end_date" name="end_date" required><br><br>

                <label for="edit_actual_end_date">Fecha Real de Fin:</label>
                <input type="date" id="edit_actual_end_date" name="actual_end_date"><br><br>

                <label for="edit_employee_id">Empleado:</label>
                <select id="edit_employee_id" name="employee_id" required>
                    <?php foreach ($employees as $employee) { ?>
                        <option value="<?php echo $employee['employee_id']; ?>"><?php echo $employee['employee_name']; ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="edit_priority_id">Prioridad:</label>
                <select id="edit_priority_id" name="priority_id" required>
                    <?php foreach ($priorities as $priority) { ?>
                        <option value="<?php echo $priority['priority_id']; ?>"><?php echo $priority['description']; ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="edit_status_id">Estado:</label>
                <select id="edit_status_id" name="status_id" required>
                    <?php foreach ($statuses as $status) { ?>
                        <option value="<?php echo $status['status_id']; ?>"><?php echo $status['description']; ?></option>
                    <?php } ?>
                </select><br><br>

                <button type="submit" name="edit_requirement">Actualizar Requisito</button>
            </form>

            <!-- Formulario para eliminar requisitos -->
            <h2>Eliminar Requisito</h2>
            <form action="" method="POST">
                <label for="delete_requirement_id">ID del Requisito:</label>
                <input type="text" id="delete_requirement_id" name="requirement_id" required><br><br>
                <button type="submit" name="delete_requirement">Eliminar Requisito</button>
            </form>
        </div>

        <div class="table-container">
            <h2>Lista de Requisitos</h2>
            <div class="table-scroll">
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Proyecto</th>
                        <th>Descripción</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
                        <th>Fecha Real de Fin</th>
                        <th>Empleado</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requirements as $requirement) { ?>
                        <tr onclick="editRequirement('<?php echo $requirement['requirement_id']; ?>', '<?php echo $requirement['project_id']; ?>', '<?php echo $requirement['description']; ?>', '<?php echo $requirement['start_date']; ?>', '<?php echo $requirement['end_date']; ?>', '<?php echo $requirement['actual_end_date']; ?>', '<?php echo $requirement['employee_id']; ?>', '<?php echo $requirement['priority_id']; ?>', '<?php echo $requirement['status_id']; ?>')">
                            <td><?php echo $requirement['requirement_id']; ?></td>
                            <td><?php echo $requirement['project_name']; ?></td>
                            <td><?php echo $requirement['description']; ?></td>
                            <td><?php echo $requirement['start_date']; ?></td>
                            <td><?php echo $requirement['end_date']; ?></td>
                            <td><?php echo $requirement['actual_end_date']; ?></td>
                            <td><?php echo $requirement['employee_name']; ?></td>
                            <td><?php echo $requirement['priority_description']; ?></td>
                            <td><?php echo $requirement['status_description']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>

    <script>
        function editRequirement(requirement_id, project_id, description, start_date, end_date, actual_end_date, employee_id, priority_id, status_id) {
            document.getElementById('requirement_id').value = requirement_id;
            document.getElementById('edit_project_id').value = project_id;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_start_date').value = start_date;
            document.getElementById('edit_end_date').value = end_date;
            document.getElementById('edit_actual_end_date').value = actual_end_date;
            document.getElementById('edit_employee_id').value = employee_id;
            document.getElementById('edit_priority_id').value = priority_id;
            document.getElementById('edit_status_id').value = status_id;
        }
    </script>
</body>
</html>
