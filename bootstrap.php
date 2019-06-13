<?php

// check if mod rewrite enabled. At this moment I am assuming that this app will be hosted under apache.
// TODO: in future there will be testing for nginx, etc.
if(!in_array('mod_rewrite', apache_get_modules())){
    // TODO: make it more verbose - add pretty html error page.
    exit("Mod Rewrite not enabled.");
}
// verify mod rewrite from the rewriterule E param
if($_SERVER['REDIRECT_UNDER_REWRITE'] !== 'YES'){
    exit('Not confirmed that it is under rewrite.');
}

define('ROOT_PATH', dirname(realpath(__FILE__)));

require_once 'vendor/autoload.php';


use Webmozart\PathUtil\Path;

define('CACHE_DIR', Path::join(ROOT_PATH, 'cache'));
define('TEMPLATE_CACHE_DIR', Path::join(ROOT_PATH, 'cache', 'templates'));
define('DATA_DIR', Path::join(ROOT_PATH, 'data'));
define('CONFIGS_DIR', Path::join(ROOT_PATH, 'configs'));
define('VIEWS_DIR', Path::join(ROOT_PATH, 'views'));
define('PUBLIC_DIE', Path::join(ROOT_PATH, 'public'));
// create required directories
$dirs = [CACHE_DIR, TEMPLATE_CACHE_DIR, DATA_DIR, CONFIGS_DIR, VIEWS_DIR];
foreach ($dirs as $dir){
    if(!file_exists($dir)){
        mkdir($dir);
    }
}


use Framework\Application;
$app = new Application();
$GLOBALS['app'] = $app;

require_once 'routes.php';

$app->run();
