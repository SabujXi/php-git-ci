<?php
/**
 * Created by PhpStorm.
 * User: Sabuj
 * Date: 15-Jun-19
 * Time: 12:04 AM
 */

namespace Framework;
use Webmozart\PathUtil\Path;

class FileDB {
    private $cached_entities = [];
    private $db_dir = null;

    public function __construct($db_dir) {
        $this->db_dir = $db_dir;
    }

    public function make_entity_path($name){
        $path = Path::join($this->db_dir, $name . '.json');
        return $path;
    }

    function get_db_dir(){
        assert(!is_null($this->db_dir));
        return $this->db_dir;
    }

    function create_entity($name, $return_if_exists=false){
        $path = $this->make_entity_path($name);
        $exists = file_exists($path);
        if(!$exists){
            touch($path);
        }else{
            if($return_if_exists){
                throw new \Exception("Entity $name already exists");
            }
        }
        return $this->get_entity($name);
    }

    function update_entity(Entity $entity){
        $name = $entity->name();
        $array = $entity->get_array();
        $path = $this->make_entity_path($name);
        file_put_contents($path, json_encode($array));
    }

    function get_entity($name){
        if(!array_key_exists($name, $this->cached_entities)){
            $path = $this->make_entity_path($name);
            $text = file_get_contents($path);
            $json = json_decode($text, true);
            $entity = new Entity($name, $json);
            $this->cached_entities[$name] = $entity;
        }
        return $this->cached_entities[$name];
    }

    function delete_entity($name){
        if(array_key_exists($this->cached_entities, $name)){
            unset($this->cached_entities[$name]);
        }
        unlink($this->make_entity_path($name));
    }

    function forget_entity(Entity $entity){
        $name = $entity->name();
        if(array_key_exists($this->cached_entities, $name)){
            unset($this->cached_entities[$name]);
        }
    }

    function entity_exists($name){
        return file_exists($this->make_entity_path($name));
    }

    function list_entities(){
        $path = rtrim($this->db_dir, '\/');
        $file_names = glob($path . '/*.json');
        $entity_names = [];
        foreach ($file_names as $file_name){
            $entity_names[] = substr($file_name, 0, strlen($file_name) - strlen('.json'));
        }
        return $entity_names;
    }
}
