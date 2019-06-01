<?php
namespace Framework;

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\Routing\Matcher\UrlMatcher;
use \Symfony\Component\Routing\Exception\ResourceNotFoundException;
use \Symfony\Component\Routing\Route;
use \Symfony\Component\Routing\RouteCollection;
use \Symfony\Component\Routing\RequestContext;
use \Symfony\Component\Routing\Generator\UrlGenerator;
use \Symfony\Component\HttpKernel\HttpKernelInterface;

class Application implements HttpKernelInterface{
    public $templates;
    private $routes;
    private $generator;

    public function __construct(){
        $this->templates = new Templates($this);
        $this->routes  = new RouteCollection();
    }

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $context = new RequestContext();
        $context->fromRequest($request);
        $this->generator = new UrlGenerator($this->routes, $context);

        $matcher = new UrlMatcher($this->routes, $context);

        try{
            $attributes = $matcher->match($request->getPathInfo());
            $controller = $attributes['controller'];
            unset($attributes['controller']);
            $ret = call_user_func_array($controller, $attributes);
            $response =  $ret;

        }catch (ResourceNotFoundException $err){
            $response =  new Response("Not Found" . $request->getPathInfo(), 404);
        }
        if(is_string($response)){
            $response = new Response($response);
        }

        return $response->send();
    }


    public function route($path, $controller, $name=null, $methods=null){
        if(is_null($name)){
            $name = $path;
        }
        $route = new Route($path, ['controller' => $controller]);
        $this->routes->add($name, $route);
    }

    public function asset($path){
        return $path;
    }

    public function url($name, $args=[]){
        return $this->generator->generate($name, $args);
    }

    public function run(){
//        echo 'Document Root: ' . $_SERVER['REDIRECT_DOCUMENT_ROOT'] . PHP_EOL;
//        echo 'REQUEST URI: ' . $_SERVER['REDIRECT_REQUEST_URI'] . PHP_EOL;
//        echo 'REQUEST FILENAME: ' . $_SERVER['REDIRECT_REQUEST_FILENAME'] . PHP_EOL;
//        echo 'DOCUMENT X: ' . $_SERVER['DOCUMENT_X'] . PHP_EOL;
        $request = Request::createFromGlobals();
        return $this->handle($request);
    }
}
