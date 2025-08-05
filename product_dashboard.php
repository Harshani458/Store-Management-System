<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'product manager') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Manager Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f6f1eb;
            margin: 0;
            display: flex;
            justify-content: center;
            padding: 40px;
        }

        .dashboard-container {
            width: 90%;
            max-width: 900px;
            background-color: #fffaf5;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
        }

        header {
            background-color: #5d4037;
            color: white;
            padding: 20px;
            text-align: center;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .welcome {
            text-align: center;
            padding: 15px;
            font-size: 17px;
            background-color: #e8dcd2;
            color: #4e342e;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 30px;
        }

        .card a {
            display: block;
            background-color: #cd853f;
            color: white;
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, transform 0.2s;
        }

        .card a:hover {
            background-color: #b46c2b;
            transform: scale(1.03);
        }

        .logout {
            text-align: center;
            padding: 20px;
        }

        .logout a {
            background-color: #6d4c41;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
        }

        .logout a:hover {
            background-color: #4e342e;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <header>
        <h2>Product Manager Dashboard</h2>
    </header>

    <div class="welcome">
        Welcome, Product Manager: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
    </div>

    <div class="dashboard">
        <div class="card"><a href="manage_product_catalog.php">Manage Product Catalog</a></div>
        <div class="card"><a href="manage_supplier_profile.php">Manage Supplier Profile</a></div>
        <div class="card"><a href="manage_order_list.php">Manage Order List</a></div>
        <div class="card"><a href="manage_supplier_payment.php">Manage Supplier Payment</a></div>
        <div class="card"><a href="manage_leave_request.php">Leave Request</a></div> <!-- ✅ Newly Added -->
    </div>

    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>
</div>

</body>
</html>
