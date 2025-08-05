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
    <title>View Customer Returns</title>
    <style>
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
            max-width: 1100px;
            width: 100%;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
        }

        h2 {
            color: #4e342e;
            text-align: center;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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

        .back-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            text-decoration: none;
            font-weight: 600;
            color: #4e342e;
        }

        .back-link:hover {
            color: #3e2723;
        }
    </style>
</head>
<body>

<div class="panel">
    <h2>Customer Return Records</h2>

    <table>
        <tr>
            <th>Return ID</th>
            <th>Invoice ID</th>
            <th>Product Name</th>
            <th>Returned Quantity</th>
            <th>Description</th>
            <th>Status</th>
            <th>Return Date</th>
        </tr>

        <?php
        $sql = "SELECT r.return_id, r.invoice_id, r.item_id, r.return_quantity, r.description, r.status, r.return_date, 
                       p.name AS product_name
                FROM return_item r
                JOIN invoice_item i ON r.item_id = i.item_id
                JOIN product p ON i.product_id = p.product_id
                ORDER BY r.return_date DESC";

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['return_id']}</td>
                        <td>{$row['invoice_id']}</td>
                        <td>{$row['product_name']}</td>
                        <td>{$row['return_quantity']}</td>
                        <td>{$row['description']}</td>
                        <td>{$row['status']}</td>
                        <td>{$row['return_date']}</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No return records found.</td></tr>";
        }
        ?>
    </table>

    <a class="back-link" href="http://localhost/company_webapp/customer.php">← Back to Customer Panel</a>
</div>

</body>
</html>
