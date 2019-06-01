<?php

use \Symfony\Component\HttpFoundation\Response;

$app->route('/', function (){
    //return new Response("Home Page", 200, ['Content-Type'=>'text/plain']);
    $app = $GLOBALS['app'];
    return $app->templates->render('home.html');
});


$app->route('/a', function (){
    return new Response("A Home Page", 200, ['Content-Type'=>'text/plain']);
});
