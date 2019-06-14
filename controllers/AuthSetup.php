<?php


namespace Controller;
use Framework\BaseController;


class AuthSetup extends BaseController
{
    public function index(){
        $file_db = $this->app->file_db();
        if($file_db->entity_exists('auth')){
            return $this->app->redirect('auth');
        }else{
            $auths_entity = $file_db->create_entity('auths');
            // TODO: populate data and update entity with file db.
        }
        return 'Auth Setup - TODO';
    }
}