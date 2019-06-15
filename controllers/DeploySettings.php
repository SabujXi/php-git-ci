<?php


namespace Controller;
use \Symfony\Component\HttpFoundation\Request;
use Framework\BaseController;
use Webmozart\PathUtil\Path;
use Symfony\Component\Filesystem\Filesystem;

class DeploySettings extends BaseController
{
    public function index(Request $request){
        $session = $this->app->start_session();
        $file_db = $this->app->file_db();
        if($request->getMethod() === 'POST'){
            $deploy_entity = $file_db->create_entity('deploy', true);
            $prev_git_repo_url = $deploy_entity->get('git_repository_url');

            $pre_commands = $request->get('pre_commands');
            $deploy_dir = $request->get('deploy_dir');
            $git_repository_url = $request->get('git_repository_url');
            $post_commands = $request->get('post_commands');

            $deploy_entity->set('pre_commands', $pre_commands);
            $deploy_entity->set('deploy_dir', $deploy_dir);
            $deploy_entity->set('git_repository_url', $git_repository_url);
            $deploy_entity->set('post_commands', $post_commands);

            $file_db->update_entity($deploy_entity);

            // clear temp dir.
            if($git_repository_url !== $prev_git_repo_url){
                $filesystem = new Filesystem();
                $tmp_dir = Path::join(ROOT_PATH, '__tmp__');
                $filesystem->remove($tmp_dir);
                $session->getFlashBag()->add('messages', "Cleared temporary directory due to git url change.");
            }

            $session->getFlashBag()->add('messages', "Deploy settings saved");
            return $this->app->redirect('deploy_settings');
        }else{
            $deploy_entity = $file_db->create_entity('deploy', true);
            $messages = $session->getFlashBag()->get('messages');
            return $this->app->templates->render('deploy_settings.html', ['messages'=>$messages, 'deploy_entity' => $deploy_entity]);
        }
    }
}