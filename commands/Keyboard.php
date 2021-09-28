<?php
namespace Command;

class Keyboard
{
    public static function buttonStart($user_id)
    {
        /*
         * записываем пустую команду
         */
        Tree::setEmptyCommand($user_id);


        $keyboard = array(
            array(
                array('text'=>'Москва','callback_data'=>'{"action":"/moscow", "city":"MSK"}')
            ),
            array(
                array('text'=>'Санкт-Питербург','callback_data'=>'{"action":"/piterburg", "city":"St.Petersburg"}')
            ),
            array(
                array('text'=>'Красноярск','callback_data'=>'{"action":"/krasnoyarsk", "city":"KRS"}')
            ),
            array(
                array('text'=>'Краснодар','callback_data'=>'{"action":"/krasnodar", "city":"KRD"}')
            ),
            array(
                array('text'=>'Сочи','callback_data'=>'{"action":"/sochi", "city":"Sochi"}')
            )
        );

        $reply_markup = json_encode([
            "inline_keyboard"=>$keyboard,
            "resize_keyboard"=>true
        ]);

        $response = array(
            'chat_id' => $user_id,
            'text'=>'Добро пожаловать `@EscortBarGrammBot`, здесь размещены эскорт услуги💦🌶️🍓, дальнейшие действия предпологают, что ваш возраст старше 18+!',
            'reply_markup'=>$reply_markup
        );

        /*
         * существкет ли анкета
         */

        $collection = collect([collect(['sendMessage' => $response])]);
        $response = collect(['sendMessage' =>Tree_ancet\Ancets::statusAncet($user_id)]);
        $collection = $collection->push($response);
        return $collection;


    }

    public static function Online($user_id)
    {

        $keyboard = array(
            array(
                array('text'=>'Я онлайн','callback_data'=>'{"action":"/online"}')
            )
        );

        $reply_markup = json_encode([
            "inline_keyboard"=>$keyboard,
            "resize_keyboard"=>true
        ]);

        return $response = array(
            'chat_id' => $user_id,
            'text'=>'Поднимите анкету кнопкой *Я онлайн*. Поднимать анкету можно каждый час.',
            'reply_markup'=>$reply_markup
        );

    }

    public static function buttonSelect($user_id, $message_id, $cityElq, $city)
    {
        /*
         * записываем пустую команду
         */
        Tree::setEmptyCommand($user_id);

        $keyboard = array(
            array(
                array('text'=>'Добавить анкету','callback_data'=>'{"action":"/register", "city": "'.$cityElq.'"}'),
                array('text'=>'Смотреть','callback_data'=>'{"action":"/view_city", "city": "'.$cityElq.'"}')
            )

        );

        $reply_markup = json_encode([
            "inline_keyboard"=>$keyboard,
            "resize_keyboard"=>true
        ]);

        $response = array(
            'chat_id' => $user_id,
            'text'=>"{$city}:",
            'disable_web_page_preview' => false,
            'reply_markup'=>$reply_markup
        );


        $collection = collect([collect(['editMessageReplyMarkup' => self::DelKeyNext($user_id, $message_id)])]);
        $response = collect(['sendMessage' => $response]);
        $collection = $collection->push($response);
        return $collection;



    }

    public function register($user_id, $city)
    {
        /*
         * записываем пустую команду
         */
        Tree::setEmptyCommand($user_id);

        return $response = array(
            'chat_id' => $user_id,
            'text'=>"Ваш город {$city}. Отправьте в ответном письме ваше имя",
            'callback_data'=>'{"action":"/name"}',
            'disable_web_page_preview' => false
        );
    }

    /*
     * Редактирование названия анкеты
     */
    public function saveName($user_id, $newName, $oldName)
    {
        $text = (is_null($oldName))? "Отлично, ваше имя *{$newName}*, отправьте в ответном письме описание анкеты" : "Отлично, вы изменили имя с *{$oldName}* на *{$newName}*, отправьте в ответном письме описание анкеты";
        return $response = array(
            'chat_id' => $user_id,
            'text'=>$text,
            'callback_data'=>'{"action":"/about"}',
            'disable_web_page_preview' => false
        );
    }

    /*
     * Редактирование описания
     */
    public function saveAbout($user_id, $about, $oldAbout)
    {
        $keyboard = array(
            array(
                array('text'=>'Отредактировать описание','callback_data'=>'{"action":"/about_edit"}')
            )

        );

        $reply_markup = json_encode([
            "inline_keyboard"=>$keyboard,
            "resize_keyboard"=>true
        ]);

        $text = (is_null($oldAbout))? "Отлично, ваше описание готово, отправьте в ответном письме описание о формировании вознаграждения" : "Ваше старое описание: \n _{$oldAbout}_ \nбыло изменено, отправьте в ответном письме описание о формировании вознаграждения";
        return $response = array(
            'chat_id' => $user_id,
            'text'=>$text,
            'callback_data'=>'{"action":"/price"}',
            'disable_web_page_preview' => false,
            'reply_markup'=>$reply_markup
        );
    }


    /*
     * Ответное письмо на редактирование описания
     */
    public function AboutEdit($user_id)
    {
        /*
         * записываем пустую команду
         */
        Tree::setEmptyCommand($user_id);

        $text = "Хорошо, отправьте в ответном письме отредактированное описание";
        return $response = array(
            'chat_id' => $user_id,
            'text'=>$text,
            'callback_data'=>'{"action":"/about"}',
            'disable_web_page_preview' => false,
        );
    }

    /*
     * Ответное письмо на редактирование описания
     */
    public function savePrice($user_id, $price, $oldPrice)
    {
        $keyboard = array(
            array(
                array('text'=>'Отредактировать вознаграждение','callback_data'=>'{"action":"/price_edit"}')
            )

        );

        $reply_markup = json_encode([
            "inline_keyboard"=>$keyboard,
            "resize_keyboard"=>true
        ]);

        $text = (is_null($oldPrice))? "Отлично, вознаграждение выставлено, отправьте в ответных сообщениях фотографии, возможно опубликовать до 10 фотографий (*Можно отправить альбомом*)" : "Предыдущее описание о вознаграждении: \n _{$oldPrice}_ \nотредактированно,  отправьте в ответных сообщениях фотографии, возможно опубликовать до 10 фотографий (*Можно отправить альбомом*)";
        return $response = array(
            'chat_id' => $user_id,
            'text'=>$text,
            'callback_data'=>'{"action":"/img"}',
            'disable_web_page_preview' => false,
            'reply_markup'=>$reply_markup
        );
    }

    /*
    * Ответное письмо на редактирование цен
    */
    public function PriceEdit($user_id)
    {
        /*
         * записываем пустую команду
         */
        Tree::setEmptyCommand($user_id);

        $text = "Хорошо, отправьте в ответном сообщении отредактированное описание вознаграждения";
        return $response = array(
            'chat_id' => $user_id,
            'text'=>$text,
            'callback_data'=>'{"action":"/price"}',
            'disable_web_page_preview' => false
        );
    }


    /*
    * Проверка на изображение
    */
    public function IsImg($user_id)
    {
        $keyboard = array(
            array(
                array('text'=>'Смотреть коллаж','callback_data'=>'{"action":"/see_albom"}')
            ),
            array(
                array('text'=>'Сохранить альбом','callback_data'=>'{"action":"/save_albom"}')
            ),
            array(
                array('text'=>'Удалить альбом','callback_data'=>'{"action":"/del_albom"}')
            )


        );

        $reply_markup = json_encode([
            "inline_keyboard"=>$keyboard,
            "resize_keyboard"=>true
        ]);
        $text = "В альбом можно добавить только *фото* или *видео*!";
        return $response = array(
            'chat_id' => $user_id,
            'text'=>$text,
            'disable_web_page_preview' => false,
            'reply_markup'=>$reply_markup
        );
    }

    /*
    * Ответное письмо о добавлении изображения
    */
    public function ImgEdit($user_id, $count)
    {
        /*
         * записываем пустую команду
         */
        //Tree::setEmptyCommand($user_id);

        $keyboard = array(
            array(
                array('text'=>'Смотреть коллаж','callback_data'=>'{"action":"/see_albom"}')
            ),
            array(
                array('text'=>'Сохранить альбом','callback_data'=>'{"action":"/save_albom"}')
            ),
            array(
                array('text'=>'Удалить альбом','callback_data'=>'{"action":"/del_albom"}')
            )


        );

        $reply_markup = json_encode([
            "inline_keyboard"=>$keyboard,
            "resize_keyboard"=>true
        ]);
        $count =10 - $count;
        $text = "Отлично, еще можно добавить *{$count}* изображений!";
        return $response = array(
            'chat_id' => $user_id,
            'text'=>$text,
            'disable_web_page_preview' => false,
            'reply_markup'=>$reply_markup
        );
    }

    /*
     * просмотр коллажа
     */

    public function SeeAlbom($user_id)
    {
        /*
         * записываем пустую команду
         */
        Tree::setEmptyCommand($user_id);

        /*
         * получаем json для отправки media
         */
        $media = Tree_ancet\Ancets::seeElqAlbom($user_id);
        //file_put_contents(__DIR__ . '/message.txt', print_r($media, true));
        return $response = array(
            'chat_id' => $user_id,
            'media'=>$media,
            'method'=> 'sendMediaGroup',
            'callback_data'=>'{"action":"/img"}',
            'disable_web_page_preview' => false
        );
    }

    /*
     * удаление альбома
     */
    public function DelAlbom($user_id)
    {

        /*
         * записываем пустую команду
         */
        Tree::setEmptyCommand($user_id);

        /*
         * Удаляем альбом
         */
        Tree_ancet\Ancets::delElqAlbom($user_id);

        $text = "Альбом удален, добавьте изображения заново.";
        return $response = array(
            'chat_id' => $user_id,
            'text'=>$text,
            'callback_data'=>'{"action":"/img"}',
            'disable_web_page_preview' => false
        );
    }

    /*
     * Альбом полон и перейти к записи контактов
     */
    public function ImgFull($user_id, $count)
    {

        /*
         * записываем пустую команду
         */
        Tree::setEmptyCommand($user_id);


        $keyboard = array(
            array(
                array('text'=>'Смотреть коллаж','callback_data'=>'{"action":"/see_albom"}')
            ),
            array(
                array('text'=>'Сохранить альбом','callback_data'=>'{"action":"/save_albom"}')
            ),
            array(
                array('text'=>'Удалить альбом','callback_data'=>'{"action":"/del_albom"}')
            )


        );

        $reply_markup = json_encode([
            "inline_keyboard"=>$keyboard,
            "resize_keyboard"=>true
        ]);

        /*
         * переходим к след шагу
         */
        $text = "*Альбом* полон, в коллаже *{$count}* фотографий.";
        return $response = array(
            'chat_id' => $user_id,
            'text'=>$text,
            'callback_data'=>'{"action":"/img"}',
            'disable_web_page_preview' => false,
            'reply_markup'=>$reply_markup
        );
    }

    /*
     * Сохранить альбом и перейти к записи контактов
     */
    public function SaveAlbom($user_id)
    {

        /*
         * записываем пустую команду
         */
        Tree::setEmptyCommand($user_id);

        /*
         * переходим к след шагу
         */
        $text = "*Альбом* сохранен. Почти готово, осталось указать ваши *контакты*, отправьте их в ответном письме";
        return $response = array(
            'chat_id' => $user_id,
            'text'=>$text,
            'callback_data'=>'{"action":"/contact"}',
            'disable_web_page_preview' => false
        );
    }

    /*
     * Ответное письмо на редактирование контактов или закончить анкету
     */
    public function saveContact($user_id, $contact, $oldContact)
    {
        $keyboard = array(
            array(
                array('text'=>'Отредактировать контакты','callback_data'=>'{"action":"/contact_edit"}')
            ),
            array(
                array('text'=>'В начало','callback_data'=>'{"action":"/city"}')
            )

        );

        $reply_markup = json_encode([
            "inline_keyboard"=>$keyboard,
            "resize_keyboard"=>true
        ]);

        $text = (is_null($oldContact))? "Отлично, контакты внесены, 🌶️*Анкета* размещена" : "Старые контакты: \n _{$oldContact}_ \nотредактированы, 🌶️*Анкета* размещена";
        return $response = array(
            'chat_id' => $user_id,
            'text'=>$text,
            'callback_data'=>'{"action":""}',
            'disable_web_page_preview' => false,
            'reply_markup'=>$reply_markup
        );
    }

    /*
    * Ответное письмо на редактирование контактов
    */
    public function ContactEdit($user_id)
    {
        /*
         * записываем пустую команду
         */
        Tree::setEmptyCommand($user_id);

        $text = "Хорошо, отправьте в ответном сообщении отредактированные *контакты*";
        return $response = array(
            'chat_id' => $user_id,
            'text'=>$text,
            'callback_data'=>'{"action":"/contact"}',
            'disable_web_page_preview' => false
        );
    }

    /*
    * В начало
    */
    public function CityOpen($user_id)
    {
        /*
         * записываем пустую команду
         */
        Tree::setEmptyCommand($user_id);

        $text = "для выхода в начало /city";
        return $response = array(
            'chat_id' => $user_id,
            'text'=>$text,
            'disable_web_page_preview' => false
        );
    }

    //------------------------------------------------//
    /*
     * работа с просмотром анкет
     */
    //-----------------------------------------------//

    public function ViewAncet($user_id, $ancet)
    {
        /*
         * Показать текст анкеты
         */
        Tree::setEmptyCommand($user_id);

        return $response = array(
            'chat_id' => $user_id,
            'text'=>$ancet,
            'disable_web_page_preview' => false
        );
    }

    /*
    * показ коллажа анкеты
    */

    public function ViewAlbom($user_id, $albom)
    {

        //file_put_contents(__DIR__ . '/message.txt', print_r($media, true));
        return $response = array(
            'chat_id' => $user_id,
            'media'=>$albom,
            'method'=> 'sendMediaGroup',
            'disable_web_page_preview' => false
        );
    }

    public function ViewNext($user_id, $ancet_time, $city)
    {
        $keyboard = array(
            array(
                array('text'=>'Далее','callback_data'=>'{"action":"/d", "city": "'.$city.'", "t":"'.$ancet_time.'"}')
            )
        );

        $reply_markup = json_encode([
            "inline_keyboard"=>$keyboard,
            "resize_keyboard"=>true
        ]);

        $response = array(
            'chat_id' => $user_id,
            'text'=> 'Показать следующие анкеты',
            'reply_markup'=>$reply_markup,

        );
        return $response;
    }

    public function DelKeyNext($user_id, $message_id)
    {

        $response = array(
            'chat_id' => $user_id,
            'message_id'=> $message_id,
            'method' => 'editMessageReplyMarkup'
        );
        return $response;
    }
    /*
     * Успех поднятия
     */
    public function OnlineUp($user_id, $message_id)
    {
        $response = array(
            'chat_id' => $user_id,
            'text'=> 'Анкета поднята!',
            'disable_web_page_preview' => false
        );

        $collection = collect([collect(['editMessageReplyMarkup' => self::DelKeyNext($user_id, $message_id)])]);
        $response = collect(['sendMessage' => $response]);
        $collection = $collection->push($response);
        return $collection;

    }
}