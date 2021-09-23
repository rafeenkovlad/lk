<?php
namespace Command\Tree_ancet;
use App\Models\Ancet;
use Command\Keyboard;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class Ancets extends Ancet
{
    public function setId($id)
    {
        $ancet = new Ancet(['user_id' => $id]);
        $savedAncet = &$ancet->save();

        $objAncet = Ancet::where('user_id', $id)->take(1)->get();
        $idAncet = $objAncet[0]->original['id'];

        $img = new AncetImg(['user_id' => $idAncet]);
        $savedImg = &$img->save();
        return ($savedAncet && $savedImg)? true : false;
    }

    public function issetName($user_id, $name, $keyboard = Keyboard::class)
    {
        //file_put_contents(__DIR__ . '/message.txt', print_r($name, true));
        $name = json_decode('"'.$name.'"');

        $name = Str::words($name, 5);
        //поиск имени
        $ancet = Ancet::where('user_id', $user_id)->get();
        $issetName = ($ancet[0]->original['user_id'] == $user_id) ? $ancet[0]->original['name'] : false;
        //сохранение нового имени
        $saved = &Ancet::where('user_id', $user_id)->update(['name' => $name]);

        if($saved) return $keyboard::saveName($user_id, $name, $issetName);


        //
    }

    public function issetAbout($user_id, $about, $keyboard = Keyboard::class)
    {

        $about = json_decode('"'.$about.'"');

        $about = Str::words($about, 100);
        //поиск описания
        $ancet = Ancet::where('user_id', $user_id)->get();
        $issetAbout = ($ancet[0]->original['user_id'] == $user_id) ? $ancet[0]->original['about'] : false;
        $issetAbout = Str::words($issetAbout, 5);
        file_put_contents(__DIR__ . '/message.txt', print_r($issetAbout, true));
        //сохранение нового описания
        $saved = &Ancet::where('user_id', $user_id)->update(['about' => $about]);

        if($saved) return $keyboard::saveAbout($user_id, $about, $issetAbout);


        //
    }

    public function issetPrice($user_id, $price, $keyboard = Keyboard::class)
    {
        //file_put_contents(__DIR__ . '/message.txt', print_r($price, true));

        $price = json_decode('"'.$price.'"');

        $price = Str::words($price, 25);
        //поиск описания
        $ancet = Ancet::where('user_id', $user_id)->get();
        $issetPrice = ($ancet[0]->original['user_id'] == $user_id) ? $ancet[0]->original['price'] : false;
        $issetPrice = Str::words($issetPrice, 5);
        //сохранение нового имени
        $saved = &Ancet::where('user_id', $user_id)->update(['price' => $price]);

        if($saved) return $keyboard::savePrice($user_id, $price, $issetPrice);


        //
    }

    public function issetImg($user_id, $img, $keyboard = Keyboard::class)
    {
        //$imgTable = new AncetImg();
        //$userImgObj = $imgTable->exsistImg($user_id);
        if(empty(array_filter($img)) || is_string($img)) return $keyboard::IsImg($user_id);

        //file_put_contents(__DIR__ . '/message.txt', print_r($img, true));
        /*
         * получаем объект анкеты
         */
        $objAncetUser = Ancet::where('user_id', $user_id)->take(1)->get();
        $idImgTable = $objAncetUser[0]->original['id'];

        /*
         * Ищем галерею изображений
         */
        $imgObject = $objAncetUser->find($idImgTable)->img;
        $oldImg = json_decode($imgObject->original['img']);

        /*
         * Записываем json в таблицу img
         */
        $oldCollect = collect($oldImg);
        $pushImgArr = $oldCollect->push($img);
        $count = (int)$oldCollect->count();
        if($count == 10) return $keyboard::ImgFull($user_id, $count);

        $saved = &$imgObject->update(['img'=> json_encode($pushImgArr), 'count_img' => $count]);


        if($saved) return $keyboard::ImgEdit($user_id, $count);




    }

    public function issetContact($user_id, $contact, $keyboard = Keyboard::class)
    {
        //file_put_contents(__DIR__ . '/message.txt', print_r($contact, true));

        $contact = json_decode('"'.$contact.'"');

        $contact = Str::words($contact, 6);
        //поиск контактов
        $ancet = Ancet::where('user_id', $user_id)->get();
        $issetContact = ($ancet[0]->original['user_id'] == $user_id) ? $ancet[0]->original['contact'] : false;
        $issetContact = Str::words($issetContact, 5);
        //сохранение нового контакта
        $saved = &Ancet::where('user_id', $user_id)->update(['contact' => $contact]);

        if($saved) return $keyboard::saveContact($user_id, $contact, $issetContact);


        //
    }


    public function seeElqAlbom($user_id, $keyboard = Keyboard::class)
    {
        //$imgTable = new AncetImg();
        //$userImgObj = $imgTable->exsistImg($user_id);
        /*
         * получаем объект анкеты
         */
        $objAncetUser = Ancet::where('user_id', $user_id)->take(1)->get();
        $idImgTable = $objAncetUser[0]->original['id'];

        /*
         * Ищем галерею изображений
         */
        $imgObject = $objAncetUser->find($idImgTable)->img;
        //file_put_contents(__DIR__ . '/message.txt', print_r($imgObject->original, true));
        return $imgObject->original['img'];
    }

    public function delElqAlbom($user_id)
    {
        /*
         * получаем объект анкеты
         */
        $objAncetUser = Ancet::where('user_id', $user_id)->take(1)->get();
        $idImgTable = $objAncetUser[0]->original['id'];

        /*
         * Ищем галерею изображений
         */
        $imgObject = $objAncetUser->find($idImgTable)->img;
        /*
         * Обнуляем альбом
         */
        return $imgObject->update(['img'=> null, 'count_img' => null]);
    }
}