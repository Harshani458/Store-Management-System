<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['customer_name']);
    $phone = $conn->real_escape_string($_POST['customer_phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $type = $conn->real_escape_string($_POST['product_type']);
    $description = $conn->real_escape_string($_POST['product_description']);
    $delivery = $conn->real_escape_string($_POST['delivery_date']);
    $order_date = $conn->real_escape_string($_POST['order_date']);

    $sql = "INSERT INTO custom_orders (customer_name, customer_phone, address, product_type, product_description, delivery_date, order_date)
            VALUES ('$name', '$phone', '$address', '$type', '$description', '$delivery', '$order_date')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Order placed successfully!'); window.location.href='manage_order.php';</script>";
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Custom Order - Kalana Furniture</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        body {
            margin: 0; padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #5D4037; /* medium dark brown */
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .order-container {
            background-color: #D7CCC8; /* light beige/brown */
            padding: 35px 40px;
            border-radius: 12px;
            width: 600px;
            box-shadow: 0 5px 15px rgba(109, 76, 65, 0.3);
            color: #3E2723; /* dark brown text on light background */
        }

        h2 {
            margin-top: 0;
            margin-bottom: 30px;
            font-weight: 600;
            font-size: 28px;
            text-align: center;
            color: #3E2723; /* dark brown */
            letter-spacing: 1.1px;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
        }

        .form-group {
            flex: 1 1 48%;
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            flex: 1 1 100%;
        }

        label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #6D4C41; /* medium brown */
        }

        input[type=text],
        input[type=date],
        select,
        textarea {
            padding: 12px 15px;
            border: 2px solid #A1887F; /* light brown border */
            border-radius: 8px;
            background-color: #fff;
            color: #3E2723; /* dark brown */
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        input[type=text]:focus,
        input[type=date]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #6D4C41; /* medium brown */
            box-shadow: 0 0 8px #6D4C41AA;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .btn-submit {
            flex: 1 1 100%;
            background-color: #6D4C41; /* medium brown */
            border: none;
            color: #fff;
            font-weight: 700;
            font-size: 16px;
            padding: 15px;
            border-radius: 12px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 12px;
            box-shadow: 0 4px 10px rgba(109, 76, 65, 0.5);
        }

        .btn-submit:hover {
            background-color: #4E342E; /* darker medium brown */
            box-shadow: 0 6px 15px rgba(78, 52, 46, 0.8);
        }

        .btn-view {
            display: block;
            text-align: center;
            margin: 25px auto 0;
            padding: 12px 28px;
            background-color: #A1887F; /* light brown */
            color: #3E2723; /* dark brown */
            font-weight: 600;
            border-radius: 12px;
            text-decoration: none;
            box-shadow: 0 3px 10px rgba(161, 136, 127, 0.7);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            width: 180px;
        }

        .btn-view:hover {
            background-color: #6D4C41;
            color: #fff;
            box-shadow: 0 5px 15px rgba(109, 76, 65, 0.8);
        }

        @media (max-width: 650px) {
            .order-container {
                width: 90vw;
                padding: 30px 25px;
            }
            .form-group {
                flex: 1 1 100%;
            }
        }
    </style>
</head>
<body>

<div class="order-container">
    <h2>Custom Product Order</h2>
    <form action="" method="POST">
        <div class="form-group">
            <label for="customer_name">Customer Name:</label>
            <input type="text" name="customer_name" id="customer_name" required />
        </div>

        <div class="form-group">
            <label for="customer_phone">Phone Number:</label>
            <input type="text" name="customer_phone" id="customer_phone" required />
        </div>

        <div class="form-group full-width">
            <label for="address">Delivery Address:</label>
            <input type="text" name="address" id="address" required />
        </div>

        <div class="form-group">
            <label for="product_type">Product Type:</label>
            <select name="product_type" id="product_type" required>
                <option value="" disabled selected>-- Select Type --</option>
                <option value="Bed">Bed</option>
                <option value="Chair">Chair</option>
                <option value="Table">Table</option>
                <option value="Cupboard">Cupboard</option>
                <option value="Sofa">Sofa</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="order_date">Order Date:</label>
            <input type="date" name="order_date" id="order_date" value="<?php echo date('Y-m-d'); ?>" readonly />
        </div>

        <div class="form-group full-width">
            <label for="product_description">Product Description / Instructions:</label>
            <textarea name="product_description" id="product_description" required></textarea>
        </div>

        <div class="form-group full-width">
            <label for="delivery_date">Delivery Date:</label>
            <input type="date" name="delivery_date" id="delivery_date" required />
        </div>

        <button type="submit" class="btn-submit">Place Order</button>
    </form>

    <a href="view_orders.php" class="btn-view">View Orders</a>
</div>

</body>
</html>
