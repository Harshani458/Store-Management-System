<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_invoice'])) {
    $customer_name = $_POST['customer_name'];
    $phone = $_POST['phone'];
    $cashier_username = $_SESSION['username'] ?? 'Unknown';
    $invoice_date = date('Y-m-d');
    $discount = floatval($_POST['discount_percent']);
    $final_amount = floatval($_POST['final_amount']);

    $conn->query("INSERT INTO customer_invoice (customer_name, phone, cashier_username, invoice_date, discount, final_amount)
                  VALUES ('$customer_name', '$phone', '$cashier_username', '$invoice_date', '$discount', '$final_amount')");
    $invoice_id = $conn->insert_id;

    if (!empty($_POST['product_id']) && is_array($_POST['product_id'])) {
        foreach ($_POST['product_id'] as $index => $product_id) {
            $quantity = $_POST['quantity'][$index];
            $unit_price = $_POST['unit_price'][$index];
            $total_price = $quantity * $unit_price;
            $conn->query("INSERT INTO invoice_item (invoice_id, product_id, quantity, unit_price, total_price)
                          VALUES ('$invoice_id', '$product_id', '$quantity', '$unit_price', '$total_price')");
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
    <title>Manage Customer Invoice</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #3e2723, #5d4037);
            margin: 0;
            padding: 20px;
            color: #fbeee6;
        }
        h2 {
            text-align: center;
            font-size: 32px;
            color: #ffe0b2;
            margin-bottom: 25px;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            background-color: #4e342e;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
        }
        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .form-group input, .form-group select {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 8px;
            background-color: #efebe9;
            color: #3e2723;
            font-size: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #ffe0b2;
        }
        table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
            background-color: #efebe9;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #bcaaa4;
            color: #3e2723;
        }
        th {
            background-color: #6d4c41;
            color: #fff3e0;
        }
        .btn {
            padding: 10px 20px;
            margin: 10px 8px 0 0;
            background-color: #8d6e63;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        .btn:hover {
            background-color: #5d4037;
        }
        .form-footer {
            margin-top: 20px;
            text-align: right;
        }
        .totals {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .totals input {
            flex: 1;
        }
    </style>

    <script>
        const productData = {};

        <?php while ($row = $product_result->fetch_assoc()) { ?>
            productData[<?= $row['product_id']; ?>] = {
                name: "<?= htmlspecialchars($row['name'], ENT_QUOTES); ?>",
                price: <?= $row['price']; ?>
            };
        <?php } ?>

        function addRow() {
            const table = document.getElementById("product_table");
            const row = table.insertRow();
            const rowIndex = table.rows.length - 1;

            row.innerHTML = `
                <td>
                    <select name="product_id[]" onchange="updateUnitPrice(this)" required>
                        <option value="">-- Select Product --</option>
                        ${Object.entries(productData).map(([id, p]) =>
                            `<option value="${id}">${p.name}</option>`
                        ).join('')}
                    </select>
                </td>
                <td><input type="number" name="quantity[]" value="1" min="1" oninput="calculateFinal()" required></td>
                <td><input type="number" name="unit_price[]" readonly required></td>
            `;
        }

        function updateUnitPrice(select) {
            const productId = select.value;
            const price = productData[productId]?.price || 0;
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

        function clearForm() {
            document.querySelector("form").reset();
            const table = document.getElementById("product_table");
            table.innerHTML = `
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                </tr>
            `;
            addRow();
        }

        window.onload = () => addRow();
    </script>
</head>
<body>
    <div class="container">
        <h2>Customer Invoice</h2>
        <form method="post">
            <div class="form-group">
                <div style="flex: 1;">
                    <label>Customer Name</label>
                    <input type="text" name="customer_name" required>
                </div>
                <div style="flex: 1;">
                    <label>Phone</label>
                    <input type="text" name="phone">
                </div>
            </div>

            <div class="form-group">
                <div style="flex: 1;">
                    <label>Cashier</label>
                    <input type="text" value="<?= $_SESSION['username'] ?? 'Unknown'; ?>" readonly>
                </div>
                <div style="flex: 1;">
                    <label>Date</label>
                    <input type="text" value="<?= date('Y-m-d'); ?>" readonly>
                </div>
            </div>

            <table id="product_table">
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                </tr>
            </table>

            <button type="button" class="btn" onclick="addRow()">+ Add Product</button>

            <div class="totals">
                <div style="flex: 1;">
                    <label>Discount (%)</label>
                    <input type="number" id="discount_percent" name="discount_percent" value="0" min="0" oninput="calculateFinal()">
                </div>
                <div style="flex: 1;">
                    <label>Discount Amount</label>
                    <input type="text" id="discount_amount" readonly>
                </div>
                <div style="flex: 1;">
                    <label>Final Amount</label>
                    <input type="text" id="final_amount" name="final_amount" readonly>
                </div>
            </div>

            <div class="form-footer">
                <button type="submit" name="submit_invoice" class="btn">Add Invoice</button>
                <button type="button" onclick="clearForm()" class="btn">Clear</button>
                <a href="view_invoice.php" class="btn">View Invoices</a>
            </div>
        </form>
    </div>
</body>
</html>
