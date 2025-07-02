<?php
declare(strict_types=1);
namespace Daniilprusakov\TudaykaBot\classes;


class RequestTelegram
{
    private string $token;
    private string $apiBaseUrl = "https://api.telegram.org/bot";
    public function __construct($token)
    {
        $this->token = $token;
    }

    private array $keyboard = [
        "keyboard" => [
            [
                ["text" => "Добавить событие"],
                ["text" => "Проверить события за день"]
            ]
        ]
    ];
    public function sendMessage(int $chatId, string $text): string
    {
        $params = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'html',
            'reply_markup' => json_encode($this->keyboard)
        ];
        return file_get_contents($this->apiBaseUrl . $this->token . '/sendMessage?' . http_build_query($params));


        
    }
}