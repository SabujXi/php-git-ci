<?php
/**
 * Created by PhpStorm.
 * User: Sabuj
 * Date: 13-Jun-19
 * Time: 4:32 PM
 */

define('ROOT_PATH', realpath(__DIR__));

require 'vendor/autoload.php';

// determine subdir, first from env

$subdir = getenv('UNDER_SUBDIR');
if(!$subdir){
    $config = require 'site_config.php';
    $subdir = $config['subdir'];
}

if(!$subdir){
    $subdir = null;
}

use Framework\HtaccessGenerator;

$generator = new HtaccessGenerator();

$generator->write(null, $subdir);
