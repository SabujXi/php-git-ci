<?php
/**
 * Created by PhpStorm.
 * User: Sabuj
 * Date: 28-May-19
 * Time: 12:29 AM
 */

define('ROOT_PATH', dirname(realpath(__FILE__)));

require_once 'vendor/autoload.php';

function main(){
    $ci = new SabujXiCI\CI();
    $deploy = new SabujXiCI\Deploy($ci);
    $deploy->run();
}
main();