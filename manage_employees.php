<?php
include 'includes/db_connect.php';

$username = $password = $first_name = $last_name = $email = $gender = $address = $dob = $doh = $role = "";
$update = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $doh = $_POST['doh'];
    $role = $_POST['role'];

    if (isset($_POST['add'])) {
        $stmt = $conn->prepare("INSERT INTO employee VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $username, $password, $first_name, $last_name, $email, $gender, $address, $dob, $doh, $role);
        $stmt->execute();
    } elseif (isset($_POST['update'])) {
        $stmt = $conn->prepare("UPDATE employee SET password=?, first_name=?, last_name=?, email=?, gender=?, address=?, dob=?, doh=?, role=? WHERE username=?");
        $stmt->bind_param("ssssssssss", $password, $first_name, $last_name, $email, $gender, $address, $dob, $doh, $role, $username);
        $stmt->execute();
    }
}

if (isset($_GET['delete'])) {
    $username = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM employee WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
}

if (isset($_GET['edit'])) {
    $username = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM employee WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $password = $row['password'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $email = $row['email'];
        $gender = $row['gender'];
        $address = $row['address'];
        $dob = $row['dob'];
        $doh = $row['doh'];
        $role = $row['role'];
        $update = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Employees</title>
    <style>
        body {
            background-color: #3e2723;
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
            color: #ffffff;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: #5d4037;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
        }

        h2 {
            font-size: 30px;
            margin-bottom: 25px;
            color: #ffffff;
            text-align: center;
        }

        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        form label {
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
            color: #fff3e0;
        }

        form input,
        form select {
            width: 100%;
            padding: 10px;
            border: 1px solid #a1887f;
            border-radius: 8px;
            font-size: 14px;
            background-color: #efebe9;
            color: #3e2723;
        }

        .form-actions {
            grid-column: 1 / -1;
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 15px;
        }

        button {
            padding: 10px 25px;
            border: none;
            background-color: #8d6e63;
            color: white;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #a1887f;
        }

        table {
            margin-top: 40px;
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            background-color: #efebe9;
        }

        th, td {
            border: 1px solid #bcaaa4;
            padding: 12px 10px;
            text-align: left;
            color: #3e2723;
        }

        th {
            background-color: #6d4c41;
            color: #ffffff;
        }

        tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        .action-buttons a {
            text-decoration: none;
            padding: 6px 12px;
            color: white;
            border-radius: 4px;
            margin-right: 5px;
            font-weight: bold;
            font-size: 13px;
        }

        .edit-btn {
            background-color: #4e342e;
        }

        .delete-btn {
            background-color: #d84315;
        }

        @media(max-width: 768px) {
            form {
                grid-template-columns: 1fr;
            }
            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Employees</h2>
        <form method="POST">
            <div>
                <label>Username:</label>
                <input type="text" name="username" value="<?= $username ?>" required <?= $update ? 'readonly' : '' ?>>
            </div>
            <div>
                <label>Password:</label>
                <input type="text" name="password" value="<?= $password ?>" required>
            </div>
            <div>
                <label>First Name:</label>
                <input type="text" name="first_name" value="<?= $first_name ?>" required>
            </div>
            <div>
                <label>Last Name:</label>
                <input type="text" name="last_name" value="<?= $last_name ?>" required>
            </div>
            <div>
                <label>Email:</label>
                <input type="email" name="email" value="<?= $email ?>" required>
            </div>
            <div>
                <label>Gender:</label>
                <select name="gender" required>
                    <option value="">--Select--</option>
                    <option value="Male" <?= ($gender == "Male") ? "selected" : "" ?>>Male</option>
                    <option value="Female" <?= ($gender == "Female") ? "selected" : "" ?>>Female</option>
                </select>
            </div>
            <div>
                <label>Address:</label>
                <input type="text" name="address" value="<?= $address ?>" required>
            </div>
            <div>
                <label>Date of Birth:</label>
                <input type="date" name="dob" value="<?= $dob ?>" required>
            </div>
            <div>
                <label>Date of Hire:</label>
                <input type="date" name="doh" value="<?= $doh ?>" required>
            </div>
            <div>
                <label>Role:</label>
                <select name="role" required>
                    <option value="">--Select--</option>
                    <option value="admin" <?= ($role == "admin") ? "selected" : "" ?>>Admin</option>
                    <option value="cashier" <?= ($role == "cashier") ? "selected" : "" ?>>Cashier</option>
                    <option value="hr manager" <?= ($role == "hr manager") ? "selected" : "" ?>>HR Manager</option>
                    <option value="product manager" <?= ($role == "product manager") ? "selected" : "" ?>>Product Manager</option>
                </select>
            </div>
            <div class="form-actions">
                <?php if ($update): ?>
                    <button type="submit" name="update">Update</button>
                <?php else: ?>
                    <button type="submit" name="add">Add</button>
                <?php endif; ?>
            </div>
        </form>

        <table>
            <tr>
                <th>Username</th>
                <th>Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Address</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM employee");
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?= $row['username'] ?></td>
                <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['gender'] ?></td>
                <td><?= $row['address'] ?></td>
                <td><?= $row['role'] ?></td>
                <td class="action-buttons">
                    <a class="edit-btn" href="?edit=<?= $row['username'] ?>">Edit</a>
                    <a class="delete-btn" href="?delete=<?= $row['username'] ?>" onclick="return confirm('Are you sure to delete this employee?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
