<?php


namespace Controller;
use Framework\BaseController;
use \Symfony\Component\HttpFoundation\Request;


class AuthSetup extends BaseController
{
    public function index(Request $request){
        if($request->getMethod() === 'POST'){
            $file_db = $this->app->file_db();
            if(!$file_db->entity_exists('auths')){
                $auths_entity = $file_db->create_entity('auths');
                $username = $request->get('username');
                $password = md5($request->get('password'));
                $auths_entity->set('username', $username);
                $auths_entity->set('password', $password);
                $file_db->update_entity($auths_entity);
            }
            return $this->app->redirect('login');
        }elseif ($request->getMethod() === 'GET'){
            if($this->app->get_auth()->is_authentic()){
                return $this->app->redirect('auth_test');
            }
            return $this->app->templates->render('auth_setup.html');
        }else{
            throw new \Exception("Invalid method");
        }
    }
}