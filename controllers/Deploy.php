<?php


namespace Controller;
use \Symfony\Component\HttpFoundation\Request;
use Framework\BaseController;


class Deploy extends BaseController
{
    public function deploy(Request $request){
        $deploy = new \CI\Deploy($this->app->file_db()->get_entity('deploy'));
        list($pre_command_outputs, $post_command_outputs) = $deploy->run();

        $messages = $this->app->start_session()->getFlashBag()->get('messages');
        return $this->app->templates->render('deploy.html', [
            'messages'=>$messages,
            'pre_command_outputs' => $pre_command_outputs,
            'post_command_outputs' => $post_command_outputs
        ]);
    }

    public function reset_n_deploy(Request $request){
        $deploy = new \CI\Deploy($this->app->file_db()->get_entity('deploy'));
        list($pre_command_outputs, $post_command_outputs) = $deploy->run(true);

        $messages = $this->app->start_session()->getFlashBag()->get('messages');
        return $this->app->templates->render('deploy.html', [
            'messages'=>$messages,
            'pre_command_outputs' => $pre_command_outputs,
            'post_command_outputs' => $post_command_outputs
        ]);
    }
}
