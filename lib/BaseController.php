<?php


namespace Framework;


class BaseController
{
    protected $app = null;
    public function __construct(Application $app)
    {
        $this->app = $app;
    }
}
