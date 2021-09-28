<?php
namespace Command;

use Command\Tree_ancet\Ancets;
use Illuminate\Support\Carbon;

class Interfacebot
{
    private static $data, $id_user, $id_message;

    public function __construct($data)
    {
        self::$data = $data;
    }
//меню команды
    //команда /city
    public static function cityRegular()
    {
        preg_match('/(?<="text":")[\/city]{5}(?=")/ixm', self::$data, $city );
        return (!empty($city))? $city[0]=='/city' : false;
    }
    //id отправителя
    public static function idUserRegular()
    {
        preg_match('/(?<="from":{"id":)[0-9]+(?=,)/ixm', self::$data, $id );
        return $id[0];

    }
    //message id
    public static function idMessageRegular()
    {
        preg_match('/(?<=message_id":)\d+(?=\S)/ixm', self::$data, $idMesage );
        return $idMesage[0];

    }
//проверка на входящую комманду
    //что за команда
    public static function inputButton($id_user, $id_message)
    {
        self::$id_user = $id_user;
        self::$id_message = $id_message;
        $data = json_decode(self::$data);
        //file_put_contents(__DIR__ . '/message.txt', print_r($data->callback_query->data, true));
        //(?<="chat_instance":")([-0-9]+","data":"{\\"action\\":\\")(\/\w+)(?=\\"})
        //preg_match('/(?<="data":"{\\"action\\":\\")[\/a-z]+(?=\\")/ixm', self::$data, $inputButton );
        $data = $data->callback_query->data;
        if(isset($data)){
            return json_decode($data);
        }

    }
    //распрееление команды
    public static function inputCommand($command, $keyboard = Keyboard::class, $see = See::class)
    {
       $allCommand = [
           "/online" => fn($id, $id_message, $city) => $see::OnlineView($id, $id_message),
           "/view_city" => fn($id, $id_message, $city) => $see::ViewCity($id, $id_message, $city, Carbon::now()->getTimestamp()),
           "/d" => fn($id, $id_message, $city, $last_ancet_id) => $see::DelViewNext($id, $id_message, $city, $last_ancet_id), // удаляем кнопку и загружаем следующие
           "/register" => fn($id, $id_message, $city) => $keyboard::register($id, $city),
           "/moscow" => fn($id, $id_message, $city) => $keyboard::buttonSelect($id, $id_message, $city, $cityTitle = 'Москва🌃'),
           "/piterburg" => fn($id, $id_message, $city) => $keyboard::buttonSelect($id, $id_message, $city, $cityTitle = "Санкт-Питербург🌃"),
           "/krasnoyarsk" => fn($id, $id_message, $city) => $keyboard::buttonSelect($id, $id_message, $city, $cityTitle = 'Красноярск🌃'),
           "/krasnodar" => fn($id, $id_message, $city) => $keyboard::buttonSelect($id, $id_message, $city, $cityTitle = 'Краснодар🌃'),
           "/sochi" => fn($id, $id_message, $city) => $keyboard::buttonSelect($id, $id_message, $city, $cityTitle = 'Сочи🌃'),
           "/about_edit" => fn($id, $id_message, $city) => $keyboard::AboutEdit($id),
           "/price_edit" => fn($id, $id_message, $city) => $keyboard::PriceEdit($id),
           "/contact_edit" => fn($id, $id_message, $city) => $keyboard::ContactEdit($id),
           "/see_albom" => fn($id, $id_message, $city) => $keyboard::SeeAlbom($id),
           "/del_albom" => fn($id, $id_message, $city) => $keyboard::DelAlbom($id),
           "/save_albom" => fn($id, $id_message, $city) => $keyboard::SaveAlbom($id),
           "/city" => fn($id, $id_message, $city) => $keyboard::CityOpen($id)



       ];

       if(array_key_exists($command->action, $allCommand)){
           return call_user_func_array($allCommand[$command->action], [self::$id_user, self::$id_message, $command->city, $command->t]);
       }
    }

    //отправляем команду в посторитель дерева
    public function inputCommndTree($action, $id)
    {
        /*
         * вырезаем аргумент для заполнения анкеты
         */
        $arg = Regular::regularCreateAncet(self::$data);

        //file_put_contents(__DIR__ . '/message.txt', print_r($action, true));
        $command = [
            "/name" => fn($ancet, $user_id, $argName) => $ancet->issetName($user_id, $argName),
            "/about" => fn($ancet, $user_id, $argAbout) => $ancet->issetAbout($user_id, $argAbout),
            "/price" => fn($ancet, $user_id, $argPrice) => $ancet->issetPrice($user_id, $argPrice),
            "/img" => fn($ancet, $user_id, $argImg) => $ancet->issetImg($user_id, $argImg),
            "/contact" => fn($ancet, $user_id, $argContact) => $ancet->issetContact($user_id, $argContact)
        ];

        if(array_key_exists($action, $command)) {
            $ancet = new Ancets();
            return call_user_func_array($command[$action], [$ancet, $id, $arg]);
        }
    }

}