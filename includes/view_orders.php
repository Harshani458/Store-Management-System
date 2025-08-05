<?php
include 'db_connect.php';

$sql = "SELECT * FROM custom_orders ORDER BY order_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>View Custom Orders - Kalana Furniture</title>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    body {
        margin: 0; padding: 40px 30px;
        background-color: #5D4037; /* medium dark brown */
        font-family: 'Poppins', sans-serif;
        color: #fff;
        min-height: 100vh;
    }

    h2 {
        text-align: center;
        font-weight: 600;
        font-size: 30px;
        margin-bottom: 40px;
        color: #fff;
        letter-spacing: 1.1px;
    }

    table {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto 50px;
        border-collapse: separate;
        border-spacing: 0 10px;
        background-color: #D7CCC8; /* light beige */
        border-radius: 14px;
        box-shadow: 0 5px 15px rgba(109, 76, 65, 0.3);
        overflow: hidden;
        color: #3E2723; /* dark brown text on light bg */
    }

    thead tr {
        background-color: #A1887F; /* light brown */
        color: #3E2723;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 1.1px;
    }

    th, td {
        padding: 14px 20px;
        text-align: left;
        vertical-align: middle;
    }

    tbody tr {
        background-color: #F5F5F5; /* almost white */
        transition: background-color 0.3s ease;
        box-shadow: 0 1px 6px rgba(109, 76, 65, 0.15);
        border-radius: 8px;
    }

    tbody tr:hover {
        background-color: #A1887F; /* light brown hover */
        color: #3E2723;
        cursor: default;
        box-shadow: 0 6px 15px rgba(161, 136, 127, 0.5);
    }

    tbody tr td:first-child {
        font-weight: 700;
        color: #6D4C41; /* medium brown */
    }

    .no-data {
        text-align: center;
        font-style: italic;
        font-size: 1.3em;
        margin-top: 80px;
        color: #D7CCC8;
    }

    .back-btn {
        display: inline-block;
        margin: 0 auto;
        padding: 14px 32px;
        background-color: #6D4C41; /* medium brown */
        color: #fff;
        font-weight: 600;
        font-size: 17px;
        border-radius: 14px;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(109, 76, 65, 0.7);
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        text-align: center;
        display: block;
        width: 180px;
    }

    .back-btn:hover {
        background-color: #4E342E; /* darker medium brown */
        box-shadow: 0 6px 20px rgba(78, 52, 46, 0.8);
    }

    /* Responsive Table */
    @media (max-width: 900px) {
        table, thead, tbody, th, td, tr {
            display: block;
        }

        thead tr {
            display: none;
        }

        tbody tr {
            margin-bottom: 25px;
            background-color: #D7CCC8;
            border-radius: 14px;
            box-shadow: none;
            padding: 15px 20px;
        }

        tbody tr:hover {
            background-color: #D7CCC8;
            box-shadow: none;
            color: #3E2723;
        }

        tbody tr td {
            padding-left: 50%;
            position: relative;
            border-bottom: 1px solid #A1887F;
            text-align: left;
            color: #6D4C41;
            font-weight: 600;
        }

        tbody tr td:last-child {
            border-bottom: 0;
        }

        tbody tr td::before {
            position: absolute;
            left: 20px;
            top: 14px;
            white-space: nowrap;
            font-weight: 700;
            color: #A1887F;
            content: attr(data-label);
        }
    }
</style>
</head>
<body>

<h2>All Custom Product Orders</h2>

<?php if ($result && $result->num_rows > 0): ?>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Customer Name</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Product Type</th>
            <th>Description</th>
            <th>Order Date</th>
            <th>Delivery Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $count = 1;
        while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td data-label="#"><?php echo $count++; ?></td>
                <td data-label="Customer Name"><?php echo htmlspecialchars($row['customer_name']); ?></td>
                <td data-label="Phone"><?php echo htmlspecialchars($row['customer_phone']); ?></td>
                <td data-label="Address"><?php echo htmlspecialchars($row['address']); ?></td>
                <td data-label="Product Type"><?php echo htmlspecialchars($row['product_type']); ?></td>
                <td data-label="Description"><?php echo htmlspecialchars($row['product_description']); ?></td>
                <td data-label="Order Date"><?php echo htmlspecialchars($row['order_date']); ?></td>
                <td data-label="Delivery Date"><?php echo htmlspecialchars($row['delivery_date']); ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php else: ?>
    <p class="no-data">No orders found.</p>
<?php endif; ?>

<div style="text-align:center;">
    <a href="manage_order.php" class="back-btn">Place New Order</a>
</div>

</body>
</html>
