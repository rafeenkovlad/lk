<?php
namespace Command;

class Regular
{
    public static function regularCreateAncet($data)
    {
        /*
         * выборка текста
         */
        preg_match('/(?<=text":")[\S\s]+?(?=")/ixm', $data, $match);

        /*
         * выборка фото и видео
         */
        if(is_null($match[0])){
            preg_match('/(?<=")(photo|video)\S*?file_id":"[\S]+"file_id":"([\S]+?)(?=")/ixm', $data, $match);
            $match[0] = ['type' => $match[1], 'media' => $match[2]];
        }

        return $match[0];
        //file_put_contents(__DIR__ . '/message.txt', print_r($match, true));
    }
}