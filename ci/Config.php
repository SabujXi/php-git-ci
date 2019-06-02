<?php

namespace CI;

use Webmozart\PathUtil\Path;

class Config
{
    private $config = [];
    public static function get_deploy_config_fn(){
        $config_fn = Path::join(CONFIGS_DIR, 'deploy.json');
        return $config_fn;
    }
    public function __construct() {
        $config_fn = self::get_deploy_config_fn();
        if(file_exists($config_fn)){
            $this->config = json_decode(file_get_contents($config_fn));
        }
    }

    public function get($key, $default=null){
        if(isset($this->config[$key])){
            return $this->config[$key];
        }else{
            return $default;
        }
    }

    public function __get($name) {
        return $this->get($name);
    }
}
