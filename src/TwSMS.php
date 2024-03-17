<?php

namespace NotificationChannels\TwSMS;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class TwSMS
{
    public const API_URL = 'https://api.twsms.com';

    public $username;

    public $password;

    /**
     * @param  string  $username  會員帳號
     * @param  string  $password  會員密碼
     */
    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * 發送簡訊
     *
     * @param  string  $phone  接收簡訊的手機號碼，例如大陸: 8613681912700
     * @param  string  $message  簡訊內容
     * @param  array  $options  可選參數：
     * @return string 回傳服務器的響應內容
     *
     * @throws GuzzleException 如果 HTTP 請求失敗
     */
    public function sendSMS(string $phone, string $message, array $options = []): string
    {
        $params = array_merge([
            'username' => $this->username,
            'password' => $this->password,
            'mobile' => $phone,
            'message' => urlencode($message),
        ], $options);

        $response = $this->httpClient()->get(self::API_URL.'/json/sms_send.php', [
            'query' => $params,
        ]);

        return $response->getBody()->getContents();
    }

    private function httpClient(): Client
    {
        return new Client([
            'connect_timeout' => 20,
        ]);
    }
}
