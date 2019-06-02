<?php
/**
 * Created by PhpStorm.
 * User: Sabuj
 * Date: 27-May-19
 * Time: 11:52 PM
 */

namespace CI;

use Symfony\Component\Dotenv\Dotenv;
use Webmozart\PathUtil\Path;


class CI{
    private $config = null;
    private $deploy_path = null;

    public function get_config(){
        if(is_null($this->config)){
            $dotenv_path = Path::join(ROOT_PATH, '.env');
            if(!file_exists($dotenv_path)){
                $dotenv_path = Path::join(ROOT_PATH, '.env-example');
            }
            $dotenv = new Dotenv();
            $dotenv->load($dotenv_path);
            $this->config = new Config($_ENV);
        }
        return $this->config;
    }

    public function get_deploy_dir(){
        if(is_null($this->deploy_path)){
            $deploy_path = $this->config->DEPLOY_DIR;
            if(Path::isRelative($deploy_path)){
                $this->deploy_path = Path::join(ROOT_PATH, $deploy_path);
            }else{
                $this->deploy_path = $deploy_path;
            }
        }
        return $this->deploy_path;
    }
}
