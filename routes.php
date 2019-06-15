<?php

use \Symfony\Component\HttpFoundation\Response;

$app->route('/', 'Home', ['name' => 'home']);
$app->route('/auth', 'Home', ['auth_needed' => true]);
$app->route('/login', 'Login', ['name' => 'login']);
$app->route('/logout', 'Login@logout', ['name' => 'logout']);
$app->route('/auth_test', 'Login@auth_test', ['name' => 'auth_test']);
$app->route('/auth_setup', 'AuthSetup', ['name' => 'auth_setup']);
$app->route('/deploy_settings', 'DeploySettings', ['name' => 'deploy_settings']);
$app->route('/deploy', 'Deploy', ['name' => 'deploy']);
$app->route('/reset_n_deploy', 'Deploy', ['name' => 'reset_n_deploy']);


/* closure example
$app->route('/', function (){
    //return new Response("Home Page", 200, ['Content-Type'=>'text/plain']);
    $app = $GLOBALS['app'];
    return $app->templates->render('home.html');
});
*/
