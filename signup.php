<?php
require 'config.php'; // include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form inputs
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // secure password
    $role = $_POST['role'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO employee (name, username, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $username, $password, $role);

    // Execute
    if ($stmt->execute()) {
        // Redirect to login page after successful signup
        header("Location: login.php");
        exit();
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>

<!-- HTML signup form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Signup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f5f5f5;
        }

        h2 {
            color: #4B2E2E;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border: 2px solid #B22222;
            border-radius: 10px;
            max-width: 400px;
        }

        label {
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 6px;
            margin-bottom: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #B22222;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #8B1A1A;
        }
    </style>
</head>
<body>
    <h2>Employee Signup</h2>
    <form action="signup.php" method="POST">
        <label>Name:</label><br>
        <input type="text" name="name" required><br>

        <label>Username:</label><br>
        <input type="text" name="username" required><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br>

        <label>Role:</label><br>
        <select name="role" required>
            <option value="">-- Select Role --</option>
            <option value="cashier">Cashier</option>
            <option value="hr_manager">HR Manager</option>
            <option value="product_manager">Product Manager</option>
            <option value="admin">Admin</option> <!-- ✅ Admin role added -->
        </select><br>

        <button type="submit">Sign Up</button>
    </form>
</body>
</html>
