<?php

use \Symfony\Component\HttpFoundation\Response;

$app->route('/', 'Home', ['name' => 'home']);
$app->route('/auth', 'Home', ['auth_needed' => true]);
$app->route('/login', 'Login', ['name' => 'login']);
$app->route('/logout', 'Login@logout', ['name' => 'logout']);
$app->route('/auth_test', 'Login@auth_test', ['name' => 'auth_test']);
$app->route('/auth_setup', 'AuthSetup', ['name' => 'auth_setup']);
$app->route('/deploy_settings', 'DeploySettings', ['name' => 'deploy_settings', 'auth_needed' => true]);
$app->route('/deploy', 'Deploy@deploy', ['name' => 'deploy', 'auth_needed' => true]);
$app->route('/reset_n_deploy', 'Deploy@reset_n_deploy', ['name' => 'reset_n_deploy', 'auth_needed' => true]);
$app->route('/upload_data', 'DataManager@upload', ['name' => 'upload_data', 'auth_needed' => true]);
$app->route('/execute_command', 'ExecuteCommand@execute', ['name' => 'execute_command', 'auth_needed' => true]);


/* closure example
$app->route('/', function (){
    //return new Response("Home Page", 200, ['Content-Type'=>'text/plain']);
    $app = $GLOBALS['app'];
    return $app->templates->render('home.html');
});
*/
