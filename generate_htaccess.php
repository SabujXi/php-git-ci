<?php
/**
 * Created by PhpStorm.
 * User: Sabuj
 * Date: 13-Jun-19
 * Time: 4:32 PM
 */

define('ROOT_PATH', realpath(__DIR__));

require 'vendor/autoload.php';

use Framework\HtaccessGenerator;

$generator = new HtaccessGenerator();

$generator->write(null);
