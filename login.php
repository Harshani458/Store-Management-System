<?php
session_start();
include("includes/db_connect.php");

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM employee WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = strtolower($user['role']);

        switch ($_SESSION['role']) {
            case 'admin':
                header("Location: admin_dashboard.php");
                break;
            case 'cashier':
                header("Location: cashier_dashboard.php");
                break;
            case 'hr manager':
                header("Location: hr_dashboard.php");
                break;
            case 'product manager':
                header("Location: product_manager.php");
                break;
            case 'supplier':
                header("Location: supplier_dashboard.php");
                break;
            default:
                $error = "Unknown role. Contact administrator.";
        }
        exit();
    } else {
        $error = "❌ Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Kalana Furniture</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: rgba(252, 250, 249, 1); /* Dark brown */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background: #4e342e;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 20px;
            color: #ffffff;
        }

        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            background-color: #ffffff; /* White input background */
            color: #000000; /* Black text */
            border-radius: 8px;
            font-size: 16px;
        }

        .login-box input::placeholder {
            color: #777;
        }

        .login-box button {
            background-color: #5d4037; /* Dark brown button */
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-box button:hover {
            background-color: #3e2723;
        }

        .error {
            color: #ff8a80;
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login to Kalana Furniture</h2>
    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
</div>

</body>
</html>
