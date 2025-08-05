<?php
include("db_connect.php");
$result = $conn->query("SELECT * FROM customer_invoice ORDER BY invoice_id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Invoices</title>
    <style>
        body {
            background-color: #f8f5f2;
            font-family: Arial;
            padding: 20px;
        }
        h2 {
            color: #5a3e2b;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background: #fff;
        }
        th, td {
            border: 1px solid #c9a17a;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #d6b295;
            color: #3f2d23;
        }
    </style>
</head>
<body>
    <h2>Invoices</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Customer Name</th>
            <th>Phone</th>
            <th>Cashier</th>
            <th>Date</th>
            <th>Discount (%)</th>
            <th>Final Amount</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['invoice_id']; ?></td>
            <td><?= $row['customer_name']; ?></td>
            <td><?= $row['phone']; ?></td>
            <td><?= $row['cashier_username']; ?></td>
            <td><?= $row['invoice_date']; ?></td>
            <td><?= $row['discount']; ?>%</td>
            <td><?= number_format($row['final_amount'], 2); ?></td>
            <td><a href="generate_invoice_pdf.php?invoice_id=<?= $row['invoice_id']; ?>" target="_blank">Print</a></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
