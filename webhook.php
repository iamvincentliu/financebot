<?php
require_once('apiFunctions.php');
$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update) {
    exit;
}
if (!isset($update["message"])) {
    exit;
}

$message = $update["message"];
$message_id = $message['message_id'];
$chat_id = $message['chat']['id'];
$chat_type = $message['chat']['type'];

if (!isset($message['text'])) {
    exit;
}

$text = str_ireplace(TG_BOT_NAME,"",$message['text']);
$text2 = explode(" ", $text, 2);
$command = strtolower(str_replace("/","",$text2[0]));
if(isset($text2[1])) {
    $content = $text2[1];
}
if ($command != "userid" && $chat_id != TG_USER_ID) {
    sendMessage($chat_id, "You do not have permission to use this bot");
    exit;
}

switch($command) {
    case "start":
        sendMessage($chat_id, 'Hello! Welcome to the finance bot. Use "/add amount name" to add items.');
        break;
    case "add":
        addItem($content);
        break;
    case "remove":
        removeLastItem();
        break;
    case "status":
        sendStatus();
        break;
    case "list":
        listSpending();
        break;
    case "userid":
        sendID($chat_id);
        break;
    case "euro":
        toEuro(floatval($content));
        break;
    case "twd":
        toTWD(floatval($content));
        break;
    default:
        sendMessage($chat_id, "Invalid command");
}

?>