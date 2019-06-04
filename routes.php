<?php

use \Symfony\Component\HttpFoundation\Response;

$app->route('/', 'Home');
$app->route('/auth', 'Home', ['auth_needed' => true]);
$app->route('/login', 'Login', ['name' => 'login']);
$app->route('/auth_setup', 'AuthSetup', ['name' => 'auth_setup']);




/* closure example
$app->route('/', function (){
    //return new Response("Home Page", 200, ['Content-Type'=>'text/plain']);
    $app = $GLOBALS['app'];
    return $app->templates->render('home.html');
});
*/
