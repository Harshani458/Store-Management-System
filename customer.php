<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'cashier') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            display: flex;
            width: 90%;
            max-width: 1200px;
            height: 90%;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
            border-radius: 12px;
            overflow: hidden;
        }

        .sidebar {
            width: 250px;
            background-color: #4e342e;
            color: white;
            padding-top: 30px;
            flex-shrink: 0;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .sidebar a {
            display: block;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #3e2723;
        }

        .main-area {
            flex: 1;
            background: url('images/cashier_background.jpg') no-repeat center center;
            background-size: cover;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .dashboard-box {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 40px 50px;
            border-radius: 12px;
            text-align: center;
            max-width: 600px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .dashboard-title {
            font-size: 28px;
            font-weight: bold;
            color: #4e342e;
            margin-bottom: 30px;
        }

        .nav-buttons a {
            display: block;
            margin: 12px 0;
            padding: 14px 20px;
            text-decoration: none;
            color: white;
            background-color: #6d4c41;
            border-radius: 8px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .nav-buttons a:hover {
            background-color: #4e342e;
        }

        .back-button {
            margin-top: 30px;
        }

        .back-button a {
            text-decoration: none;
            font-weight: bold;
            color: white;
            background-color: #8d6e63;
            padding: 10px 20px;
            border-radius: 6px;
            transition: background 0.3s ease;
        }

        .back-button a:hover {
            background-color: #4e342e;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                height: auto;
            }

            .sidebar {
                width: 100%;
                height: auto;
            }

            .main-area {
                width: 100%;
                padding: 20px;
            }

            .dashboard-box {
                padding: 30px 40px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Kalana Furniture</h2>
        <a href="customer.php" class="active">🧑‍🤝‍🧑 Customers</a>
        <a href="reports.php">📊 Reports</a>
        <a href="add_leave_request.php">📅 Leave Request</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <!-- Main Area -->
    <div class="main-area">
        <div class="dashboard-box">
            <div class="dashboard-title">Customer Management</div>
            <div class="nav-buttons">
                <a href="includes/manage_customer_invoice.php">🧾 Manage Customer Invoice</a>
                <a href="includes/manage_return.php">🔁 Manage Customer Returns</a>
                <a href="includes/view_customer_return.php">📦 view Customer Returns</a>
                <a href="includes/manage_order.php">📦 Manage Customer Orders</a>

            </div>
            <div class="back-button">
                <a href="cashier_dashboard.php">← Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
