<?php

include_once '../Database/connection.php';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset( $_POST['addProject'])){

    $name = $_POST['name'];
    $desciption = $_POST['description'];
    $start_Date = $_POST['start_Date'];
    $end_date = $_POST['end-date'];
    $actual_end_date = $_POST['actual_end_date'];
    $customer_id = $_POST['customer_id'];

    // terminar esto 

    try {

        $sql= "INSERT INTO project (name, description, start_date, end_date, actual_end_date, customer_id)
                VALUES ('$name', '$description', '$start_date', '$end_date', '$actual_end_date', '$customer_id')";

        if ($conn->query($sql) === TRUE) {
            $projectMessage = "Project added correctly.";
        } else {
            throw new Exception($conn->error, $conn->errno);
        }

    }catch(Exception $e) {
        $projectMessage = "Error adding project " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects</title>
</head>
<body>
    <h1>Project Manager</h1>
    <h2>Add Project</h2>
    <form>
        <label>Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>
        <label>Description:</label><br>
        <input type="text" id="description" name="description" required><br><br>
        <label>Start Date:</label><br>
        <input type="date" id="start_date" name="start_date" required><br><br>
        <label>End Date:</label><br>
        <input type="date" id="end_date" name="end_date" required><br><br>
        <label>Actual end date:</label><br>
        <input type="date" id="actual_end_date" name="actual_end_date" required><br><br>
        <label>Customer ID:</label><br>
        <input type="number" id="customer_id" name="customer_id" required><br><br>
        <button type="submit" name="addProject">Add project</button>
    </form><br><br>
    <h2>Active projects</h2>
    <table border="1">
        <th>Name</th>
        <th>Description</th>
        <th>Start date</th>
        <th>End Date</th>
        <th>Actual end date</th>
        <th>Customer</th>
    </table>
</body>
</html>
