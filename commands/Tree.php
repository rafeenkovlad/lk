<?php
namespace Command;
use App\Models\City;
use Command\Tree_ancet\Ancets;

class Tree extends City
{

    public $city, $select, $name, $rayon, $img, $about, $telegram, $price;
    private static $treeRegister;
    public static function register ($id, $city)
    {

        $result_id = City::where('telegram_id', $id)
            ->get();

        if(!isset($result_id[0]->original['telegram_id'])){
            $user = new City(['telegram_id' => $id]);
            $user->city = $city;
            $saved = &$user->save();

            //вставляем id Unsigned в Ancets
            if($saved) {
                $result = new Ancets();
                $result->setId($user->original['telegram_id'], $user->original['city']);
            }
            return $result;
            //вызов дальнейшего действия

        }

        $result = City::where('telegram_id', $id)
            ->update(['city' => $city]);
        Ancets::where('user_id', $id)
            ->update(['city' => $city]);
        return $result;

        //file_put_contents(__DIR__ . '/message.txt', print_r($result_id, true));

    }


    public static function nextCommand($command, $id)
    {
       $fileCommand = fopen(__DIR__ .'/commandtxt/'.$id.'.txt', 'w+');
       fwrite($fileCommand, $command);
       fclose($fileCommand);
    }

    public static function getNextCommand($id)
    {
        return file_get_contents(__DIR__ .'/commandtxt/'.$id.'.txt');
    }
    
    public function setEmptyCommand($id)
    {
        file_put_contents(__DIR__ .'/commandtxt/'.$id.'.txt', print_r(null, true));
    }

    public function delLastbutton()
    {

    }

    /*public static function regularTree($id, $data)
    {
        $name = self::regularName($data);
        $json  = file_get_contents(__DIR__ . '/txt/'.$id.'.txt');
        $json = preg_replace('/(?<={"name":)[a-zA-Z]+(?=,")/ixs', $name, $json);

        $anceta = self::open($id);

        fwrite($anceta, $json);

        fclose($anceta);


    }
    //name
    private static function regularName($data)
    {
        preg_match('/(?<=text":")[a-zA-Zа-яА-Я0-9\s()+_\-?!.,\/\\]{0,30}(?="}})/ixm', $data, $match);
        return $match;
    }*/
}