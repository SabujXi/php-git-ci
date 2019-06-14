<?php

namespace Framework;

class Entity
{
    private $entity_name;
    private $entity = [];

    public function name(){
        return $this->entity_name;
    }

    public function get_array(){
        return $this->entity;
    }

    public function __construct($entity_name, $json_array) {
        $this->entity_name = $entity_name;
        $this->entity = $json_array;
    }

    public function get($key, $default=null){
        $_keys = explode('.', $key);
        $key = array_pop($_keys);
        $arr = $this->entity;
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
        $arr = $this->entity;
        foreach ($_keys as $_key){
            $arr = $arr[$_key];
        }

        $arr[$key] = $value;
    }

    public function __get($name) {
        return $this->get($name);
    }
}
