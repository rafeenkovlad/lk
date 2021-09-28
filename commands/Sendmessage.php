<?php
namespace Command;

class Sendmessage extends See
{
    private static $token;
    public function __construct($token)
    {
        self::$token = $token;
    }

    public static function message($method,$response)
    {

        if(is_a($response, 'Illuminate\Support\Collection')){

            $response->each(function($value){


                foreach ($value as $method => $message)
                {
                    //file_put_contents(__DIR__ . '/message.txt', print_r($message, true));
                    self::send($message, $method);
                }

            });
        }

        self::send($response, $method);
    }

    private static function send($response, $method)
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