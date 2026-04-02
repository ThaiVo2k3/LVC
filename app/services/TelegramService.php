<?php

class TelegramService
{
    private string $token;
    private string $chatId;

    public function __construct()
    {
        $this->token  = Env::get('TELEGRAM_BOT_TOKEN');
        $this->chatId = Env::get('TELEGRAM_CHAT_ID');
    }
    public function send(string $message): bool
    {
        if (!$this->token || !$this->chatId) {
            error_log('Telegram ENV missing');
            return false;
        }

        $url = "https://api.telegram.org/bot{$this->token}/sendMessage";

        $payload = [
            'chat_id'    => $this->chatId,
            'text'       => $message,
            'parse_mode' => 'HTML'
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => http_build_query($payload),
            CURLOPT_TIMEOUT        => 5
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response !== false;
    }
}
