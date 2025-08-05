<?php
session_start();
include 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = $_POST['customer_name'];
    $phone = $_POST['phone'];
    $cashier_username = $_SESSION['username'] ?? 'Unknown';
    $invoice_date = date('Y-m-d');
    $discount = floatval($_POST['discount_percent']);
    $final_amount = floatval($_POST['final_amount']);

    $conn->query("INSERT INTO customer_invoice (customer_name, phone, cashier_username, invoice_date, discount, final_amount)
                  VALUES ('$customer_name', '$phone', '$cashier_username', '$invoice_date', '$discount', '$final_amount')");
    $invoice_id = $conn->insert_id;

    if (!empty($_POST['product']) && is_array($_POST['product'])) {
        foreach ($_POST['product'] as $index => $product_id) {
            $quantity = $_POST['quantity'][$index];
            $unit_price = $_POST['unit_price'][$index];

            // Get product name from DB
            $result = $conn->query("SELECT name FROM product WHERE product_id = '$product_id'");
            $row = $result->fetch_assoc();
            $product_name = $row['name'];

            $conn->query("INSERT INTO invoice_item (invoice_id, product_name, quantity, unit_price)
                          VALUES ('$invoice_id', '$product_name', '$quantity', '$unit_price')");
        }
    }

    echo "<script>
        if (confirm('Invoice Added Successfully. Do you want to print?')) {
            window.location.href = 'generate_invoice_pdf.php?invoice_id=$invoice_id';
        } else {
            window.location.href = 'manage_customer_invoice.php';
        }
    </script>";
    exit;
}

$product_result = $conn->query("SELECT * FROM product");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Invoice</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #2d1c12;
            color: #fff;
            padding: 30px;
        }
        h2 {
            text-align: center;
            color: #e8c39e;
        }
        form {
            background-color: #3b281c;
            padding: 25px;
            border-radius: 12px;
            max-width: 950px;
            margin: auto;
            box-shadow: 0 0 10px #00000066;
        }
        input, select {
            padding: 10px;
            margin: 8px;
            border: 1px solid #c69c6d;
            border-radius: 5px;
            background-color: #fff;
            color: #000;
        }
        label {
            display: inline-block;
            width: 140px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            margin-top: 15px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #d2a679;
            text-align: left;
            color: #000;
        }
        th {
            background-color: #8b5e3c;
            color: white;
        }
        .btn {
            background-color: #6e4b32;
            color: #fff;
            padding: 10px 20px;
            margin-top: 15px;
            border: none;
            cursor: pointer;
            border-radius: 6px;
        }
        .btn:hover {
            background-color: #9c6b44;
        }
    </style>

    <script>
        const products = {};

        <?php $product_result->data_seek(0); while($row = $product_result->fetch_assoc()) { ?>
            products[<?= $row['product_id'] ?>] = {
                name: "<?= addslashes($row['name']) ?>",
                price: <?= $row['price'] ?>
            };
        <?php } ?>

        function addRow() {
            const table = document.getElementById("product_table");
            const row = table.insertRow();

            const productOptions = Object.entries(products).map(([id, product]) =>
                `<option value="${id}" data-price="${product.price}">${product.name}</option>`
            ).join('');

            row.innerHTML = `
                <td>
                    <select name="product[]" onchange="updateUnitPrice(this)">
                        <option value="">-- Select Product --</option>
                        ${productOptions}
                    </select>
                </td>
                <td><input type="number" name="quantity[]" value="1" min="1" oninput="calculateFinal()"></td>
                <td><input type="number" name="unit_price[]" readonly></td>
            `;
        }

        function updateUnitPrice(select) {
            const selected = select.options[select.selectedIndex];
            const price = selected.getAttribute('data-price');
            const row = select.closest('tr');
            row.querySelector("input[name='unit_price[]']").value = price;
            calculateFinal();
        }

        function calculateFinal() {
            const quantities = document.getElementsByName("quantity[]");
            const unitPrices = document.getElementsByName("unit_price[]");
            const discount = parseFloat(document.getElementById("discount_percent").value) || 0;
            let total = 0;

            for (let i = 0; i < quantities.length; i++) {
                total += (parseFloat(quantities[i].value) || 0) * (parseFloat(unitPrices[i].value) || 0);
            }

            const discountAmount = total * discount / 100;
            const finalAmount = total - discountAmount;

            document.getElementById("discount_amount").value = discountAmount.toFixed(2);
            document.getElementById("final_amount").value = finalAmount.toFixed(2);
        }

        window.onload = () => addRow();
    </script>
</head>
<body>
    <h2>Create Invoice</h2>
    <form method="post">
        <div>
            <label>Customer Name:</label>
            <input type="text" name="customer_name" required>

            <label>Phone:</label>
            <input type="text" name="phone">

            <label>Cashier:</label>
            <input type="text" name="cashier_username" value="<?= $_SESSION['username'] ?? 'Unknown'; ?>" readonly>

            <label>Date:</label>
            <input type="text" value="<?= date('Y-m-d'); ?>" readonly>
        </div>

        <table id="product_table">
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
            </tr>
        </table>

        <button type="button" class="btn" onclick="addRow()">+ Add Product</button>

        <div style="margin-top: 20px;">
            <label>Discount (%):</label>
            <input type="number" id="discount_percent" name="discount_percent" value="0" oninput="calculateFinal()">

            <label>Discount Amount:</label>
            <input type="text" id="discount_amount" readonly>

            <label>Final Amount:</label>
            <input type="text" id="final_amount" name="final_amount" readonly>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn">Add Invoice</button>
            <a href="view_invoice.php" class="btn">View Invoices</a>
        </div>
    </form>
</body>
</html>
