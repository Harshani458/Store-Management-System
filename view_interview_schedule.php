<?php
include 'includes/db_connect.php';

$sql = "SELECT * FROM interview ORDER BY interview_date, interview_time";
$result = $conn->query($sql);

if ($result === false) {
    die("❌ Query Failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Interview Schedule</title>
    <style>
        table { border-collapse: collapse; width: 90%; margin: 20px auto; }
        th, td { border: 1px solid #444; padding: 8px; text-align: left; }
        th { background-color: #6b4226; color: white; }
        caption { font-weight: bold; font-size: 1.5em; margin: 10px; }
    </style>
</head>
<body>

<table>
    <caption>Interview Schedule</caption>
    <thead>
        <tr>
            <th>ID</th>
            <th>Candidate Name</th>
            <th>Position</th>
            <th>Date</th>
            <th>Time</th>
            <th>Location</th>
            <th>Interviewer</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['interview_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['candidate_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['position']); ?></td>
                    <td><?php echo htmlspecialchars($row['interview_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['interview_time']); ?></td>
                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                    <td><?php echo htmlspecialchars($row['interviewer_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" style="text-align:center;">No interviews scheduled yet.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>

<?php
$conn->close();
?>
