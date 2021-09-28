<?php
namespace Connect;
use Connect\token;
use Command\{Interfacebot, Sendmessage, Keyboard, Tree};


class Connect
{
    private static $token, $data;
    public function __construct()
    {
        self::$data = file_get_contents('php://input');
        $token = new token();
        self::$token = $token->get_token();
        //отправка строки для выборки параметров через регулярку.
        new Interfacebot(self::$data );
        //токен для отправки сообщения
        new Sendmessage(self::$token );

    }

    public function parseStr($interface = Interfacebot::class, $send = Sendmessage::class, $keyboard = Keyboard::class)
    {
        /*$response = [
            'chat_id' => '516057640',
            'media' =>json_encode([
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIGa2FK75goBA3GurgxG_pNzaZ17nL4AALstjEbYspYStZhDEn2ouwBAQADAgADcwADIQQ'],
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIGa2FK75goBA3GurgxG_pNzaZ17nL4AALstjEbYspYStZhDEn2ouwBAQADAgADcwADIQQ']
            ])
    ];
        */

        //вырезаем команду /city
        //$interface::cityRegular();
        //вырезаем id юзера
        $id = $interface::idUserRegular();

        //стартовый ответ
        if($interface::cityRegular()):
            $response = $keyboard::buttonStart($id);
            $send::message('sendMessage', $response);
        endif;

        //Команда юзера
        $userCommand = $interface::inputButton($id);
        file_put_contents(__DIR__ . '/message.txt', print_r(self::$data, true));

        //распределение команды
        $response = $interface::inputCommand($userCommand);

        //file_put_contents(__DIR__ . '/message.txt', print_r($userCommand->action , true));

        //подключаемся к дереву
        if($userCommand->action == '/register') $treeRegister = Tree::register($id, $userCommand->city);

        //проверяем существует ли команда
        $action = Tree::getNextCommand($id);
        if($action){
            //формируем дерево анкеты без участия кнопок, вопрос-ответ
            $treeCommand = new Interfacebot(self::$data);
            $response = $treeCommand->inputCommndTree($action, $id);
        }


        /*
         * записываем следующую команду
         */
        $nextCommand = json_decode($response['callback_data'])->action;
        if(isset($nextCommand))Tree::nextCommand($nextCommand, $response['chat_id']);

        /*
         * отправка сообщения в чат юзеру
         */
        $send::message($response['method']??'sendMessage', $response);



        //var_dump($response);


    }


}