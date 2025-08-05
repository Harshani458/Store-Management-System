<?php
session_start();
include("includes/db_connect.php");

$username = $_SESSION['username'] ?? '';

$stmt = $conn->prepare("SELECT * FROM leave_requests WHERE username=? ORDER BY created_at DESC");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Leave Status</title>
    <style>
        body { background-color: #f5f3f0; font-family: Arial; }
        .container {
            width: 90%; margin: 20px auto; padding: 20px;
            background: #fff; box-shadow: 0 0 10px #7d5a50; border-radius: 8px;
        }
        table {
            width: 100%; border-collapse: collapse; margin-top: 20px;
        }
        th, td {
            border: 1px solid #b89c89; padding: 8px; text-align: center;
        }
        th {
            background: #7d5a50; color: white;
        }
        h2 {
            color: #5c3d2e;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Your Leave Status</h2>
    <table>
        <tr>
            <th>ID</th><th>Type</th><th>Start</th><th>End</th><th>Reason</th><th>Status</th>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['leave_type'] ?></td>
            <td><?= $row['start_date'] ?></td>
            <td><?= $row['end_date'] ?></td>
            <td><?= $row['reason'] ?></td>
            <td><?= ucfirst($row['status']) ?></td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
