<?php
namespace Connect;

class token{
    private $token;

    private function setToken()
    {
        return $this->token = "";
    }

    public function get_token()
    {
        return $this->setToken();
    }
}
