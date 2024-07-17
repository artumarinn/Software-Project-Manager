<?php

include_once '../../Database/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // eliminación de empleados
    if (isset($_POST['delete_employee_id'])) {
        $employeeId = $_POST['delete_employee_id'];

        $sql = "DELETE FROM employee WHERE employee_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $employeeId);

        if ($stmt->execute() === TRUE) {
            echo 'Empleado eliminado exitosamente';
            header("Refresh: 2; URL=Employee.php");
            exit();
        } else {
            echo 'Error al eliminar el empleado: ' . $conn->error;
        }

        $stmt->close();
    }

    // edición de empleados
    elseif (isset($_POST['edit_employee_id'])) {
        $employeeId = $_POST['edit_employee_id'];
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $dni = $_POST['dni'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $role = $_POST['role'];
        $salary = $_POST['salary'];
        $hireDate = $_POST['hireDate'];

        $sql = "UPDATE employee SET first_name = ?, last_name = ?, dni = ?, email = ?, phone = ?, role_id = ?, salary = ?, hire_date = ? WHERE employee_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssi", $firstName, $lastName, $dni, $email, $phone, $role, $salary, $hireDate, $employeeId);

        if ($stmt->execute() === TRUE) {
            echo 'Empleado actualizado exitosamente';
            header("Refresh: 2; URL=Employee.php");
            exit();
        } else {
            echo 'Error al actualizar el empleado: ' . $conn->error;
        }

        $stmt->close();
        
    } else {
        // adición de empleados
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $dni = $_POST['dni'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $role = $_POST['role'];
        $salary = $_POST['salary'];
        $hireDate = $_POST['hireDate'];

        $sql = "INSERT INTO employee (first_name, last_name, dni, email, phone, role_id, salary, hire_date) 
                VALUES ('$firstName', '$lastName', '$dni', '$email', '$phone', '$role', '$salary', '$hireDate')";

        if ($conn->query($sql) === TRUE) {
            echo 'Empleado agregado exitosamente';
            header("Refresh: 2; URL=Employee.php");
            exit();
        } else {
            if ($conn->errno == 1062) {
                echo 'Error: Ya existe un empleado con el mismo DNI o correo electrónico.';
            } else {
                echo 'Error: ' . $conn->error;
            }
        }
    }
}

// consulta para obtener roles
$sqlRoles = "SELECT role_id, description FROM roles";
$resultRoles = $conn->query($sqlRoles);

// consulta para obtener empleados con el rol
$sqlEmployees = "
    SELECT employee.employee_id, employee.first_name, employee.last_name, employee.dni, employee.email, employee.phone, roles.description AS role, employee.salary, employee.hire_date 
    FROM employee  
    JOIN roles ON employee.role_id = roles.role_id
";
$resultEmployees = $conn->query($sqlEmployees);

// Obtener datos del empleado a editar
$employeeToEdit = null;
if (isset($_GET['edit_employee_id'])) {
    $employeeId = $_GET['edit_employee_id'];
    $sql = "SELECT * FROM employee WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employeeId);
    $stmt->execute();
    $result = $stmt->get_result();
    $employeeToEdit = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee</title>
</head>
<body>
    <h1>Manage Employees</h1>
    <form method="POST" action="">
        <h2><?php echo $employeeToEdit ? 'Edit Employee' : 'Add Employee'; ?></h2>
        <input type="hidden" name="edit_employee_id" value="<?php echo $employeeToEdit['employee_id'] ?? ''; ?>">
        <label>First Name:</label><br>
        <input type="text" name="first_name" value="<?php echo $employeeToEdit['first_name'] ?? ''; ?>" required><br><br>
        <label>Last Name:</label><br>
        <input type="text" name="last_name" value="<?php echo $employeeToEdit['last_name'] ?? ''; ?>" required><br><br>
        <label>DNI:</label><br>
        <input type="text" name="dni" value="<?php echo $employeeToEdit['dni'] ?? ''; ?>" required><br><br>
        <label>Email:</label><br>
        <input type="text" name="email" value="<?php echo $employeeToEdit['email'] ?? ''; ?>" required><br><br>
        <label>Phone:</label><br>
        <input type="text" name="phone" value="<?php echo $employeeToEdit['phone'] ?? ''; ?>" required><br><br>
        <label>Role:</label><br>
        <select id="role" name="role" required>
            <?php
            if ($resultRoles->num_rows > 0) {
                while ($row = $resultRoles->fetch_assoc()) {
                    $selected = $employeeToEdit && $employeeToEdit['role_id'] == $row['role_id'] ? 'selected' : '';
                    echo "<option value=\"{$row['role_id']}\" $selected>{$row['description']}</option>";
                }
            } else {
                echo "<option value=\"\">No se encontraron roles</option>";
            }
            ?>
        </select><br><br>
        <label>Salary:</label><br>
        <input type="text" name="salary" value="<?php echo $employeeToEdit['salary'] ?? ''; ?>" required><br><br>
        <label>Hire Date:</label><br>
        <input type="date" name="hireDate" value="<?php echo $employeeToEdit['hire_date'] ?? ''; ?>" required><br><br>
        <button type="submit"><?php echo $employeeToEdit ? 'Update Employee' : 'Add Employee'; ?></button><br><br>
    </form>

    <h2>Existing Employees</h2>
    <table border="1">
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>DNI</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Salary</th>
            <th>Hire Date</th>
            <th>Action</th>
        </tr>
        <?php
        if ($resultEmployees->num_rows > 0) {
            while($row = $resultEmployees->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["first_name"] . "</td>";
                echo "<td>" . $row["last_name"] . "</td>";
                echo "<td>" . $row["dni"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["phone"] . "</td>";
                echo "<td>" . $row["role_description"] . "</td>"; 
                echo "<td>" . $row["salary"] . "</td>";
                echo "<td>" . $row["hire_date"] . "</td>";
                echo "<td>
                        <form method='POST' action='' style='display:inline;'>
                            <input type='hidden' name='delete_employee_id' value='{$row['employee_id']}'>
                            <button type='submit'>Eliminar</button>
                        </form>
                        <form method='GET' action='' style='display:inline;'>
                            <input type='hidden' name='edit_employee_id' value='{$row['employee_id']}'>
                            <button type='submit'>Editar</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='9'>0 resultados</td></tr>";
        }
        ?>
    </table>

    <?php $conn->close();?>
</body>
</html>
