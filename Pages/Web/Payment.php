<?php

include_once '../../Database/connection.php';

// agregar pago
if (isset($_POST['add_payment'])) {
    $date = $_POST['date'];
    $amount = $_POST['amount'];
    $payment_method_id = $_POST['payment_method_id'];
    $payment_status_id = $_POST['payment_status_id'];

    $sql = "INSERT INTO payment (date, amount, payment_method_id, payment_status_id)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sddi", $date, $amount, $payment_method_id, $payment_status_id);

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
    $payment_method_id = $_POST['payment_method_id'];
    $payment_status_id = $_POST['payment_status_id'];

    $sql = "UPDATE payment SET date=?, amount=?, payment_method_id=?, payment_status_id=?
            WHERE payment_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sddsi", $date, $amount, $payment_method_id, $payment_status_id, $payment_id);

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

// obtener métodos de pago
$payment_methods = [];
$sql = "SELECT * FROM payment_method";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $payment_methods[] = $row;
    }
}

// obtener estados de pago
$payment_statuses = [];
$sql = "SELECT * FROM payment_status";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $payment_statuses[] = $row;
    }
}

// Obtener pagos
$payments = [];
$sql = "SELECT * FROM payment p
        JOIN payment_method pm ON p.payment_method_id = pm.payment_method_id
        JOIN payment_status ps ON p.payment_status_id = ps.payment_status_id";
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
    <h1>Gestión de Pagos</h1>

    <div class="container">
        <div class="form-container">
            <!-- Formulario para agregar pagos -->
            <h2>Agregar Pago</h2>
            <form action="" method="POST">
                <label for="date">Fecha:</label>
                <input type="date" id="date" name="date" required><br><br>

                <label for="amount">Monto:</label>
                <input type="text" id="amount" name="amount" required><br><br>

                <label for="payment_method_id">Método de Pago:</label>
                <select id="payment_method_id" name="payment_method_id" required>
                    <?php foreach ($payment_methods as $pm) { ?>
                        <option value="<?php echo $pm['payment_method_id']; ?>"><?php echo $pm['description']; ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="payment_status_id">Estado del Pago:</label>
                <select id="payment_status_id" name="payment_status_id" required>
                    <?php foreach ($payment_statuses as $ps) { ?>
                        <option value="<?php echo $ps['payment_status_id']; ?>"><?php echo $ps['payment_status']; ?></option>
                    <?php } ?>
                </select><br><br>

                <button type="submit" name="add_payment">Agregar Pago</button>
            </form>

            <!-- Formulario para editar pagos -->
            <h2>Editar Pago</h2>
            <form id="editForm" action="" method="POST">
                <input type="hidden" id="edit_payment_id" name="payment_id">
                <label for="edit_date">Fecha:</label>
                <input type="date" id="edit_date" name="date" required><br><br>

                <label for="edit_amount">Monto:</label>
                <input type="text" id="edit_amount" name="amount" required><br><br>

                <label for="edit_payment_method_id">Método de Pago:</label>
                <select id="edit_payment_method_id" name="payment_method_id" required>
                    <?php foreach ($payment_methods as $pm) { ?>
                        <option value="<?php echo $pm['payment_method_id']; ?>"><?php echo $pm['description']; ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="edit_payment_status_id">Estado del Pago:</label>
                <select id="edit_payment_status_id" name="payment_status_id" required>
                    <?php foreach ($payment_statuses as $ps) { ?>
                        <option value="<?php echo $ps['payment_status_id']; ?>"><?php echo $ps['payment_status']; ?></option>
                    <?php } ?>
                </select><br><br>

                <button type="submit" name="edit_payment">Actualizar Pago</button>
            </form>

            <!-- Formulario para eliminar pagos -->
            <h2>Eliminar Pago</h2>
            <form action="" method="POST">
                <label for="delete_payment_id">ID del Pago:</label>
                <input type="number" id="delete_payment_id" name="payment_id" required><br><br>

                <button type="submit" name="delete_payment">Eliminar Pago</button>
            </form>
        </div>

        <div class="table-container">
            <h2>Lista de Pagos</h2>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Método de Pago</th>
                            <th>Estado del Pago</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $p) { ?>
                            <tr>
                                <td><?php echo $p['payment_id']; ?></td>
                                <td><?php echo $p['date']; ?></td>
                                <td><?php echo $p['amount']; ?></td>
                                <td><?php echo $p['description']; ?></td>
                                <td><?php echo $p['payment_status']; ?></td>
                                <td>
                                    <button onclick="editPayment(
                                        '<?php echo $p['payment_id']; ?>',
                                        '<?php echo $p['date']; ?>',
                                        '<?php echo $p['amount']; ?>',
                                        '<?php echo $p['payment_method_id']; ?>',
                                        '<?php echo $p['payment_status_id']; ?>'
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
        function editPayment(payment_id, date, amount, payment_method_id, payment_status_id) {
            document.getElementById('edit_payment_id').value = payment_id;
            document.getElementById('edit_date').value = date;
            document.getElementById('edit_amount').value = amount;
            document.getElementById('edit_payment_method_id').value = payment_method_id;
            document.getElementById('edit_payment_status_id').value = payment_status_id;
        }
    </script>
</body>
</html>
