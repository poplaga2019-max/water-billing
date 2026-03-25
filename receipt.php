<?php
require_once('tcpdf/tcpdf.php');
include 'config/db.php';

// ===== โหลด setting =====
$setting = [];
$res = mysqli_query($conn,"SELECT * FROM settings");
while($r = mysqli_fetch_assoc($res)){
    $setting[$r['name']] = $r['value'];
}

$id = $_GET['id'] ?? 0;

// ===== ดึงข้อมูล =====
$sql = "SELECT b.*, c.name, c.address 
        FROM bills b 
        JOIN customers c ON b.customer_id=c.id 
        WHERE b.id='$id'";
$row = mysqli_fetch_assoc(mysqli_query($conn,$sql));

// ===== PDF =====
$pdf = new TCPDF('P','mm',[58,200],true,'UTF-8',false);
$pdf->SetMargins(4,4,4);
$pdf->AddPage();
$pdf->SetFont('thsarabun','',14);

// ===== LOGO =====
if(!empty($setting['logo']) && file_exists($setting['logo'])){
    $pdf->Image($setting['logo'], 20, 5, 18);
    $pdf->Ln(15);
}

// ===== HEADER =====
$pdf->Cell(0,5,$setting['site_name'],0,1,'C');
$pdf->Cell(0,5,'ใบเสร็จรับเงิน',0,1,'C');

$pdf->Ln(2);

// ===== CUSTOMER =====
$pdf->Cell(0,5,'ชื่อ: '.$row['name'],0,1);
$pdf->MultiCell(0,5,'ที่อยู่: '.$row['address']);

$pdf->Ln(1);

// ===== INFO =====
$pdf->Cell(0,5,'เลขบิล: '.$row['id'],0,1);
$pdf->Cell(0,5,'วันที่: '.$row['created_at'],0,1);

// ===== STATUS =====
$statusMap = [
    'pending' => 'ยังไม่ชำระ',
    'paid' => 'ชำระแล้ว',
    'verify' => 'รอตรวจสอบ'
];

$pdf->Cell(0,5,'สถานะ: '.$statusMap[$row['status']],0,1);

$pdf->Ln(2);

// ===== DETAIL =====
$pdf->Cell(30,5,'หน่วยน้ำ',0,0);
$pdf->Cell(0,5,$row['used_unit'],0,1);

$pdf->Cell(30,5,'ยอดเงิน',0,0);
$pdf->Cell(0,5,number_format($row['amount'],2).' บาท',0,1);

$pdf->Ln(3);

// ===== QR PromptPay =====
if(!empty($setting['promptpay'])){

    $prompt = preg_replace('/[^0-9]/','',$setting['promptpay']);
    $amount = number_format($row['amount'],2,'.','');

    // ใช้ QR generator (ง่ายสุด)
    $qr_url = "https://promptpay.io/".$prompt."/".$amount.".png";

    $pdf->Cell(0,5,'สแกนจ่าย',0,1,'C');
    $pdf->Image($qr_url, 10, $pdf->GetY(), 35);

    $pdf->Ln(38);
}

// ===== FOOTER =====
$pdf->Cell(0,5,'ขอบคุณที่ใช้บริการ',0,1,'C');

// ===== SAVE FILE =====
$file = "uploads/receipt_".$id.".pdf";
$pdf->Output($file,'F');

// ===== SEND TELEGRAM =====
if(!empty($setting['telegram_token']) && !empty($setting['telegram_chat_finance'])){

    $token = $setting['telegram_token'];
    $chat_id = $setting['telegram_chat_finance'];

    $url = "https://api.telegram.org/bot$token/sendDocument";

    $post_fields = [
        'chat_id' => $chat_id,
        'caption' => "📄 ใบเสร็จ\n".$row['name']."\n".$row['amount']." บาท",
        'document' => new CURLFile(realpath($file))
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_exec($ch);
    curl_close($ch);
}

// ===== OUTPUT =====
$pdf->Output('receipt.pdf','I');
?>
