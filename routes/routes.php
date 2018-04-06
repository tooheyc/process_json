<?php

class routes
{
    protected $uri = ['/' => 'initialize.php', '/ajax' => 'change.php', '/setSource'=>'setSource.php', '/info'=>'info.php'];

    public function __construct()
    {
        require './config/config.php';
        $request_uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
        if (array_key_exists($request_uri, $this->uri)) {
            include './controllers/'.$this->uri[$request_uri];
        } else {
            include './controllers/notFound.php';
        }
    }
}
