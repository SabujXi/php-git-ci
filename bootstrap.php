<?php
//
// check if mod rewrite enabled. At this moment I am assuming that this app will be hosted under apache.
// TODO: in future there will be testing for nginx, etc.
if(!in_array('mod_rewrite', apache_get_modules())){
    // TODO: make it more verbose - add pretty html error page.
    exit("Mod Rewrite not enabled.");
}
// verify mod rewrite from the rewriterule E param
if($_SERVER['UNDER_REWRITE'] !== 'YES'){
    exit('Not confirmed that it is under rewrite.');
}

define('ROOT_PATH', dirname(realpath(__FILE__)));

require_once 'vendor/autoload.php';


use Webmozart\PathUtil\Path;

define('CACHE_DIR', Path::join(ROOT_PATH, '__cache__'));
define('TEMPLATE_CACHE_DIR', Path::join(ROOT_PATH, '__cache__', 'templates'));
define('DATA_DIR', Path::join(ROOT_PATH, '__data__'));
define('CONFIGS_DIR', Path::join(ROOT_PATH, '__configs__'));
define('TMP_DIR', Path::join(ROOT_PATH, '__tmp__'));
define('VIEWS_DIR', Path::join(ROOT_PATH, 'views'));
define('PUBLIC_DIE', Path::join(ROOT_PATH, 'public'));
// create required directories
$dirs = [CACHE_DIR, TEMPLATE_CACHE_DIR, DATA_DIR, CONFIGS_DIR, VIEWS_DIR];
foreach ($dirs as $dir){
    if(!file_exists($dir)){
        mkdir($dir);
    }
}

// Base URL calculation
$_document_root = Path::canonicalize($_SERVER['DOCUMENT_ROOT']);
$_current_dir = Path::canonicalize(realpath(__DIR__));
$_document_root_components = explode('/', $_document_root);
$_current_dir_components = explode('/', $_current_dir);
$_url_base_components = array_slice($_current_dir_components, count($_document_root_components));

define('SITE_SUBDIR', implode('/', $_url_base_components));

if(count($_url_base_components) !== 0){
    define('URL_BASE', '/' . implode('/', $_url_base_components) . '/');
}else{
    define('URL_BASE', '/');
}

use Framework\Application;
$site_config = require_once 'site_config.php';
$app = new Application($site_config);
$GLOBALS['app'] = $app;

require_once 'routes.php';

$app->run();
