<?php include('includes/db_connect.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>View Sales Reports</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 40px auto;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.05);
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #5c3d2e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #8b5e3c;
            color: white;
        }

        tr:hover {
            background-color: #f0ece8;
        }

        .back-btn {
            background-color: #5c3d2e;
            color: white;
            padding: 10px 18px;
            border: none;
            text-decoration: none;
            font-size: 14px;
            border-radius: 6px;
            margin-bottom: 15px;
            display: inline-block;
        }

        .back-btn:hover {
            background-color: #402a1e;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="reports.php" class="back-btn">⬅ Back to Add Report</a>
        <h2>All Daily Sales Reports</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Report Date</th>
                <th>Month</th>
                <th>Total Sales (Rs.)</th>
                <th>Description</th>
            </tr>
            <?php
            $query = "SELECT * FROM daily_sales_report ORDER BY report_date DESC";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['report_date']}</td>
                            <td>{$row['month']}</td>
                            <td>{$row['total_sales']}</td>
                            <td>{$row['description']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No reports found.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
