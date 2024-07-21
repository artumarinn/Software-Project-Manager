<?php

include_once '../../Database/connection.php';

// agregar empleado
if (isset($_POST['add_employee'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $dni = $_POST['dni'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role_id = $_POST['role'];
    $salary = $_POST['salary'];
    $hire_date = $_POST['hire_date'];

    $sql = "INSERT INTO employee (first_name, last_name, dni, email, phone, role_id, salary, hire_date, )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, )";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssiis", $first_name, $last_name, $dni, $email, $phone, $role_id, $salary, $hire_date);

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
    $role_id = $_POST['role'];
    $salary = $_POST['salary'];
    $hire_date = $_POST['hire_date'];

    $sql = "UPDATE employee SET first_name=?, last_name=?, dni=?, email=?, phone=?, role_id=?, salary=?, hire_date=?
            WHERE employee_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssiisi", $first_name, $last_name, $dni, $email, $phone, $role_id, $salary, $hire_date, $employee_id);

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

// Obtener roles
$roles = [];
$sql = "SELECT role_id, role FROM roles";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $roles[] = $row;
    }
}

// Obtener empleados
$employees = [];
$sql = "SELECT employee.employee_id, employee.first_name, employee.last_name, employee.dni, employee.email, employee.phone, roles.role, employee.salary, employee.hire_date
        FROM employee
        JOIN roles ON employee.role_id = roles.role_id";
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
            <a href="http://localhost/UCH/BASE-DE-DATOS/Software-Project-Manager/Pages/Payment.php">Payment</a>
        </nav>
    </header>
    <h1>Gestión de Empleados</h1>

    <!-- formulario para agregar empleados -->
    <h2>Agregar Empleado</h2>
    <form action="" method="POST">
        <label for="first_name">Nombre:</label>
        <input type="text" id="first_name" name="first_name" required><br><br>

        <label for="last_name">Apellido:</label>
        <input type="text" id="last_name" name="last_name" required><br><br>

        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="phone">Teléfono:</label>
        <input type="text" id="phone" name="phone" required><br><br>

        <label for="role">Rol:</label>
        <select id="role" name="role" required>
            <?php foreach ($roles as $role) { ?>
                <option value="<?php echo $role['role_id']; ?>"><?php echo $role['role']; ?></option>
            <?php } ?>
        </select><br><br>

        <label for="salary">Salario:</label>
        <input type="number" id="salary" name="salary" step="0.01" required><br><br>

        <label for="hire_date">Fecha de Contratación:</label>
        <input type="date" id="hire_date" name="hire_date" required><br><br>

        <button type="submit" name="add_employee">Agregar Empleado</button>
    </form>

    <!-- formulario para editar empleados -->
    <h2>Editar Empleado</h2>
    <form action="" method="POST">
        <input type="hidden" id="employee_id" name="employee_id">
        <label for="edit_id">ID:</label>
        <input type="text" id="edit_id" name="employee_id" required><br><br>

        <label for="edit_first_name">Nombre:</label>
        <input type="text" id="edit_first_name" name="first_name" required><br><br>

        <label for="edit_last_name">Apellido:</label>
        <input type="text" id="edit_last_name" name="last_name" required><br><br>

        <label for="edit_dni">DNI:</label>
        <input type="text" id="edit_dni" name="dni" required><br><br>

        <label for="edit_email">Email:</label>
        <input type="email" id="edit_email" name="email" required><br><br>

        <label for="edit_phone">Teléfono:</label>
        <input type="text" id="edit_phone" name="phone" required><br><br>

        <label for="edit_role">Rol:</label>
        <select id="edit_role" name="role" required>
            <?php foreach ($roles as $role) { ?>
                <option value="<?php echo $role['role_id']; ?>"><?php echo $role['role']; ?></option>
            <?php } ?>
        </select><br><br>

        <label for="edit_salary">Salario:</label>
        <input type="number" id="edit_salary" name="salary" step="0.01" required><br><br>

        <label for="edit_hire_date">Fecha de Contratación:</label>
        <input type="date" id="edit_hire_date" name="hire_date" required><br><br>

        <button type="submit" name="edit_employee">Actualizar Empleado</button>
    </form>

    <!-- formulario para eliminar empleados -->
    <h2>Eliminar Empleado</h2>
    <form action="" method="POST">
        <label for="delete_employee_id">ID del Empleado:</label>
        <input type="text" id="delete_employee_id" name="employee_id" required><br><br>
        <button type="submit" name="delete_employee">Eliminar Empleado</button>
    </form>

    <h2>Lista de Empleados</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>DNI</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Rol</th>
                <th>Salario</th>
                <th>Fecha de Contratación</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee) { ?>
                <tr>
                    <td><?php echo $employee['employee_id']; ?></td>
                    <td><?php echo $employee['first_name']; ?></td>
                    <td><?php echo $employee['last_name']; ?></td>
                    <td><?php echo $employee['dni']; ?></td>
                    <td><?php echo $employee['email']; ?></td>
                    <td><?php echo $employee['phone']; ?></td>
                    <td><?php echo $employee['role']; ?></td>
                    <td><?php echo $employee['salary']; ?></td>
                    <td><?php echo $employee['hire_date']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <script>
        function editEmployee(employee_id, first_name, last_name, dni, email, phone, role, salary, hire_date) {
            document.getElementById('employee_id').value = employee_id;
            document.getElementById('edit_first_name').value = first_name;
            document.getElementById('edit_last_name').value = last_name;
            document.getElementById('edit_dni').value = dni;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_role').value = role;
            document.getElementById('edit_salary').value = salary;
            document.getElementById('edit_hire_date').value = hire_date;
        }
    </script>
</body>
</html>
