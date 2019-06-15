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

    public function run($reset_tmp=false){
        $filesystem = new Filesystem();
        $deploy_dir = $this->get_deploy_dir();
        if($reset_tmp){
            $filesystem->remove(TMP_DIR);
        }

        $deploy_entity = $this->deploy_entity;

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
    }
}
