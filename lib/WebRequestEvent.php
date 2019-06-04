<?php


namespace Framework;
use \Symfony\Component\EventDispatcher\Event;


class WebRequestEvent extends Event
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function get_request(){
        return $this->request;
    }

}