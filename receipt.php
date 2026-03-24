<?php
require_once('tcpdf/tcpdf.php');
include 'config/db.php';

// ดึง setting
$set = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();

$id = $_GET['id'];

$data = $conn->query("
SELECT b.*, c.name, c.address
FROM bills b
JOIN customers c ON b.customer_id = c.id
WHERE b.id=$id
")->fetch_assoc();

// ===== สร้าง PDF 58mm =====
$pdf = new TCPDF('P', 'mm', array(58, 150), true, 'UTF-8', false);
$pdf->SetMargins(3, 3, 3);
$pdf->AddPage();

$pdf->SetFont('freeserif', '', 10);

// ===== โลโก้ =====
if(!empty($set['logo'])){
    $pdf->Image($set['logo'], 18, 3, 20);
    $pdf->Ln(15);
}

// ===== หัว =====
$pdf->Cell(0,5,'ใบเสร็จค่าน้ำ',0,1,'C');
$pdf->Cell(0,5,$set['site_name'],0,1,'C');

$pdf->Ln(2);
$pdf->Cell(0,0,'-------------------------',0,1,'C');

// ===== ข้อมูล =====
$pdf->Ln(2);
$pdf->Cell(0,5,'ลูกค้า: '.$data['name'],0,1);
$pdf->MultiCell(0,5,'ที่อยู่: '.$data['address'],0,1);

$pdf->Cell(0,0,'-------------------------',0,1,'C');

// ===== รายการ =====
$pdf->Cell(0,5,'ค่าน้ำ: '.$data['used_unit'].' หน่วย',0,1);
$pdf->Cell(0,5,'ยอด: '.number_format($data['amount']).' บาท',0,1);

$pdf->Cell(0,0,'-------------------------',0,1,'C');

// ===== วันที่ =====
$pdf->Ln(2);
$pdf->Cell(0,5,'วันที่: '.$data['created_at'],0,1);

// ===== ปิดท้าย =====
$pdf->Ln(3);
$pdf->Cell(0,5,'ขอบคุณที่ใช้บริการ',0,1,'C');

// แสดงผล
$pdf->Output('receipt.pdf', 'I');
?>
