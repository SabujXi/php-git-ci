<?php


namespace Controller;
use \Symfony\Component\HttpFoundation\Request;
use Framework\BaseController;


class Deploy extends BaseController
{
    public function index(Request $request){
        $deploy = new \CI\Deploy($this->app->file_db()->get_entity('deploy'));
        $deploy->run();

        $messages = $this->app->start_session()->getFlashBag()->get('messages');
        return $this->app->templates->render('deploy.html', ['messages'=>$messages]);
    }
}
