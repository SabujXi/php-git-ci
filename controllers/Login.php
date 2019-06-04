<?php


namespace Controller;
use \Symfony\Component\HttpFoundation\Request;
use Framework\BaseController;


class Login extends BaseController
{
    public function index(Request $request){
        $auths_config = $this->app->get_config('auths');
        if(!$auths_config->fileExists()){
            return $this->app->redirect('auth_setup');
        }
        return 'TODO - LOgin';
    }

    public function logout(){

    }

}