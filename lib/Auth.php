<?php


namespace Framework;


use \CI\Config;

class Auth
{
    private $app;
    private $name;
    private $password;

    private $auths_config;
    private $is_authentic;

    public function __construct(Application $app, $name, $password)
    {
        $this->app = $app;
        $this->name = $name;
        $this->password = $password;
    }

    public function name(){
        $this->name ? $this->is_authentic() : null;
    }

    public function is_authentic(){
        if($this->is_authentic !== null){
            return $this->is_authentic;
        }

        if(is_null($this->auths_config)){
            $this->auths_config = new Config('auths');
        }

        if(!$this->auths_config->fileExists()){
            $this->is_authentic = false;
            return $this->is_authentic;
        }
        foreach ($this->auths_config['auths'] as $auth){
            if($auth['name'] == $this->name && $auth['password'] == md5($this->password)){
                $this->is_authentic = true;
                return $this->is_authentic;
            }
        }
        $this->is_authentic = false;
        return $this->is_authentic;
    }

    public function add_auth($name, $password){  // add a new auth to the auths file.
        if(is_null($this->auths_config)){
            $this->auths_config = $this->app->get_config('auths');
        }

        $arr = $this->auths_config->get('auths');
        $arr[] = [
            'name' => $name,
            'password' => md5($password)
        ];

        $this->auths_config->updateFile();
    }
}
