<?php
require_once('tcpdf_6_3_2/tcpdf/tcpdf.php');


$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 16);
$pdf->Write(0, '✅ TCPDF is working!');
$pdf->Output('test.pdf', 'I'); // I = show in browser, D = download
?>
