<?php

include_once '../../Database/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // datos del formulario
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $dni = $_POST['dni'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $salary = $_POST['salary'];
    $hireDate = $_POST['hireDate'];

    // consulta para insertar los datos
    $sql = "INSERT INTO employee (first_name, last_name, dni, email, phone, role_id, salary, hire_date) 
    VALUES ('$firstName', '$lastName', '$dni', '$email', '$phone', '$role', '$salary', '$hireDate')";

    // TERMINAR ESTO: NO SE AGREGAN LOS DATOS A LA BD

    // ejecutar consulta
    if ($conn->query($sql) === TRUE) {
        echo 'Successfully added';
    } else {
        echo 'Error: ' . $conn->error;
    }

    $conn->close();
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
        <h2>Add Employee</h2>
        <label>First Name:</label><br>
        <input type="text" name="first_name" required><br><br>
        <label>Last Name:</label><br>
        <input type="text" name="last_name" required><br><br>
        <label>DNI:</label><br>
        <input type="text" name="dni" required><br><br>
        <label>Email:</label><br>
        <input type="text" name="email" required><br><br>
        <label>Phone:</label><br>
        <input type="text" name="phone" required><br><br>
        <label>Role:</label><br>
        <input type="text" name="role" required><br><br>
        <label>Salary:</label><br>
        <input type="text" name="salary" required><br><br>
        <label>Hire Date:</label><br>
        <input type="date" name="hireDate" required><br><br>
        <button type="submit">Add Employee</button><br><br>

        <h2>Existing Employees</h2>
        <table border="1">
            <th>First Name</th>
            <th>Last Name</th>
            <th>DNI</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Salary</th>
            <th>Hire Date</th>
        </table>

    </form>
</body>
</html>
