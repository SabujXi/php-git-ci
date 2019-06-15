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

    private function split_key($key){
        $_keys = explode('.', $key);
        $final_key = array_pop($_keys);
        $keys = new \stdClass();
        $keys->final_key = $final_key;
        $keys->keys = $_keys;
        return $keys;
    }

    private function &get_array_by_key($keys){
        $arr = &$this->entity;
        foreach ($keys as $key){
            $arr = &$arr[$key];
        }
        assert(is_array($arr));
        return $arr;
    }

    public function get($key, $default=null){
        $keys = $this->split_key($key);
        $arr = &$this->get_array_by_key($keys->keys);
        if(isset($arr[$keys->final_key])){
            return $arr[$keys->final_key];
        }else{
            return $default;
        }
    }

    public function set($key, $value){
        $keys = $this->split_key($key);
        $arr = &$this->get_array_by_key($keys->keys);
        $arr[$keys->final_key] = $value;
    }

    public function append($key, $value){
        $keys = $this->split_key($key);
        $arr = &$this->get_array_by_key($keys->keys);
        $arr[] = $value;
    }

    public function clear($key){
        $keys = $this->split_key($key);
        $arr = &$this->get_array_by_key($keys->keys);
        $arr = [];
    }

    public function __get($name) {
        return $this->get($name);
    }
}
