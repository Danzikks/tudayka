<?php

use Daniilprusakov\TudaykaBot\Chat;

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeload();

include_once("../database.php");
include_once("../Chat.php");


$token = $_ENV['BOT_TOKEN'];
$apiUrl = "https://api.telegram.org/bot$token";


$lastUpdateId = 0;
$commandStartText = <<<TEXT
Привет!
Я — бот, которому можно рассказывать, что ты сделал в течение дня.
В конце дня я напомню тебе обо всём, что ты успел — как маленькое напоминание о твоих достижениях.
TEXT;

$commandAddEvent = <<<TEXT
Какое событие добавляем?
TEXT;

$sendMessageUrl = "$apiUrl/sendMessage?";
$chat_id = "";


$sql_search_chat_id = "SELECT * FROM chats WHERE chat_id = ?";


$sql_insert_chat_id = "
    INSERT INTO chats (chat_id, first_name, last_name, username)
    VALUES (:chat_id, :first_name, :last_name, :username)
";

$sql_update_event_chat_id = "
    UPDATE chats
    SET waiting_for_event = :update_event
    WHERE chat_id = :chat_id AND waiting_for_event = :now_event
";

$sql_insert_message = "
    INSERT INTO messages (chat_id, text, created_at)
    VALUES (:chat_id, :text, FROM_UNIXTIME(:date))
";

$sql_check_event_the_day = "
    SELECT TEXT FROM messages 
    WHERE chat_id = :chat_id 
    AND created_at >= CURRENT_DATE
";




while (true) {
    $params = [
        "offset" => $lastUpdateId + 1,
        "timeout" => 25
    ];

    echo date('Y-m-d H:i:s').PHP_EOL;
    $response = file_get_contents("$apiUrl/getUpdates?" . http_build_query($params));
    echo date('Y-m-d H:i:s').PHP_EOL;
    $update = json_decode($response, true);




    foreach ($update["result"] as $item) {

        $chat = new Chat(
            $item["message"]["from"]["username"],
            $item["message"]["chat"]["id"],
            $item["message"]["from"]["first_name"] ?? null,
            $item["message"]["from"]["last_name"] ?? null,
            $item["message"]["date"],
            $item["message"]["text"],
            $apiUrl,
            $item["update_id"]
        );

        $lastUpdateId = $chat->getUpdateId();




        $stmt = $pdo->prepare($sql_search_chat_id);
        $stmt->execute([$chat->getChatId()]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($user == false) {
            $stmt = $pdo->prepare($sql_insert_chat_id);
            $stmt->execute([
                'chat_id' => $chat->getChatId(),
                'first_name' => $chat->getFirstName(),
                'last_name' => $chat->getLastName(),
                'username' => $chat->getUsername()
            ]);
        }






        if ($user["waiting_for_event"] == true) {
            if ($chat->getTextMessage() != "/add_event") {
                $stmt = $pdo->prepare($sql_insert_message);
                $stmt->execute([
                    'chat_id' => $chat->getChatId(),
                    'text' => $chat->getTextMessage(),
                    'date' => $chat->getDateMessage()
                ]);
                $stmt = $pdo->prepare($sql_update_event_chat_id);
                $stmt->execute([
                    'update_event' => 0,
                    'chat_id' => $chat->getChatId(),
                    'now_event' => 1
                ]);
                $chat->sendMessage("Записал!📝");
            } else {
                $chat->sendMessage("Да-да, я уже записываю📝");
            }
        } elseif ($chat->getTextMessage() == "/start") {
            $chat->sendMessage($commandStartText);
        } elseif ($chat->getTextMessage() == "/add_event") {
            $chat->sendMessage($commandAddEvent);
            $stmt = $pdo->prepare($sql_update_event_chat_id);
            $stmt->execute([
                'update_event' => 1,
                'chat_id' => $chat->getChatId(),
                'now_event' => 0
            ]);
        } elseif ($chat->getTextMessage() == "/check_the_day") {
            $stmt = $pdo->prepare($sql_check_event_the_day);
            $stmt->execute([
                'chat_id' => $chat->getChatId()
            ]);
            $user_all_event = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $result_formatting = "";
            foreach ($user_all_event as $key => $value) {
                $result_formatting .= $key + 1 . ") " . $value . "\n";
            }
            $chat->sendMessage("<b>Итоги дня:</b>\n$result_formatting");
        } else {
            $chat->sendMessage("Если хочешь добавить событие, то напиши /add_event");
        }
    }
    sleep(2);
}