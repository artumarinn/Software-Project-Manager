<?php

include_once '../../Database/connection.php';

// agregar requisito
if (isset($_POST['add_requirement'])) {
    $project_id = $_POST['project_id'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $actual_end_date = $_POST['actual_end_date'] ?: NULL; 
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

// editar requisito
if (isset($_POST['edit_requirement'])) {
    $requirement_id = $_POST['requirement_id'];
    $project_id = $_POST['project_id'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $actual_end_date = $_POST['actual_end_date'] ?: NULL; 
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

// eliminar requisito
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

// Obtener datos para formularios y tablas
// Obtener prioridades
$priorities = [];
$sql = "SELECT * FROM priority";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $priorities[] = $row;
    }
}

// Obtener estados
$statuses = [];
$sql = "SELECT * FROM status";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $statuses[] = $row;
    }
}

// Obtener empleados
$employees = [];
$sql = "SELECT * FROM employee";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $employees[] = $row;
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

// Obtener requisitos
$requirements = [];
$sql = "SELECT r.*, p.name AS project_name, e.first_name AS employee_name, pr.description AS priority_desc, s.description AS status_desc
        FROM requirements r
        JOIN project p ON r.project_id = p.project_id
        JOIN employee e ON r.employee_id = e.employee_id
        JOIN priority pr ON r.priority_id = pr.priority_id
        JOIN status s ON r.status_id = s.status_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
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
            width: 100%;
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
            <a href="http://localhost/UCH/BASE-DE-DATOS/Software-Project-Manager/Pages/Web/ControlPanel.php" class="web-title">Gestor de Proyectos de Software</a>
        </div>
        <nav>
            <a href="http://localhost/UCH/BASE-DE-DATOS/Software-Project-Manager/Pages/Web/Projects.php">Proyectos</a>
            <a href="http://localhost/UCH/BASE-DE-DATOS/Software-Project-Manager/Pages/Web/Requirements.php">Requisitos</a>
            <a href="http://localhost/UCH/BASE-DE-DATOS/Software-Project-Manager/Pages/Web/Employee.php">Empleados</a>
            <a href="http://localhost/UCH/BASE-DE-DATOS/Software-Project-Manager/Pages/Web/Customers.php">Clientes</a>
            <a href="http://localhost/UCH/BASE-DE-DATOS/Software-Project-Manager/Pages/Web/Payment.php">Pagos</a>
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
                    <?php foreach ($projects as $p) { ?>
                        <option value="<?php echo $p['project_id']; ?>"><?php echo $p['name']; ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="description">Descripción:</label>
                <input type="text" id="description" name="description" required><br><br>

                <label for="start_date">Fecha de Inicio:</label>
                <input type="date" id="start_date" name="start_date" required><br><br>

                <label for="end_date">Fecha de Fin:</label>
                <input type="date" id="end_date" name="end_date" required><br><br>

                <label for="actual_end_date">Fecha de Fin Real:</label>
                <input type="date" id="actual_end_date" name="actual_end_date"><br><br>

                <label for="employee_id">Empleado:</label>
                <select id="employee_id" name="employee_id" required>
                    <?php foreach ($employees as $e) { ?>
                        <option value="<?php echo $e['employee_id']; ?>"><?php echo $e['first_name']; ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="priority_id">Prioridad:</label>
                <select id="priority_id" name="priority_id" required>
                    <?php foreach ($priorities as $p) { ?>
                        <option value="<?php echo $p['priority_id']; ?>"><?php echo $p['description']; ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="status_id">Estado:</label>
                <select id="status_id" name="status_id" required>
                    <?php foreach ($statuses as $s) { ?>
                        <option value="<?php echo $s['status_id']; ?>"><?php echo $s['description']; ?></option>
                    <?php } ?>
                </select><br><br>

                <input type="hidden" id="requirement_id" name="requirement_id">
                <button type="submit" name="add_requirement">Agregar Requisito</button>
                <button type="submit" name="edit_requirement">Actualizar Requisito</button>
            </form>
        </div>

        <div class="table-container">
            <h2>Lista de Requisitos</h2>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Proyecto</th>
                            <th>Descripción</th>
                            <th>Fecha de Inicio</th>
                            <th>Fecha de Fin</th>
                            <th>Fecha de Fin Real</th>
                            <th>Empleado</th>
                            <th>Prioridad</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requirements as $r) { ?>
                            <tr>
                                <td><?php echo $r['project_name']; ?></td>
                                <td><?php echo $r['description']; ?></td>
                                <td><?php echo $r['start_date']; ?></td>
                                <td><?php echo $r['end_date']; ?></td>
                                <td><?php echo $r['actual_end_date'] ? $r['actual_end_date'] : 'N/A'; ?></td>
                                <td><?php echo $r['employee_name']; ?></td>
                                <td><?php echo $r['priority_desc']; ?></td>
                                <td><?php echo $r['status_desc']; ?></td>
                                <td>
                                    <button onclick="editRequirement(<?php echo $r['requirement_id']; ?>, '<?php echo $r['description']; ?>', '<?php echo $r['start_date']; ?>', '<?php echo $r['end_date']; ?>', '<?php echo $r['actual_end_date']; ?>', <?php echo $r['employee_id']; ?>, <?php echo $r['priority_id']; ?>, <?php echo $r['status_id']; ?>)">Editar</button>
                                    <form action="" method="POST" style="display:inline;">
                                        <input type="hidden" name="requirement_id" value="<?php echo $r['requirement_id']; ?>">
                                        <button type="submit" name="delete_requirement">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function editRequirement(id, description, startDate, endDate, actualEndDate, employeeId, priorityId, statusId) {
            document.getElementById('requirement_id').value = id;
            document.getElementById('description').value = description;
            document.getElementById('start_date').value = startDate;
            document.getElementById('end_date').value = endDate;
            document.getElementById('actual_end_date').value = actualEndDate ? actualEndDate : '';
            document.getElementById('employee_id').value = employeeId;
            document.getElementById('priority_id').value = priorityId;
            document.getElementById('status_id').value = statusId;
        }
    </script>
</body>
</html>
