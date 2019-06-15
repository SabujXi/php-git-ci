<?php


namespace Controller;
use \Symfony\Component\HttpFoundation\Request;
use Framework\BaseController;


class ExecuteCommand extends BaseController
{
    public function execute(Request $request){
        $session = $this->app->start_session();
        $file_db = $this->app->file_db();
        $deploy_dir = $file_db->get_entity('deploy')->get('deploy_dir');

        if(!$deploy_dir){
            $deploy_dir = TMP_DIR;
        }
        $dirs = [TMP_DIR, $deploy_dir];

        if($request->getMethod() === 'POST'){
            $dir = $request->get('dir');
            $command = $request->get('command');
            if(!$dir || !$command){
                $session->getFlashBag()->add('messages', 'invalid input') ;
                return $this->app->redirect('execute_command');
            }
            if(!in_array($dir, $dirs)){
                $session->getFlashBag()->add('messages', 'Invalid dir') ;
                return $this->app->redirect('execute_command');
            }
            $prev_dir = getcwd();
            chdir($dir);
            exec($command . ' 2>&1', $command_outputs, $return_val);
            $command_outputs = implode("\n", $command_outputs);
            $session->getFlashBag()->add('command', $command);
            $session->getFlashBag()->add('command_outputs', $command_outputs);
            $session->getFlashBag()->add('exit_code', $return_val);
            chdir($prev_dir);
            return $this->app->redirect('execute_command');
        }else{
            $messages = $session->getFlashBag()->get('messages');
            $command_outputs = $session->getFlashBag()->get('command_outputs');
            $exit_code = $session->getFlashBag()->get('exit_code');
            if(count($exit_code) == 0){
                $exit_code = null;
            }else{
                $exit_code = $exit_code[0];
            }
            return $this->app->templates->render('execute_command.html', ['messages'=>$messages, 'dirs'=>$dirs, 'command_outputs'=>$command_outputs, 'exit_code' => $exit_code]);
        }
    }
}
