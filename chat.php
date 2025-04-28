<?php

class Chat
{
    private $username;
    private $chat_id;
    private $first_name;
    private $last_name;
    private $date_message;
    private $text_message;
    private $apiUrl;
    private $UpdateId;

    public function __construct(
        string $username,
        int $chat_id,
        ?string $first_name,
        ?string $last_name,
        int $date_message,
        string $text_message,
        string $apiUrl,
        int $UpdateId
    ) {
        $this->username = $username;
        $this->chat_id = $chat_id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->date_message = $date_message;
        $this->text_message = $text_message;
        $this->apiUrl = $apiUrl;
        $this->UpdateId = $UpdateId;
    }

    function sendMessage($message)
    {

        $params = [
            'chat_id' => $this->getChatId(),
            'text' => $message,
            'parse_mode' => 'html'
        ];

        return file_get_contents($this->apiUrl . "/sendMessage?" . http_build_query($params));
    }



    /**
     * Получите значение имени пользователя
     */
    public function getUsername()
    {
        return $this->username;
    }


    /**
     * Получите значение chat_id
     */
    public function getChatId()
    {
        return $this->chat_id;
    }


    /**
     * Получите значение first_name
     */
    public function getFirstName()
    {
        return $this->first_name;
    }


    /**
     * Получите значение last_name
     */
    public function getLastName()
    {
        return $this->last_name;
    }


    /**
     * Получите значение date_message
     */
    public function getDateMessage()
    {
        return $this->date_message;
    }


    /**
     * Получите значение text_message
     */
    public function getTextMessage()
    {
        return $this->text_message;
    }


    /**
     * Установите значение UpdateId
     */
    public function setUpdateId($UpdateId): self
    {
        $this->UpdateId = $UpdateId;

        return $this;
    }

    /**
     * Получить значение UpdateId
     */
    public function getUpdateId()
    {
        return $this->UpdateId;
    }
}