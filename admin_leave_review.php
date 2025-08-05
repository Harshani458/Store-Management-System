<?php
include("includes/db_connect.php");

if (isset($_POST['update_status'])) {
    $stmt = $conn->prepare("UPDATE leave_requests SET status=? WHERE id=?");
    $stmt->bind_param("si", $_POST['status'], $_POST['id']);
    $stmt->execute();
}

$result = $conn->query("SELECT * FROM leave_requests ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Leave Review</title>
    <style>
        body { background: #f5f3f0; font-family: Arial; }
        .container {
            width: 90%; margin: 20px auto; background: #fff;
            border-radius: 8px; padding: 20px;
            box-shadow: 0 0 10px #7d5a50;
        }
        table {
            width: 100%; border-collapse: collapse; margin-top: 20px;
        }
        th, td {
            border: 1px solid #b89c89; padding: 8px; text-align: center;
        }
        th {
            background-color: #7d5a50; color: white;
        }
        form {
            display: inline-block;
        }
        select, button {
            padding: 5px; border-radius: 5px; border: 1px solid #7d5a50;
        }
        button {
            background: #7d5a50; color: white; cursor: pointer;
        }
        button:hover {
            background: #5c3d2e;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Admin Leave Request Review</h2>
    <table>
        <tr>
            <th>ID</th><th>Username</th><th>Type</th><th>Start</th><th>End</th><th>Reason</th><th>Status</th><th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['username'] ?></td>
                <td><?= $row['leave_type'] ?></td>
                <td><?= $row['start_date'] ?></td>
                <td><?= $row['end_date'] ?></td>
                <td><?= $row['reason'] ?></td>
                <td><?= ucfirst($row['status']) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <select name="status">
                            <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="approved" <?= $row['status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                            <option value="rejected" <?= $row['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                        <button type="submit" name="update_status">Update</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
