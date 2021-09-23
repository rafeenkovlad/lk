<?php
namespace Command\Tree_ancet;
use App\Models\Img;
use Command\Keyboard;

class AncetImg extends Img
{
    public function exsistImg($id)
    {
        return Img::where('user_id', $id)->get();
    }
}