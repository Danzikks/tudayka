<?php
declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';

use Daniilprusakov\TudaykaBot\classes\Chat;
use Daniilprusakov\TudaykaBot\classes\Db;
use Daniilprusakov\TudaykaBot\classes\RequestTelegram;
use Daniilprusakov\TudaykaBot\classes\RequestYougile;
use Dotenv\Dotenv;

require_once '../src/helpers/func.php';
$configDb = require_once '../src/config/config_db.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();




$commandStartText = <<<TEXT
Привет!
Я — бот, которому можно рассказывать, что ты сделал в течение дня.
В конце дня я напомню тебе обо всём, что ты успел — как маленькое напоминание о твоих достижениях.
TEXT;

$commandAddEvent = <<<TEXT
Какое событие добавляем?
TEXT;


$sql_search_chat_id = "SELECT * FROM chats WHERE :chat_id";


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
$sql_check_user_yougile = "
    SELECT * FROM users_yougile
    WHERE username_tg = :username_tg
";



header('Content-Type: application/json');

$request = file_get_contents('php://input');

$update = json_decode($request,true);
//$fp = file_put_contents('request.log', $request);

$token = $_ENV['BOT_TOKEN'];
$requestApi = new RequestTelegram($token);


$chat = new Chat(
    $update["message"]["from"]["username"],
    $update["message"]["chat"]["id"],
    $update["message"]["from"]["first_name"] ?? null,
    $update["message"]["from"]["last_name"] ?? null,
    $update["message"]["date"],
    $update["message"]["text"],
    $update["update_id"]
);

$db = new Db($configDb);
$userTelegram = $db->query($sql_search_chat_id, ['chat_id' => $chat->getChatId()])->fetch();
DD($userTelegram);


if (!$userTelegram) {
    $db->query($sql_insert_chat_id,
        [
        'chat_id' => $chat->getChatId(),
        'first_name' => $chat->getFirstName(),
        'last_name' => $chat->getLastName(),
        'username' => $chat->getUsername()
        ]);
}



if ($userTelegram["waiting_for_event"] === true) {
    if ($chat->getTextMessage() !== "Добавить событие") {
        $db->query($sql_insert_message,
            [
            'chat_id' => $chat->getChatId(),
            'text' => $chat->getTextMessage(),
            'date' => $chat->getDateMessage()
            ]);
        $db->query($sql_update_event_chat_id,
            [
            'update_event' => 0,
            'chat_id' => $chat->getChatId(),
            'now_event' => 1
            ]);
        $requestApi->sendMessage($chat->getChatId(),"Записал!📝");
    } else {
        $requestApi->sendMessage($chat->getChatId(),"Да-да, я уже записываю📝");
    }
} elseif ($chat->getTextMessage() === "/start") {
    $requestApi->sendMessage($chat->getChatId(),$commandStartText);
} elseif ($chat->getTextMessage() === "Добавить событие") {
    $requestApi->sendMessage($chat->getChatId(),$commandAddEvent);
    $db->query($sql_update_event_chat_id,
        [
        'update_event' => 1,
        'chat_id' => $chat->getChatId(),
        'now_event' => 0
        ]);
} elseif ($chat->getTextMessage() === "/send_message") {
    $userYG = $db->query($sql_check_user_yougile, ['username_tg'=>$chat->getUsername()])->fetch();
    if ($chat->getUsername() === $userYG["username_tg"]) {
        $requestYg = new RequestYougile($userYG["auth_token"]);
        $user_all_event = $db->query($sql_check_event_the_day, ['chat_id' => $chat->getChatId()])->fetchAll(PDO::FETCH_COLUMN);
        if (empty($user_all_event)) {
            $requestApi->sendMessage($chat->getChatId(),"А ты что нибудь сделал за сегодня?");
        } else {
            $result_formatting = "";
            foreach ($user_all_event as $key => $value) {
                $result_formatting .= $key + 1 . ") " . $value . "\n";
            }
            $currentDate = date('d.m.Y');
            $requestYg->sendMessageYougile($userYG["chat_id"], "$currentDate\n\nИтоги дня:\n$result_formatting");
        }
    }
} elseif ($chat->getTextMessage() === "Проверить события за день") {
    $user_all_event = $db->query($sql_check_event_the_day, ['chat_id' => $chat->getChatId()])->fetchAll(PDO::FETCH_COLUMN);
    if (empty($user_all_event)) {
        $requestApi->sendMessage($chat->getChatId(),"А ты что нибудь сделал за сегодня?");
    } else {
        $result_formatting = "";
        foreach ($user_all_event as $key => $value) {
            $result_formatting .= $key + 1 . ") " . $value . "\n";
        }
        $requestApi->sendMessage($chat->getChatId(),"<b>Итоги дня:</b>\n$result_formatting");
    }
} else {
    $requestApi->sendMessage($chat->getChatId(), "Если хочешь добавить событие, то нажми на кнопку \"Добавить событие\"");
}