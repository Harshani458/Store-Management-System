<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'cashier') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Customer Returns</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url('../assets/bg-light.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            min-height: 100vh;
        }

        .panel {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 16px;
            max-width: 1000px;
            width: 100%;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
        }

        h2 {
            color: #4e342e;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            margin-bottom: 30px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
        }

        label {
            font-weight: 600;
            color: #4e342e;
            align-self: center;
        }

        select {
            padding: 10px 14px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
            width: 200px;
        }

        button {
            background-color: #5d4037;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #3e2723;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #6d4c41;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #fbe9e7;
        }

        .return-button button {
            padding: 8px 14px;
            font-size: 15px;
            border-radius: 6px;
            background-color: #795548;
        }

        .return-button button:hover {
            background-color: #4e342e;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #4e342e;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            color: #3e2723;
        }
    </style>
</head>
<body>

<div class="panel">
    <h2>Manage Customer Returns</h2>

    <form method="GET">
        <label for="invoice_id">Select Invoice ID:</label>
        <select name="invoice_id" id="invoice_id" required>
            <option value="">-- Select Invoice --</option>
            <?php
            $invoiceQuery = $conn->query("SELECT invoice_id FROM customer_invoice");
            while ($row = $invoiceQuery->fetch_assoc()) {
                $selected = (isset($_GET['invoice_id']) && $_GET['invoice_id'] == $row['invoice_id']) ? 'selected' : '';
                echo "<option value='{$row['invoice_id']}' $selected>{$row['invoice_id']}</option>";
            }
            ?>
        </select>
        <button type="submit">View Details</button>
    </form>

    <?php
    if (isset($_GET['invoice_id'])) {
        $invoice_id = intval($_GET['invoice_id']);
        $sql = "SELECT ii.item_id, ii.product_id, p.name AS product_name, ii.quantity, ii.unit_price, ii.total_price
                FROM invoice_item ii
                JOIN product p ON ii.product_id = p.product_id
                WHERE ii.invoice_id = $invoice_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<h3>Invoice #{$invoice_id} Details</h3>";
            echo "<table>
                    <tr>
                        <th>Item ID</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                        <th>Action</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['item_id']}</td>
                        <td>{$row['product_name']}</td>
                        <td>{$row['quantity']}</td>
                        <td>Rs. {$row['unit_price']}</td>
                        <td>Rs. {$row['total_price']}</td>
                        <td class='return-button'>
                            <form action='return_process.php' method='GET'>
                                <input type='hidden' name='item_id' value='{$row['item_id']}'>
                                <input type='hidden' name='invoice_id' value='{$invoice_id}'>
                                <button type='submit'>Return</button>
                            </form>
                        </td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No items found for invoice #{$invoice_id}.</p>";
        }
    }
    ?>
    <a class="back-link" href="http://localhost/company_webapp/customer.php">← Back to Customer Panel</a>
</div>

</body>
</html>
