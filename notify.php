<?php
function sendTelegram($msg, $type='payment'){
    include 'config/db.php';

    $s = [];
    $rs = $conn->query("SELECT * FROM settings");
    while($r=$rs->fetch_assoc()){ $s[$r['name']]=$r['value']; }

    $token = $s['telegram_token'] ?? '';
    if(!$token) return;

    // เลือกห้อง
    $chat = '';
    if($type=='meter') $chat = $s['telegram_meter'] ?? '';
    if($type=='payment') $chat = $s['telegram_payment'] ?? '';
    if(!$chat) return;

    $url = "https://api.telegram.org/bot$token/sendMessage";
    $data = [
        'chat_id' => $chat,
        'text' => $msg
    ];

    file_get_contents($url.'?'.http_build_query($data));
}
