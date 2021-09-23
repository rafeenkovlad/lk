<?php
namespace Command;

use Command\Tree_ancet\Ancets;

class See extends Ancets
{
    public $city, $ancet, $ancet_id, $user_img;
    public function ViewCity(string $user_id, string $city)
    {
        $user_ancet = Ancets::where('user_id', $user_id)->take(1)->get();
        $ancet_id = $user_ancet[0]->original['id'];
        $user_img = $user_ancet->find($ancet_id)->img;

        $this->city = $city;
        $this->ancet = $user_ancet;
        $this->ancet_id = $ancet_id;
        $this->user_img =$user_img;

        file_put_contents(__DIR__ . '/message.txt', print_r($city, true));
    }

}