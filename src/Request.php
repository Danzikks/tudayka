<?php
declare(strict_types=1);
namespace Daniilprusakov\TudaykaBot;

$token = $_ENV['BOT_TOKEN'];
class Request
{
    private string $apiBaseUrl;
    public function __construct()
    {
        $this->apiBaseUrl = "https://api.telegram.org/bot";
    }
}