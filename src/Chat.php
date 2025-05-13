<?php
declare(strict_types=1);
namespace Daniilprusakov\TudaykaBot;


class Chat
{
    private string $username;
    private int $chat_id;
    private string|null $first_name;
    private string|null $last_name;
    private int $date_message;
    private string $text_message;
    private int $UpdateId;

    public function __construct(
        string  $username,
        int     $chat_id,
        ?string $first_name,
        ?string $last_name,
        int     $date_message,
        string  $text_message,
        int     $UpdateId
    )
    {
        $this->username = $username;
        $this->chat_id = $chat_id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->date_message = $date_message;
        $this->text_message = $text_message;
        $this->UpdateId = $UpdateId;
    }

    /**
     * Получите значение имени пользователя
     */
    public function getUsername(): string
    {
        return $this->username;
    }


    /**
     * Получите значение chat_id
     */
    public function getChatId(): int
    {
        return $this->chat_id;
    }


    /**
     * Получите значение first_name
     */
    public function getFirstName(): ?string
    {
        return $this->first_name;
    }


    /**
     * Получите значение last_name
     */
    public function getLastName(): ?string
    {
        return $this->last_name;
    }


    /**
     * Получите значение date_message
     */
    public function getDateMessage(): int
    {
        return $this->date_message;
    }


    /**
     * Получите значение text_message
     */
    public function getTextMessage(): string
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
    public function getUpdateId(): int
    {
        return $this->UpdateId;
    }
}