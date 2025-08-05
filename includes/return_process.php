<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'cashier') {
    header("Location: ../login.php");
    exit();
}

include '../includes/db_connect.php';

if (!isset($_GET['item_id']) || !isset($_GET['invoice_id'])) {
    echo "Invalid request.";
    exit();
}

$item_id = intval($_GET['item_id']);
$invoice_id = intval($_GET['invoice_id']);

$sql = "SELECT ii.quantity AS original_quantity, p.name AS product_name, p.product_id 
        FROM invoice_item ii 
        JOIN product p ON ii.product_id = p.product_id 
        WHERE ii.item_id = ? AND ii.invoice_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $item_id, $invoice_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Item not found.";
    exit();
}

$row = $result->fetch_assoc();
$original_quantity = $row['original_quantity'];
$product_name = $row['product_name'];
$product_id = $row['product_id'];

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $return_qty = intval($_POST['return_quantity']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];

    if ($return_qty <= 0) {
        $error = "Return quantity must be at least 1.";
    } elseif ($return_qty > $original_quantity) {
        $error = "Return quantity cannot exceed purchased quantity ($original_quantity).";
    } elseif (empty($description)) {
        $error = "Please provide a return description.";
    } elseif (!in_array($status, ['Pending', 'Confirmed', 'Cancelled'])) {
        $error = "Invalid return status selected.";
    } else {
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("INSERT INTO return_item (item_id, invoice_id, return_quantity, description, status) 
                                    VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiss", $item_id, $invoice_id, $return_qty, $description, $status);
            $stmt->execute();

            // Only update stock if confirmed
            if ($status === 'Confirmed') {
                $conn->query("UPDATE product SET stock_quantity = stock_quantity + $return_qty WHERE product_id = $product_id");
                $conn->query("UPDATE invoice_item SET quantity = quantity - $return_qty WHERE item_id = $item_id");
            }

            $conn->commit();
            $success = "$return_qty unit(s) of <strong>$product_name</strong> returned successfully. <br> Status: <strong>$status</strong><br>Description: <em>$description</em>";
        } catch (Exception $e) {
            $conn->rollback();
            $error = "Return failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Process Return</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
            padding: 40px;
        }
        .container {
            background-color: white;
            max-width: 520px;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
        }
        h2 { color: #5d4037; text-align: center; }
        label { font-weight: bold; display: block; margin-top: 20px; }
        input[type="number"], textarea, select {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            margin-top: 25px;
            background-color: #5d4037;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
        }
        button:hover { background-color: #3e2723; }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #5d4037;
            font-weight: bold;
            text-decoration: none;
        }
        .message { text-align: center; font-weight: 600; margin-top: 20px; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

<div class="container">
    <h2>Return Product: <?php echo htmlspecialchars($product_name); ?></h2>
    <p>Original Quantity: <strong><?php echo $original_quantity; ?></strong></p>

    <?php if ($error): ?>
        <p class="message error"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="message success"><?php echo $success; ?></p>
        <a href="manage_return.php">← Back to Manage Returns</a>
    <?php else: ?>
        <form method="POST">
            <label for="return_quantity">Return Quantity:</label>
            <input type="number" name="return_quantity" id="return_quantity" min="1" max="<?php echo $original_quantity; ?>" required>

            <label for="description">Return Description:</label>
            <textarea name="description" id="description" rows="4" placeholder="e.g., Product was broken or not working" required></textarea>

            <label for="status">Return Status:</label>
            <select name="status" id="status" required>
                <option value="">-- Select Status --</option>
                <option value="Pending">Pending</option>
                <option value="Confirmed">Confirmed</option>
                <option value="Cancelled">Cancelled</option>
            </select>

            <button type="submit">Confirm Return</button>
        </form>

        <a href="manage_return.php">← Cancel & Back</a>
    <?php endif; ?>
</div>

</body>
</html>
