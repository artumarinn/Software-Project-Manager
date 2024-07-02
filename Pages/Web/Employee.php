<?php

include_once '../../Database/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST')

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
    <form>
        <h2>Edit Employee</h2>
        <label>First Name:</label><br>
        <input type="text" required><br><br>
        <label>Last Name:</label><br>
        <input type="text" required><br><br>
        <label>DNI:</label><br>
        <input type="text" required><br><br>
        <label>Email:</label><br>
        <input type="text" required><br><br>
        <label>Phone:</label><br>
        <input type="text" required><br><br>
        <label>Role:</label><br>
        <input type="text" required><br><br>
        <label>Salary:</label><br>
        <input type="text" required><br><br>
        <label>Hire Date:</label><br>
        <input type="date" required><br><br>
        <button>Add Employee</button><br><br>

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