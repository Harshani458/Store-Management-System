<?php
include 'includes/db_connect.php';

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $report_date = date('Y-m-d'); // auto-filled
    $month = $_POST['month'];
    $total_sales = $_POST['total_sales'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO daily_sales_report (report_date, month, total_sales, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $report_date, $month, $total_sales, $description);

    if ($stmt->execute()) {
        $success = "✅ Report added successfully!";
    } else {
        $error = "❌ Error adding report: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daily Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #5a3e2b;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            background-color: #ffffff;
            margin: 40px auto;
            padding: 30px;
            box-shadow: 0 0 10px rgba(89, 54, 36, 0.1);
            border-radius: 12px;
            border-top: 5px solid #5a3e2b;
        }

        h2 {
            text-align: center;
            color: #4b2c20;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #4b2c20;
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        .btn-container {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        button {
            padding: 10px 18px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            color: white;
            transition: 0.3s ease;
        }

        .add-btn {
            background-color: #5a3e2b;
        }

        .add-btn:hover {
            background-color: #3c2618;
        }

        .view-btn {
            background-color: #6b4c36;
        }

        .view-btn:hover {
            background-color: #3d2b1e;
        }

        .message {
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🧾 Add Daily Sales Report</h2>

    <?php if ($success): ?>
        <div class="message success"><?php echo $success; ?></div>
    <?php elseif ($error): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Report Date</label>
        <input type="text" name="report_date" value="<?php echo date('Y-m-d'); ?>" readonly>

        <label>Month</label>
        <select name="month" required>
            <option value="">-- Select Month --</option>
            <?php
            $months = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];
            foreach ($months as $m) {
                echo "<option value='$m'>$m</option>";
            }
            ?>
        </select>

        <label>Total Sales (Rs.)</label>
        <input type="number" name="total_sales" step="0.01" required>

        <label>Description</label>
        <textarea name="description" rows="4" placeholder="Enter description..." required></textarea>

        <div class="btn-container">
            <button type="submit" class="add-btn">➕ Add Report</button>
            <a href="view_reports.php"><button type="button" class="view-btn">📄 View Reports</button></a>
        </div>
    </form>
</div>

</body>
</html>
