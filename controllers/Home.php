<?php


namespace Controller;
use Framework\BaseController;

class Home extends BaseController
{
    function index(){
        return $this->app->templates->render('home.html');
    }

    function auth_index(){
        return $this->app->templates->render('home.html');
    }
}
