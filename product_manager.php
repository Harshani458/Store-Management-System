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
      background: url('images/dining_room.jpg') no-repeat center center;
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
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .dashboard-title {
      font-size: 28px;
      font-weight: bold;
      color: #4e342e;
      margin-bottom: 20px;
    }

    .welcome {
      font-size: 18px;
      color: #3e2723;
      margin-bottom: 25px;
    }

    .btn-group a {
      display: inline-block;
      margin: 10px;
      padding: 12px 25px;
      background-color: #8d6e63;
      color: white;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s ease;
    }

    .btn-group a:hover {
      background-color: #5d4037;
    }

    .logout {
      margin-top: 20px;
    }

    .logout a {
      background-color: #6d4c41;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
    }

    .logout a:hover {
      background-color: #3e2723;
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
    <a href="manage_product.php">📦 Manage Product</a>
    <a href="manage_supplier.php">🏢 Manage Supplier</a>
    <a href="manage_supplier_payment.php">💳 Supplier Payment</a>
    <a href="manage_leave_request.php">📆 Leave Request</a>
    <a href="logout.php">🚪 Logout</a>
  </div>

  <!-- Main Area -->
  <div class="main-area">
    <div class="dashboard-box">
      <div class="dashboard-title">Product Manager Dashboard</div>
      <div class="welcome">
        Welcome, Product Manager: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
      </div>
      <div class="btn-group">
        
      </div>
      <div class="logout">
        <a href="logout.php">Logout</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>
