<?php
include('includes/db_connect.php');

$candidate_name = $position = $interview_date = $interview_time = $location = $interviewer_name = $status = "";
$update_mode = false;

// Add Interview
if (isset($_POST['add'])) {
    $stmt = $conn->prepare("INSERT INTO interview (candidate_name, position, interview_date, interview_time, location, interviewer_name, status)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $_POST['candidate_name'], $_POST['position'], $_POST['interview_date'], $_POST['interview_time'],
        $_POST['location'], $_POST['interviewer_name'], $_POST['status']);
    $stmt->execute();
    header("Location: add_interview.php");
    exit();
}

// Update Interview
if (isset($_POST['update'])) {
    $stmt = $conn->prepare("UPDATE interview SET candidate_name=?, position=?, interview_date=?, interview_time=?, location=?, interviewer_name=?, status=? WHERE interview_id=?");
    $stmt->bind_param("sssssssi", $_POST['candidate_name'], $_POST['position'], $_POST['interview_date'], $_POST['interview_time'],
        $_POST['location'], $_POST['interviewer_name'], $_POST['status'], $_POST['interview_id']);
    $stmt->execute();
    header("Location: add_interview.php");
    exit();
}

// Delete Interview
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM interview WHERE interview_id=?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header("Location: add_interview.php");
    exit();
}

// Populate fields for update
if (isset($_GET['edit'])) {
    $stmt = $conn->prepare("SELECT * FROM interview WHERE interview_id=?");
    $stmt->bind_param("i", $_GET['edit']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $candidate_name = $row['candidate_name'];
        $position = $row['position'];
        $interview_date = $row['interview_date'];
        $interview_time = $row['interview_time'];
        $location = $row['location'];
        $interviewer_name = $row['interviewer_name'];
        $status = $row['status'];
        $interview_id = $row['interview_id'];
        $update_mode = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Interview Schedule Dashboard</title>
    <style>
        /* Base styles */
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fdfaf7; /* soft off-white */
            color: #4b3621; /* dark brown text */
            padding: 40px 20px;
            margin: 0;
        }

        .container {
            max-width: 900px;
            margin: 0 auto 50px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 14px rgba(75, 54, 33, 0.3); /* subtle brown shadow */
            padding: 30px 40px;
            transition: box-shadow 0.3s ease;
        }

        .container:hover {
            box-shadow: 0 6px 30px rgba(75, 54, 33, 0.45);
        }

        h2 {
            color: #3e2723; /* darker brown */
            text-align: center;
            margin-bottom: 25px;
            font-weight: 700;
            letter-spacing: 0.03em;
        }

        .btn-view {
            display: inline-block;
            padding: 12px 22px;
            background-color: #5d4037; /* medium brown */
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 30px;
            box-shadow: 0 3px 8px rgba(93, 64, 55, 0.5);
            transition: background-color 0.3s ease;
        }

        .btn-view:hover {
            background-color: #3e2723; /* dark brown */
            box-shadow: 0 5px 16px rgba(62, 39, 35, 0.65);
        }

        form label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            color: #5d4037; /* medium brown */
        }

        form input[type=text],
        form input[type=date],
        form input[type=time],
        form select {
            width: 100%;
            padding: 12px 14px;
            margin-top: 6px;
            border: 1.8px solid #d7ccc8; /* light brown border */
            border-radius: 8px;
            font-size: 1rem;
            background-color: #fcf8f4; /* very light cream */
            color: #4b3621; /* dark brown text */
            transition: border-color 0.3s ease;
        }

        form input[type=text]:focus,
        form input[type=date]:focus,
        form input[type=time]:focus,
        form select:focus {
            border-color: #3e2723; /* dark brown */
            outline: none;
            background-color: #fff;
            box-shadow: 0 0 8px rgba(62, 39, 35, 0.3);
        }

        form button {
            margin-top: 28px;
            padding: 14px 0;
            background-color: #5d4037; /* medium brown */
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1.15rem;
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(93, 64, 55, 0.5);
            width: 100%;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        form button:hover {
            background-color: #3e2723; /* dark brown */
            box-shadow: 0 6px 20px rgba(62, 39, 35, 0.7);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 45px;
            background-color: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 14px rgba(75, 54, 33, 0.25);
        }

        th, td {
            padding: 14px 20px;
            text-align: center;
            border-bottom: 1px solid #d7ccc8; /* light brown */
            font-size: 0.95rem;
            color: #4b3621;
        }

        th {
            background-color: #efebe9; /* very light brown/cream */
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        tr:hover {
            background-color: #f5ede7; /* subtle light brown on hover */
        }

        .actions a {
            padding: 8px 14px;
            margin: 0 4px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.3s ease;
            display: inline-block;
            font-size: 0.9rem;
            box-shadow: 0 2px 8px rgba(75, 54, 33, 0.3);
        }

        .actions a.edit {
            background-color: #7b5e57; /* lighter brown */
            color: white;
            box-shadow: 0 3px 8px rgba(123, 94, 87, 0.5);
        }

        .actions a.edit:hover {
            background-color: #5d4037; /* medium brown */
            box-shadow: 0 5px 14px rgba(93, 64, 55, 0.6);
        }

        .actions a.delete {
            background-color: #a0522d; /* sienna brown/red */
            color: white;
            box-shadow: 0 3px 8px rgba(160, 82, 45, 0.5);
        }

        .actions a.delete:hover {
            background-color: #6e3115; /* darker sienna */
            box-shadow: 0 5px 14px rgba(110, 49, 21, 0.7);
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px 15px;
            }

            form button {
                font-size: 1rem;
                padding: 12px 0;
            }

            th, td {
                font-size: 0.85rem;
                padding: 10px 12px;
            }

            .actions a {
                font-size: 0.85rem;
                padding: 6px 10px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2><?php echo $update_mode ? "Update Interview Schedule" : "Add Interview Schedule"; ?></h2>

        <a href="view_interview_schedule.php" class="btn-view">📋 View All Interviews</a>

        <form method="POST" novalidate>
            <input type="hidden" name="interview_id" value="<?php echo $update_mode ? $interview_id : ''; ?>">

            <label for="candidate_name">Candidate Name</label>
            <input id="candidate_name" type="text" name="candidate_name" value="<?php echo htmlspecialchars($candidate_name); ?>" required>

            <label for="position">Position</label>
            <input id="position" type="text" name="position" value="<?php echo htmlspecialchars($position); ?>" required>

            <label for="interview_date">Interview Date</label>
            <input id="interview_date" type="date" name="interview_date" value="<?php echo htmlspecialchars($interview_date); ?>" required>

            <label for="interview_time">Interview Time</label>
            <input id="interview_time" type="time" name="interview_time" value="<?php echo htmlspecialchars($interview_time); ?>" required>

            <label for="location">Location</label>
            <input id="location" type="text" name="location" value="<?php echo htmlspecialchars($location); ?>" required>

            <label for="interviewer_name">Interviewer Name</label>
            <input id="interviewer_name" type="text" name="interviewer_name" value="<?php echo htmlspecialchars($interviewer_name); ?>" required>

            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="Scheduled" <?php if ($status == 'Scheduled') echo "selected"; ?>>Scheduled</option>
                <option value="Completed" <?php if ($status == 'Completed') echo "selected"; ?>>Completed</option>
                <option value="Cancelled" <?php if ($status == 'Cancelled') echo "selected"; ?>>Cancelled</option>
            </select>

            <button type="submit" name="<?php echo $update_mode ? 'update' : 'add'; ?>">
                <?php echo $update_mode ? 'Update Interview' : 'Add Interview'; ?>
            </button>
        </form>
    </div>

    <div class="container">
        <h2>All Interview Schedules</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Candidate</th>
                    <th>Position</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Interviewer</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $result = $conn->query("SELECT * FROM interview ORDER BY interview_date DESC, interview_time DESC");
            while ($row = $result->fetch_assoc()):
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['interview_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['candidate_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['position']); ?></td>
                    <td><?php echo htmlspecialchars($row['interview_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['interview_time']); ?></td>
                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                    <td><?php echo htmlspecialchars($row['interviewer_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td class="actions">
                        <a class="edit" href="add_interview.php?edit=<?php echo $row['interview_id']; ?>">Edit</a>
                        <a class="delete" href="add_interview.php?delete=<?php echo $row['interview_id']; ?>" onclick="return confirm('Delete this record?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
