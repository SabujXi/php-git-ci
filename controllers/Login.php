<?php


namespace Controller;
use \Symfony\Component\HttpFoundation\Request;
use Framework\BaseController;


class Login extends BaseController
{
    public function index(Request $request){
        $file_db = $this->app->file_db();
        if(!$file_db->entity_exists('auth')){
            return $this->app->redirect('auth_setup');
        }
        $auths_entity = $file_db->get_entity('auths');
        return 'TODO - LOgin';
    }

    public function logout(){

    }
}