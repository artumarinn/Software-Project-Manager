<?php

include_once '../../Database/connection.php';

// agregar pago
if (isset($_POST['add_payment'])) {
    $date = $_POST['date'];
    $amount = $_POST['amount'];
    $payment_method_id = $_POST['payment_method'];
    $payment_status_id = $_POST['payment_status'];

    $sql = "INSERT INTO payment (date, amount, payment_method_id, payment_status_id)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdii", $date, $amount, $payment_method_id, $payment_status_id);

    if ($stmt->execute()) {
        echo "Pago agregado exitosamente";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// editar pago
if (isset($_POST['edit_payment'])) {
    $payment_id = $_POST['payment_id'];
    $date = $_POST['date'];
    $amount = $_POST['amount'];
    $payment_method_id = $_POST['payment_method'];
    $payment_status_id = $_POST['payment_status'];

    $sql = "UPDATE payment SET date=?, amount=?, payment_method_id=?, payment_status_id=?
            WHERE payment_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdiii", $date, $amount, $payment_method_id, $payment_status_id, $payment_id);

    if ($stmt->execute()) {
        echo "Pago actualizado exitosamente";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// eliminar pago
if (isset($_POST['delete_payment'])) {
    $payment_id = $_POST['payment_id'];

    $sql = "DELETE FROM payment WHERE payment_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payment_id);

    if ($stmt->execute()) {
        echo "Pago eliminado exitosamente";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Obtener métodos de pago
$payment_methods = [];
$sql = "SELECT payment_method_id, description FROM payment_method";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $payment_methods[] = $row;
    }
}

// Obtener estados de pago
$payment_statuses = [];
$sql = "SELECT payment_status_id, payment_status FROM payment_status";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $payment_statuses[] = $row;
    }
}

// Obtener pagos
$payments = [];
$sql = "SELECT payment.payment_id, payment.date, payment.amount, payment_method.description AS payment_method, payment_status.payment_status
        FROM payment
        JOIN payment_method ON payment.payment_method_id = payment_method.payment_method_id
        JOIN payment_status ON payment.payment_status_id = payment_status.payment_status_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $payments[] = $row;
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
    <title>Gestión de Pagos</title>
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
    <h1>Gestión de Pagos</h1>

    <div class="container">
        <div class="form-container">
            <!-- formulario para agregar pagos -->
            <h2>Agregar Pago</h2>
            <form action="" method="POST">
                <label for="date">Fecha:</label>
                <input type="date" id="date" name="date" required><br><br>

                <label for="amount">Monto:</label>
                <input type="number" id="amount" name="amount" step="0.01" required><br><br>

                <label for="payment_method">Método de Pago:</label>
                <select id="payment_method" name="payment_method" required>
                    <?php foreach ($payment_methods as $method) { ?>
                        <option value="<?php echo $method['payment_method_id']; ?>"><?php echo $method['description']; ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="payment_status">Estado de Pago:</label>
                <select id="payment_status" name="payment_status" required>
                    <?php foreach ($payment_statuses as $status) { ?>
                        <option value="<?php echo $status['payment_status_id']; ?>"><?php echo $status['payment_status']; ?></option>
                    <?php } ?>
                </select><br><br>

                <button type="submit" name="add_payment">Agregar Pago</button>
            </form>

            <!-- formulario para editar pagos -->
            <h2>Editar Pago</h2>
            <form action="" method="POST">
                <input type="hidden" id="payment_id" name="payment_id">
                <label for="edit_date">Fecha:</label>
                <input type="date" id="edit_date" name="date" required><br><br>

                <label for="edit_amount">Monto:</label>
                <input type="number" id="edit_amount" name="amount" step="0.01" required><br><br>

                <label for="edit_payment_method">Método de Pago:</label>
                <select id="edit_payment_method" name="payment_method" required>
                    <?php foreach ($payment_methods as $method) { ?>
                        <option value="<?php echo $method['payment_method_id']; ?>"><?php echo $method['description']; ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="edit_payment_status">Estado de Pago:</label>
                <select id="edit_payment_status" name="payment_status" required>
                    <?php foreach ($payment_statuses as $status) { ?>
                        <option value="<?php echo $status['payment_status_id']; ?>"><?php echo $status['payment_status']; ?></option>
                    <?php } ?>
                </select><br><br>

                <button type="submit" name="edit_payment">Actualizar Pago</button>
            </form>

            <!-- formulario para eliminar pagos -->
            <h2>Eliminar Pago</h2>
            <form action="" method="POST">
                <label for="delete_payment_id">ID del Pago:</label>
                <input type="text" id="delete_payment_id" name="payment_id" required><br><br>
                <button type="submit" name="delete_payment">Eliminar Pago</button>
            </form>
        </div>

        <div class="table-container">
            <h2>Lista de Pagos</h2>
            <div class="table-scroll">
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Monto</th>
                        <th>Método de Pago</th>
                        <th>Estado de Pago</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment) { ?>
                        <tr>
                            <td><?php echo $payment['payment_id']; ?></td>
                            <td><?php echo $payment['date']; ?></td>
                            <td><?php echo $payment['amount']; ?></td>
                            <td><?php echo $payment['payment_method']; ?></td>
                            <td><?php echo $payment['payment_status']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>

    <script>
        function editPayment(payment_id, date, amount, payment_method, payment_status) {
            document.getElementById('payment_id').value = payment_id;
            document.getElementById('edit_date').value = date;
            document.getElementById('edit_amount').value = amount;
            document.getElementById('edit_payment_method').value = payment_method;
            document.getElementById('edit_payment_status').value = payment_status;
        }
    </script>
</body>
</html>
