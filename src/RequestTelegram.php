<?php
declare(strict_types=1);
namespace Daniilprusakov\TudaykaBot;


class RequestTelegram
{
    private string $token;
    private string $apiBaseUrl = "https://api.telegram.org/bot";
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function sendMessage(int $chatId, string $text): string
    {
        $params = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'html'
        ];
        return file_get_contents($this->apiBaseUrl . $this->token . '/sendMessage?' . http_build_query($params));


        
    }
}