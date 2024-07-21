<?php

include_once '../../Database/connection.php';

// agregar cliente
if (isset($_POST['add_customer'])) {
    $full_name = $_POST['full_name'];
    $cuil = $_POST['cuil'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO customer (full_name, cuil, address, email, phone)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $full_name, $cuil, $address, $email, $phone);

    if ($stmt->execute()) {
        echo "Cliente agregado exitosamente";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// editar cliente
if (isset($_POST['edit_customer'])) {
    $customer_id = $_POST['customer_id'];
    $full_name = $_POST['full_name'];
    $cuil = $_POST['cuil'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "UPDATE customer SET full_name=?, cuil=?, address=?, email=?, phone=?
            WHERE customer_id=? ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $full_name, $cuil, $address, $email, $phone, $customer_id);

    if ($stmt->execute()) {
        echo "Cliente actualizado exitosamente";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// eliminar cliente
if (isset($_POST['delete_customer'])) {
    $customer_id = $_POST['customer_id'];

    $sql = "DELETE FROM customer WHERE customer_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);

    if ($stmt->execute()) {
        echo "Cliente eliminado exitosamente";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Obtener clientes
$customers = [];
$sql = "SELECT * FROM customer";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $customers[] = $row;
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
    <title>Gestión de Clientes</title>
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
            <a href="http://localhost/UCH/BASE-DE-DATOS/Software-Project-Manager/Pages/Customers.php">Customers</a>
            <a href="http://localhost/UCH/BASE-DE-DATOS/Software-Project-Manager/Pages/Payment.php">Payment</a>
        </nav>
    </header>
    <h1>Gestión de Clientes</h1>

    <div class="container">
        <div class="form-container">
            <!-- Formulario para agregar clientes -->
            <h2>Agregar Cliente</h2>
            <form action="" method="POST">
                <label for="full_name">Nombre Completo:</label>
                <input type="text" id="full_name" name="full_name" required><br><br>

                <label for="cuil">CUIL:</label>
                <input type="text" id="cuil" name="cuil" required><br><br>

                <label for="address">Dirección:</label>
                <input type="text" id="address" name="address" required><br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br><br>

                <label for="phone">Teléfono:</label>
                <input type="text" id="phone" name="phone" required><br><br>

                <button type="submit" name="add_customer">Agregar Cliente</button>
            </form>

            <!-- Formulario para editar clientes -->
            <h2>Editar Cliente</h2>
            <form action="" method="POST">
                <input type="hidden" id="customer_id" name="customer_id">
                <label for="edit_id">ID:</label>
                <input type="text" id="edit_id" name="customer_id" required><br><br>

                <label for="edit_full_name">Nombre Completo:</label>
                <input type="text" id="edit_full_name" name="full_name" required><br><br>

                <label for="edit_cuil">CUIL:</label>
                <input type="text" id="edit_cuil" name="cuil" required><br><br>

                <label for="edit_address">Dirección:</label>
                <input type="text" id="edit_address" name="address" required><br><br>

                <label for="edit_email">Email:</label>
                <input type="email" id="edit_email" name="email" required><br><br>

                <label for="edit_phone">Teléfono:</label>
                <input type="text" id="edit_phone" name="phone" required><br><br>

                <button type="submit" name="edit_customer">Actualizar Cliente</button>
            </form>

            <!-- Formulario para eliminar clientes -->
            <h2>Eliminar Cliente</h2>
            <form action="" method="POST">
                <label for="delete_customer_id">ID del Cliente:</label>
                <input type="text" id="delete_customer_id" name="customer_id" required><br><br>
                <button type="submit" name="delete_customer">Eliminar Cliente</button>
            </form>
        </div>

        <div>
            <!-- Lista de clientes -->
            <h2>Lista de Clientes</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>CUIL</th>
                        <th>Dirección</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer) { ?>
                        <tr>
                            <td><?php echo $customer['customer_id']; ?></td>
                            <td><?php echo $customer['full_name']; ?></td>
                            <td><?php echo $customer['cuil']; ?></td>
                            <td><?php echo $customer['address']; ?></td>
                            <td><?php echo $customer['email']; ?></td>
                            <td><?php echo $customer['phone']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Llenar formulario de edición con datos del cliente seleccionado
        function editCustomer(customer_id, full_name, cuil, address, email, phone) {
            document.getElementById('customer_id').value = customer_id;
            document.getElementById('edit_full_name').value = full_name;
            document.getElementById('edit_cuil').value = cuil;
            document.getElementById('edit_address').value = address;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_phone').value = phone;
        }
    </script>
</body>
</html>
