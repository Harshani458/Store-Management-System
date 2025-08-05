<?php
require_once('../tcpdf_6_3_2/tcpdf/tcpdf.php'); // Make sure path is correct
include('db_connect.php');

if (!isset($_GET['invoice_id'])) {
    die("Invoice ID missing.");
}

$invoice_id = intval($_GET['invoice_id']);

// Fetch invoice details
$invoice = $conn->query("SELECT * FROM customer_invoice WHERE invoice_id = $invoice_id")->fetch_assoc();

// Fetch invoice items with correct product name field
$items = $conn->query("
    SELECT ii.*, p.name AS product_name
    FROM invoice_item ii
    JOIN product p ON ii.product_id = p.product_id
    WHERE ii.invoice_id = $invoice_id
");

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// Invoice header
$html = "<h2>Invoice #$invoice_id</h2>
         <strong>Date:</strong> {$invoice['invoice_date']}<br>
         <strong>Customer:</strong> {$invoice['customer_name']}<br>
         <strong>Phone:</strong> {$invoice['phone']}<br>
         <strong>Cashier:</strong> {$invoice['cashier_username']}<br><br>";

// Table headers
$html .= "<table border='1' cellpadding='5'>
            <tr>
                <th><b>Product</b></th>
                <th><b>Quantity</b></th>
                <th><b>Unit Price</b></th>
                <th><b>Total</b></th>
            </tr>";

$total = 0;
while ($item = $items->fetch_assoc()) {
    $lineTotal = $item['quantity'] * $item['unit_price'];
    $total += $lineTotal;
    $html .= "<tr>
                <td>{$item['product_name']}</td>
                <td>{$item['quantity']}</td>
                <td>" . number_format($item['unit_price'], 2) . "</td>
                <td>" . number_format($lineTotal, 2) . "</td>
              </tr>";
}

// Calculate final amount
$discount = $invoice['discount'];
$discountAmount = $total * $discount / 100;
$finalAmount = $total - $discountAmount;

// Totals section
$html .= "</table><br>
          <strong>Subtotal:</strong> " . number_format($total, 2) . "<br>
          <strong>Discount ($discount%):</strong> -" . number_format($discountAmount, 2) . "<br>
          <strong>Total:</strong> " . number_format($finalAmount, 2);

// Output the PDF
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output("Invoice_$invoice_id.pdf", 'D'); // 'I' for view, 'D' for download
?>
