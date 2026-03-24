<?php
include 'config/db.php';

function sendTelegram($msg, $type){

    global $conn;

    $set = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();

    $token = $set['telegram_token'];

    if($type == 'meter'){
        $chat_id = $set['telegram_meter'];
    }else{
        $chat_id = $set['telegram_payment'];
    }

    $url = "https://api.telegram.org/bot$token/sendMessage";

    file_get_contents($url."?chat_id=$chat_id&text=".urlencode($msg));
}
?>
