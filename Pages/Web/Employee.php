<?php

include_once '../../Database/connection.php';

// agregar empleado
if (isset($_POST['add_employee'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $dni = $_POST['dni'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role_id = $_POST['role_id'];
    $salary = $_POST['salary'];
    $hire_date = $_POST['hire_date'];

    $sql = "INSERT INTO employee (first_name, last_name, dni, email, phone, role_id, salary, hire_date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssids", $first_name, $last_name, $dni, $email, $phone, $role_id, $salary, $hire_date);

    if ($stmt->execute()) {
        echo "Empleado agregado exitosamente";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// editar empleado
if (isset($_POST['edit_employee'])) {
    $employee_id = $_POST['employee_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $dni = $_POST['dni'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role_id = $_POST['role_id'];
    $salary = $_POST['salary'];
    $hire_date = $_POST['hire_date'];

    $sql = "UPDATE employee SET first_name=?, last_name=?, dni=?, email=?, phone=?, role_id=?, salary=?, hire_date=?
            WHERE employee_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssidsi", $first_name, $last_name, $dni, $email, $phone, $role_id, $salary, $hire_date, $employee_id);

    if ($stmt->execute()) {
        echo "Empleado actualizado exitosamente";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// eliminar empleado
if (isset($_POST['delete_employee'])) {
    $employee_id = $_POST['employee_id'];

    $sql = "DELETE FROM employee WHERE employee_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employee_id);

    if ($stmt->execute()) {
        echo "Empleado eliminado exitosamente";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// obtener roles
$roles = [];
$sql = "SELECT * FROM roles";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $roles[] = $row;
    }
}

// Obtener empleados
$employees = [];
$sql = "SELECT * FROM employee e
        JOIN roles r ON e.role_id = r.role_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $employees[] = $row;
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
    <title>Gestión de Empleados</title>
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
            border-collapse: collapse; /* Opcional: mejora la apariencia de las tablas */
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
    <h1>Gestión de Empleados</h1>

    <div class="container">
        <div class="form-container">
            <!-- Formulario para agregar empleados -->
            <h2>Agregar Empleado</h2>
            <form action="" method="POST">
                <label for="first_name">Nombre:</label>
                <input type="text" id="first_name" name="first_name" required><br><br>

                <label for="last_name">Apellido:</label>
                <input type="text" id="last_name" name="last_name" required><br><br>

                <label for="dni">DNI:</label>
                <input type="text" id="dni" name="dni" required><br><br>

                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required><br><br>

                <label for="phone">Teléfono:</label>
                <input type="text" id="phone" name="phone" required><br><br>

                <label for="role_id">Rol:</label>
                <select id="role_id" name="role_id" required>
                    <?php foreach ($roles as $r) { ?>
                        <option value="<?php echo $r['role_id']; ?>"><?php echo $r['role']; ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="salary">Salario:</label>
                <input type="text" id="salary" name="salary" required><br><br>

                <label for="hire_date">Fecha de Contratación:</label>
                <input type="date" id="hire_date" name="hire_date" required><br><br>

                <button type="submit" name="add_employee">Agregar Empleado</button>
            </form>

            <!-- Formulario para editar empleados -->
            <h2>Editar Empleado</h2>
            <form action="" method="POST">
                <input type="hidden" id="employee_id" name="employee_id">
                <label for="edit_first_name">Nombre:</label>
                <input type="text" id="edit_first_name" name="first_name" required><br><br>

                <label for="edit_last_name">Apellido:</label>
                <input type="text" id="edit_last_name" name="last_name" required><br><br>

                <label for="edit_dni">DNI:</label>
                <input type="text" id="edit_dni" name="dni" required><br><br>

                <label for="edit_email">Correo Electrónico:</label>
                <input type="email" id="edit_email" name="email" required><br><br>

                <label for="edit_phone">Teléfono:</label>
                <input type="text" id="edit_phone" name="phone" required><br><br>

                <label for="edit_role_id">Rol:</label>
                <select id="edit_role_id" name="role_id" required>
                    <?php foreach ($roles as $r) { ?>
                        <option value="<?php echo $r['role_id']; ?>"><?php echo $r['role']; ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="edit_salary">Salario:</label>
                <input type="text" id="edit_salary" name="salary" required><br><br>

                <label for="edit_hire_date">Fecha de Contratación:</label>
                <input type="date" id="edit_hire_date" name="hire_date" required><br><br>

                <button type="submit" name="edit_employee">Actualizar Empleado</button>
            </form>

            <!-- Formulario para eliminar empleados -->
            <h2>Eliminar Empleado</h2>
            <form action="" method="POST">
                <label for="delete_employee_id">ID del Empleado:</label>
                <input type="number" id="delete_employee_id" name="employee_id" required><br><br>

                <button type="submit" name="delete_employee">Eliminar Empleado</button>
            </form>
        </div>

        <div class="table-container">
            <h2>Lista de Empleados</h2>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>DNI</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th>Salario</th>
                            <th>Fecha de Contratación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees as $e) { ?>
                            <tr>
                                <td><?php echo $e['employee_id']; ?></td>
                                <td><?php echo $e['first_name']; ?></td>
                                <td><?php echo $e['last_name']; ?></td>
                                <td><?php echo $e['dni']; ?></td>
                                <td><?php echo $e['email']; ?></td>
                                <td><?php echo $e['phone']; ?></td>
                                <td><?php echo $e['role']; ?></td>
                                <td><?php echo $e['salary']; ?></td>
                                <td><?php echo $e['hire_date']; ?></td>
                                <td>
                                <button type="button" onclick="populateEditForm(
                                    <?php echo $e['employee_id']; ?>,
                                   '<?php echo addslashes($e['first_name']); ?>',
                                   '<?php echo addslashes($e['last_name']); ?>',
                                   '<?php echo addslashes($e['dni']); ?>',
                                   '<?php echo addslashes($e['email']); ?>',
                                   '<?php echo addslashes($e['phone']); ?>',
                                    <?php echo $e['role_id']; ?>,
                                   '<?php echo $e['salary']; ?>',
                                   '<?php echo $e['hire_date']; ?>'
                                )">Editar</button>
                                </td>       
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function populateEditForm(id, firstName, lastName, dni, email, phone, roleId, salary, hireDate) {
           document.getElementById('employee_id').value = id;
           document.getElementById('edit_first_name').value = firstName;
           document.getElementById('edit_last_name').value = lastName;
           document.getElementById('edit_dni').value = dni;
           document.getElementById('edit_email').value = email;
           document.getElementById('edit_phone').value = phone;
           document.getElementById('edit_role_id').value = roleId;
           document.getElementById('edit_salary').value = salary;
           document.getElementById('edit_hire_date').value = hireDate;
        }    
</script>

</body>
</html>
