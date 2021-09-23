<?php
namespace Command;

class Interfacebot
{
    public static function cityRegular()
    {
        return '/(?<="text":")[\/city]{5}(?=")/ixm';
    }

    public function cityObj()
    {
        
    }

}