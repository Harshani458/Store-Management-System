<?php
session_start();
include("includes/db_connect.php");

$username = $_SESSION['username'] ?? '';

$leave_type = $start_date = $end_date = $reason = '';
$status = 'pending';

if (isset($_POST['add'])) {
    $stmt = $conn->prepare("INSERT INTO leave_requests (username, leave_type, start_date, end_date, reason) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $_POST['leave_type'], $_POST['start_date'], $_POST['end_date'], $_POST['reason']);
    $stmt->execute();
}

if (isset($_POST['update'])) {
    $stmt = $conn->prepare("UPDATE leave_requests SET leave_type=?, start_date=?, end_date=?, reason=? WHERE id=? AND username=?");
    $stmt->bind_param("ssssss", $_POST['leave_type'], $_POST['start_date'], $_POST['end_date'], $_POST['reason'], $_POST['id'], $username);
    $stmt->execute();
}

if (isset($_POST['delete'])) {
    $stmt = $conn->prepare("DELETE FROM leave_requests WHERE id=? AND username=?");
    $stmt->bind_param("ss", $_POST['id'], $username);
    $stmt->execute();
}

$result = $conn->prepare("SELECT * FROM leave_requests WHERE username=? ORDER BY created_at DESC");
$result->bind_param("s", $username);
$result->execute();
$leaves = $result->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Leave Request</title>
    <style>
        body {
            background-color: #f5f3f0;
            font-family: Arial, sans-serif;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px #7d5a50;
            padding: 20px;
        }
        h2 {
            color: #5c3d2e;
        }
        form {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .form-group {
            width: 48%;
            margin-bottom: 15px;
        }
        input, select, textarea, button {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #b89c89;
            border-radius: 5px;
        }
        button {
            background-color: #7d5a50;
            color: #fff;
            border: none;
            margin-top: 10px;
            cursor: pointer;
        }
        button:hover {
            background-color: #5c3d2e;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #b89c89;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #7d5a50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f0e7e3;
        }
        .status-btn {
            margin-top: 15px;
            text-align: right;
        }
        .status-btn a {
            background: #7d5a50;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
        }
        .status-btn a:hover {
            background: #5c3d2e;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Manage Leave Requests</h2>
    <form method="POST">
        <div class="form-group">
            <label>Leave Type:</label>
            <select name="leave_type" required>
                <option value="">Select</option>
                <option value="Casual">Casual</option>
                <option value="Medical">Medical</option>
                <option value="Annual">Annual</option>
            </select>
        </div>
        <div class="form-group">
            <label>Start Date:</label>
            <input type="date" name="start_date" required>
        </div>
        <div class="form-group">
            <label>End Date:</label>
            <input type="date" name="end_date" required>
        </div>
        <div class="form-group">
            <label>Reason:</label>
            <textarea name="reason" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label>Leave ID (for update/delete):</label>
            <input type="text" name="id">
        </div>
        <div class="form-group">
            <button type="submit" name="add">Add</button>
            <button type="submit" name="update">Update</button>
            <button type="submit" name="delete">Delete</button>
        </div>
    </form>

    <div class="status-btn">
        <a href="leave_status.php">Leave Status</a>
    </div>

    <table>
        <tr>
            <th>ID</th><th>Type</th><th>Start</th><th>End</th><th>Reason</th><th>Status</th>
        </tr>
        <?php while($row = $leaves->fetch_assoc()) { ?>
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
