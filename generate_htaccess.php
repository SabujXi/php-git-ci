<?php
/**
 * Created by PhpStorm.
 * User: Sabuj
 * Date: 13-Jun-19
 * Time: 4:32 PM
 */

require_once 'vendor/autoload.php';

require_once '_bootstrap.php';
// determine subdir, first from env

$subdir = SITE_SUBDIR;

if(!$subdir){
    $subdir = null;
}

use Framework\HtaccessGenerator;

$generator = new HtaccessGenerator();

$htaccess_text = $generator->write(null, $subdir);
echo "Htaccess generated... and you will not be able to see this message if you refresh browser - you will get a 404 instead - if you can see then there is an error.";
echo "<pre>\n\n" . $htaccess_text . "<pre>\n\n";


