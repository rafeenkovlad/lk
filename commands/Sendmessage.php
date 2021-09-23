<?php
namespace Command;

class Sendmessage
{
    private static $token;
    public function __construct($token)
    {
        self::$token = $token;
    }

    public static function message($method,$response)
    {
        $ch = curl_init('https://api.telegram.org/bot' . self::$token . '/'.$method.'?parse_mode=markdown');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_exec($ch);
        curl_close($ch);
    }
}