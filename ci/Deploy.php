<?php
/**
 * Created by PhpStorm.
 * User: Sabuj
 * Date: 27-May-19
 * Time: 11:51 PM
 */
namespace CI;

use Framework\Entity;
use Webmozart\PathUtil\Path;
use Symfony\Component\Filesystem\Filesystem;
use GitWrapper\GitWrapper;

class Deploy{
    private $deploy_entity;

    public function __construct(Entity $deploy_entity) {
        $this->deploy_entity = $deploy_entity;
    }

    public function get_deploy_dir(){
        $deploy_dir = $this->deploy_entity->get('deploy_dir');
        $deploy_path = $deploy_dir;

        if(Path::isRelative($deploy_dir)){
            $deploy_path = Path::join(ROOT_PATH, $deploy_dir);
        }
        return $deploy_path;
    }

    private function run_commands($commands_text, $deploy_dir){
        $prev_dir = getcwd();
        chdir($deploy_dir);
        $commands = $commands_text;
        $commands_array = [];
        $command_outputs = [];
        if($commands){
            foreach (preg_split ('/\n|\r\n/', $commands) as $line){
                $line = trim($line);
                if($line){
                    $commands_array[] = $line;
                }
            }

            foreach ($commands_array as $command){
                exec($command . ' 2>&1', $output, $ret_value);
                $output = implode("\n", $output);
                $obj = new \stdClass();
                $obj->exit_code = $ret_value;
                $obj->output = $output;
                $obj->command = $command;
                $command_outputs[] = $obj;
            }
        }
        chdir($prev_dir);
        return $command_outputs;
    }

    public function run($reset_tmp=false){
        $filesystem = new Filesystem();
        $deploy_dir = $this->get_deploy_dir();
        $deploy_entity = $this->deploy_entity;

        // pre commands
        $commands = $deploy_entity->get('pre_commands');
        $pre_command_outputs = $this->run_commands($commands, $deploy_dir);
        // < pre commands

        if($reset_tmp){
            $filesystem->remove(TMP_DIR);
        }


        // cleanup deploy dir -- skipping this step as the storage might be there TODO: but find a good solution including igonre pattern.

        // make/cleanup a temporary dir.
        $git_wrapper = new GitWrapper();

        if($filesystem->exists(TMP_DIR)){
            // $filesystem->remove($tmp_dir); -- no, pull.
            if($filesystem->exists(TMP_DIR . '/.git')){
                $git = $git_wrapper->workingCopy(TMP_DIR);
                $git->pull();
            }else{
                $git = $git_wrapper->workingCopy(TMP_DIR);
                $git->cloneRepository($deploy_entity->get('git_repository_url', TMP_DIR));
            }
        }else{
            $filesystem->mkdir(TMP_DIR);
            // clone/checkout git
            $git = $git_wrapper->workingCopy(TMP_DIR);
            $git->cloneRepository($deploy_entity->get('git_repository_url', TMP_DIR));
        }
        $filesystem->mirror(TMP_DIR, $deploy_dir);
        $filesystem->mirror(DATA_DIR, $deploy_dir);
        $filesystem->remove(Path::join($deploy_dir, '.git'));

        // post commands
        $commands = $deploy_entity->get('post_commands');
        $post_command_outputs = $this->run_commands($commands, $deploy_dir);
        // < post commands

        return [$pre_command_outputs, $post_command_outputs];
    }
}
