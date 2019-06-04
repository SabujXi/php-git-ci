<?php

namespace CI;

use Webmozart\PathUtil\Path;

class Config
{
    private $config_name;
    private $config = [];
    private $file_exists = false;

    public static function get_config_fn($config_name){
        $config_fn = Path::join(CONFIGS_DIR, $config_name . '.json');
        return $config_fn;
    }

    public function name(){
        return $this->config_name;
    }

    public function __construct($config_name) {

        $config_fn = self::get_config_fn($config_name);
        if(file_exists($config_fn)){
            $this->config = json_decode(file_get_contents($config_fn));
            $this->file_exists = true;
        }
    }

    public function get($key, $default=null){
        $_keys = explode('.', $key);
        $key = array_pop($_keys);
        $arr = $this->config;
        foreach ($_keys as $_key){
            $arr = $arr[$_key];
        }

        if(isset($arr[$key])){
            return $arr[$key];
        }else{
            return $default;
        }
    }

    public function set($key, $value){
        $_keys = explode('.', $key);
        $key = array_pop($_keys);
        $arr = $this->config;
        foreach ($_keys as $_key){
            $arr = $arr[$_key];
        }

        $arr[$key] = $value;
    }

    public function __get($name) {
        return $this->get($name);
    }

    public function fileExists(){
        return $this->file_exists;
    }

    public function updateFile(){
        $config_fn = self::get_config_fn($this->name());
        return file_put_contents(json_encode($this->config));
    }
}
