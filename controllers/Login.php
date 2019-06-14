<?php


namespace Controller;
use \Symfony\Component\HttpFoundation\Request;
use Framework\BaseController;


class Login extends BaseController
{
    public function index(Request $request){
        $session = $this->app->start_session();
        if($request->getMethod() === 'POST'){
            $file_db = $this->app->file_db();
            if(!$file_db->entity_exists('auths')){
                return $this->app->redirect('auth_setup');
            }
            $auths_entity = $file_db->get_entity('auths');
            $username = $request->get('username');
            $password = md5($request->get('password'));
//            var_dump($auths_entity, $username, $password);
//            exit();
            if($auths_entity->get('username') === $username && $auths_entity->get('password') === $password){
                $session->set('username', $username);
                $session->set('password', $password);
                $session->getFlashBag()->add('message', "Login for $username successful");
            }
            return $this->app->redirect('auth_test');
        }else{
            $file_db = $this->app->file_db();
            if(!$file_db->entity_exists('auths')){
                return $this->app->redirect('auth_setup');
            }
            if($this->app->get_auth()->is_authentic()){
                return $this->app->redirect('auth_test');
            }
            $message = $session->getFlashBag()->get('message');
            $username = $session->get('username', null);
            return $this->app->templates->render('login.html', ['message'=>$message, 'username'=>$username]);
        }
    }

    public function logout(){
        $session = $this->app->start_session();
        $session->invalidate();
        return $this->app->redirect('login');
    }

    public function auth_test(){
        $auth = $this->app->get_auth();
        return $this->app->templates->render('auth_test.html', ['auth'=>$auth]);
    }
}