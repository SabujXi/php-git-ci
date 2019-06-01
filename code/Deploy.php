<?php
/**
 * Created by PhpStorm.
 * User: Sabuj
 * Date: 27-May-19
 * Time: 11:51 PM
 */
namespace CI;

use Webmozart\PathUtil\Path;
use Symfony\Component\Filesystem\Filesystem;
use GitWrapper\GitWrapper;

class Deploy{
    private $ci;
    public function __construct(CI $ci) {
        $this->ci = $ci;
    }

    public function run(){
        $config = $this->ci->get_config();
        $deploy_dir = $this->ci->get_deploy_dir();
        $data_dir = Path::join(ROOT_PATH, '__data__');

        // cleanup deploy dir -- skipping this step as the storage might be there TODO: but find a good solution including igonre pattern.

        // make/cleanup a temporary dir.
        $git_wrapper = new GitWrapper();
        $tmp_dir = Path::join(ROOT_PATH, '__tmp__');
        $filesystem = new Filesystem();

        if($filesystem->exists($tmp_dir)){
            // $filesystem->remove($tmp_dir); -- no, pull.
            $git = $git_wrapper->workingCopy($tmp_dir);
            $git->pull();
        }else{
            $filesystem->mkdir($tmp_dir);
            // clone/checkout git
            $git = $git_wrapper->cloneRepository($config->GIT_REPOSITORY_URL, $tmp_dir);
        }
        $filesystem->mirror($tmp_dir, $deploy_dir);
        $filesystem->mirror($data_dir, $deploy_dir);
        $filesystem->remove(Path::join($deploy_dir, '.git'));
    }
}
