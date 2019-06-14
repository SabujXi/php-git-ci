<?php


namespace Framework;

class Auth
{
    private $app;
    private $name;
    private $password;

    public function __construct(Application $app, $name, $password)
    {
        $this->app = $app;
        $this->name = $name;
        $this->password = $password;
    }

    public function name(){
        return $this->name;
    }

    public function is_authentic(){
       if(in_array(null, [$this->name, $this->password])){
           return false;
       }
       if(!$this->name){
           return false;
       }
       if(!$this->password){
           return false;
       }
       return true;
    }
}
