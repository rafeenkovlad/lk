<?php
namespace Command;

use Command\Tree_ancet\Ancets;
use Illuminate\Support\Carbon;

class See extends Ancets
{
    public static $city, $ancet, $ancet_time, $user_img;
    public function ViewCity( int $user_id, int $id_message, $city, $ancet_time, $status = 1, $keyboard = Keyboard::class)
    {
        //преобразуем формат даты
        $ancet_time = Carbon::createFromTimestamp($ancet_time)->toDateTimeString();

        self::$city = $city;
        self::$ancet = $user_ancet;
        self::$user_img =$user_img;

        $user_ancet = self::getAncet($city, $status, $ancet_time);
        if($user_ancet->isEmpty()) $user_ancet = self::getAncet($city, $status = 0, $ancet_time);
        self::$ancet = $user_ancet;
        $collection = self::prepareAncet();

        $last_ancet_time = $collection->map(function($value, $key){
            return $value['updated_at'];
        });

        self::$ancet_time = Carbon::parse($last_ancet_time->last())->getTimestamp();






        $imgObjCollection = $collection->map(function($value, $key)use($user_ancet){

            return $user_ancet->find($value['id'])->img;
        });

        $imgColection = $imgObjCollection->map(function($value, $key){

            return $value->original['img'];
        });

        /*
         * собираем массив с анкетами для ответа
         */
        $response = $collection->map(function($value, $key)use($imgColection, $user_id, $keyboard){

            $text = self::shablon($value);
            $collection = collect(['sendMediaGroup' =>$keyboard::ViewAlbom($user_id, $imgColection[$key])]);
            $collection = $collection->put('sendMessage', $keyboard::ViewAncet($user_id, $text));
            return $collection;

           // return ['sendMediaGroup' => [$keyboard::ViewAlbom($user_id, $imgColection[$key])], 'sendMessage' => [$keyboard::ViewAncet($user_id, $text)]];

        });
        $keyNext = collect(['sendMessage' => $keyboard::ViewNext($user_id, self::$ancet_time, $city)]);
        $response = $response->push($keyNext);

       file_put_contents(__DIR__ . '/message.txt', print_r($response, true));


        return $response;

        //$user_img = $user_ancet->find($ancet_id)->img;

        //$user_img = $user_ancet;
        //$ancet_id = $user_ancet[0]->original['id'];
        //$user_img = $user_ancet->find($ancet_id)->img;




    }

    private static function getAncet($city, $status, $ancet_time)
    {
        return Ancets::select('id', 'city', 'name', 'about', 'price', 'contact', 'status', 'updated_at')
            ->take(2)
            ->where('city', $city)
            ->where('status', $status)
            ->orderBy('updated_at', 'DESC')
            ->where('updated_at', '<', $ancet_time)
            ->get();
    }

    private static function prepareAncet()
    {
        $collection = collect(self::$ancet);
        $user = $collection->map(function ($value, $key) {
             return $value->original;
        });
        return $user;
    }

    private static function shablon($ancet)
    {
        $status = "⚪";
        if($ancet['status'] == 1) $status = "🟢";
        return $text = "
*{$ancet['name']}* `{$status}*{$ancet['city']}*`
            *Описание анкеты:* 
{$ancet['about']}
            *Вознаграждение:* 
{$ancet['price']}
            *Контакты:* 
{$ancet['contact']}
        ";
    }

    public function DelViewNext($user_id, $id_message, $city, $last_ancet_id, $keyboard = Keyboard::class)
    {

        /*
         * используем предыдущую функцию для показа анкет
         */
        /*
         * удаляем кнопку далее
         */
        $collection = collect(self::ViewCity($user_id, $id_message, $city, $last_ancet_id));
        $collectionButton = collect(['editMessageReplyMarkup' => $keyboard::DelKeyNext($user_id, $id_message)]);
        $collection = $collection->push($collectionButton);

        return $collection;


    }

    public function OnlineView($user_id, $id_message, $keyboard = Keyboard::class)
    {
        /*
         * проверяем статус анкеты и временной отрезок с даты поднятия
         */
        $status = Ancets::where('user_id', $user_id)->update(['status' => '1']);
        //file_put_contents(__DIR__ . '/message.txt', print_r($timeNow, true));
        if($status) return $keyboard::OnlineUp($user_id,$id_message);
    }
}