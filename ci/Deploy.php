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

    public function run(){
        $deploy_entity = $this->deploy_entity;
        $deploy_dir = $this->get_deploy_dir();

        $data_dir = Path::join(ROOT_PATH, '__data__');

        // cleanup deploy dir -- skipping this step as the storage might be there TODO: but find a good solution including igonre pattern.

        // make/cleanup a temporary dir.
        $git_wrapper = new GitWrapper();
        $tmp_dir = Path::join(ROOT_PATH, '__tmp__');
        $filesystem = new Filesystem();

        if($filesystem->exists($tmp_dir)){
            // $filesystem->remove($tmp_dir); -- no, pull.
            if($filesystem->exists($tmp_dir . '/.git')){
                $git = $git_wrapper->workingCopy($tmp_dir);
                $git->pull();
            }else{
                $git = $git_wrapper->workingCopy($tmp_dir);
                $git->cloneRepository($deploy_entity->get('git_repository_url', $tmp_dir));
            }
        }else{
            $filesystem->mkdir($tmp_dir);
            // clone/checkout git
            $git = $git_wrapper->workingCopy($tmp_dir);
            $git->cloneRepository($deploy_entity->get('git_repository_url', $tmp_dir));
        }
        $filesystem->mirror($tmp_dir, $deploy_dir);
        $filesystem->mirror($data_dir, $deploy_dir);
        $filesystem->remove(Path::join($deploy_dir, '.git'));
    }
}
