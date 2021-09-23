<?php
namespace Connect;
use Connect\token;
use Command\Interfacebot;

class Connect
{
    private static $token, $data;
    public function __construct()
    {
        self::$data = file_get_contents('php://input');
        $token = new token();
        self::$token = $token->get_token();
    }

    public function parseStr($interface = Interfacebot::class)
    {

        preg_match($interface::cityRegular(), self::$data, $city );
        var_dump(self::$token);
        file_put_contents(__DIR__ . '/message.txt', print_r($city, true));
    }


}