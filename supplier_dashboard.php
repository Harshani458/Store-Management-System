<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Supplier Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
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
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
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
            background: url('images/background.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .dashboard-box {
            background-color: rgba(255, 255, 255, 0.85);
            padding: 40px 60px;
            border-radius: 12px;
            text-align: center;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .dashboard-title {
            font-size: 28px;
            font-weight: bold;
            color: #4e342e;
            margin-bottom: 30px;
        }

        .button {
            display: block;
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            background-color: #8d6e63;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .button:hover {
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
        <a href="customer_side.php">👥 Customer Side</a>
        <a href="employees.php">💬 Employees</a>
        <a href="business_dashboard.php">📊 Business Side</a>
        <a href="supplier_dashboard.php" class="active">📦 Supplier Side</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <!-- Main Area -->
    <div class="main-area">
        <div class="dashboard-box">
            <div class="dashboard-title">Supplier Dashboard</div>
            <a href="supplier_profile.php" class="button">📄 View Supplier Profile</a>
            <a href="supplier_orders.php" class="button">📋 View Order List</a>
            <a href="supplier_payments.php" class="button">💰 View Supplier Payments</a>
        </div>
    </div>
</div>

</body>
</html>
