<?php
declare(strict_types=1);
namespace Daniilprusakov\TudaykaBot;


class RequestTelegram
{
    private string $tokenUser;
    private string $apiBaseUrl = "https://ru.yougile.com/api-v2";
    public function __construct($tokenUser)
    {
        $this->tokenUser = $tokenUser;
    }

    public function sendMessageYougile(int $chatId, string $text): string
    {

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "$this->apiBaseUrl . /chats . $chatId . /messages",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n  \"textHtml\": \"$text!\",\n}",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $this->tokenUser",
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }
}