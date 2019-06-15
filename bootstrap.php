<?php
require_once '_bootstrap.php';

// verify mod rewrite from the rewriterule E param
if($_SERVER['UNDER_REWRITE'] !== 'YES'){
    exit('Not confirmed that it is under rewrite.');
}

use Framework\Application;
$app = new Application();
$GLOBALS['app'] = $app;

require_once 'routes.php';

$app->run();
