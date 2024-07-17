<?php

include_once '../../Database/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // eliminación de clientes
    if (isset($_POST['delete_customer_id'])) {
        $customerId = $_POST['delete_customer_id'];

        $sql = "DELETE FROM customer WHERE customer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $customerId);

        if ($stmt->execute() === TRUE) {
            echo 'Cliente eliminado exitosamente';
            header("Refresh: 2; URL=Customer.php");
            exit();
        } else {
            echo 'Error al eliminar el cliente: ' . $conn->error;
        }

        $stmt->close();
    }
    // edición de clientes
    elseif (isset($_POST['edit_customer_id'])) {
        $customerId = $_POST['edit_customer_id'];
        $fullName = $_POST['full_name'];
        $cuil = $_POST['cuil'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $sql = "UPDATE customer SET full_name = ?, cuil = ?, address = ?, email = ?, phone = ? WHERE customer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $fullName, $cuil, $address, $email, $phone, $customerId);

        if ($stmt->execute() === TRUE) {
            echo 'Cliente actualizado exitosamente';
            header("Refresh: 2; URL=Customer.php");
            exit();
        } else {
            echo 'Error al actualizar el cliente: ' . $conn->error;
        }

        $stmt->close();
    } else {
        // dición de clientes
        $fullName = $_POST['full_name'];
        $cuil = $_POST['cuil'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $sql = "INSERT INTO customer (full_name, cuil, address, email, phone) 
                VALUES ('$fullName', '$cuil', '$address', '$email', '$phone')";

        if ($conn->query($sql) === TRUE) {
            echo 'Cliente agregado exitosamente';
            header("Refresh: 2; URL=Customer.php");
            exit();
        } else {
            if ($conn->errno == 1062) {
                echo 'Error: Ya existe un cliente con el mismo CUIL o correo electrónico.';
            } else {
                echo 'Error: ' . $conn->error;
            }
        }
    }
}

// Consulta para obtener clientes
$sqlCustomers = "SELECT * FROM customer";
$resultCustomers = $conn->query($sqlCustomers);

// Obtener datos del cliente a editar
$customerToEdit = null;
if (isset($_GET['edit_customer_id'])) {
    $customerId = $_GET['edit_customer_id'];
    $sql = "SELECT * FROM customer WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $customerToEdit = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer</title>
</head>
<body>
    <h1>Manage Customers</h1>
    <form method="POST" action="">
        <h2><?php echo $customerToEdit ? 'Edit Customer' : 'Add Customer'; ?></h2>
        <input type="hidden" name="edit_customer_id" value="<?php echo $customerToEdit['customer_id'] ?? ''; ?>">
        <label>Full Name:</label><br>
        <input type="text" name="full_name" value="<?php echo $customerToEdit['full_name'] ?? ''; ?>" required><br><br>
        <label>CUIL:</label><br>
        <input type="text" name="cuil" value="<?php echo $customerToEdit['cuil'] ?? ''; ?>" required><br><br>
        <label>Address:</label><br>
        <input type="text" name="address" value="<?php echo $customerToEdit['address'] ?? ''; ?>" required><br><br>
        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo $customerToEdit['email'] ?? ''; ?>" required><br><br>
        <label>Phone:</label><br>
        <input type="text" name="phone" value="<?php echo $customerToEdit['phone'] ?? ''; ?>" required><br><br>
        <button type="submit"><?php echo $customerToEdit ? 'Update Customer' : 'Add Customer'; ?></button><br><br>
    </form>

    <h2>Existing Customers</h2>
    <table border="1">
        <tr>
            <th>Full Name</th>
            <th>CUIL</th>
            <th>Address</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Action</th>
        </tr>
        <?php
        if ($resultCustomers->num_rows > 0) {
            while($row = $resultCustomers->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["full_name"] . "</td>";
                echo "<td>" . $row["cuil"] . "</td>";
                echo "<td>" . $row["address"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["phone"] . "</td>";
                echo "<td>
                        <form method='POST' action='' style='display:inline;'>
                            <input type='hidden' name='delete_customer_id' value='{$row['customer_id']}'>
                            <button type='submit'>Eliminar</button>
                        </form>
                        <form method='GET' action='' style='display:inline;'>
                            <input type='hidden' name='edit_customer_id' value='{$row['customer_id']}'>
                            <button type='submit'>Editar</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>0 resultados</td></tr>";
        }
        ?>
    </table>

    <?php $conn->close();?>
</body>
</html>
