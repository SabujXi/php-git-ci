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
use \Symfony\Component\EventDispatcher\EventDispatcher;
use \Symfony\Component\HttpKernel\HttpKernelInterface;
use \Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Session\Session;


class Application implements HttpKernelInterface{
    public $templates;
    private $routes;
    private $generator;
    private $dispatcher;
    private $session;
    private $auth;
    private $file_db;

    public function __construct(){
        $this->dispatcher = new EventDispatcher();
        $this->templates = new Templates($this);
        $this->routes  = new RouteCollection();
        $this->session = new Session();

        $this->on('web_request', [$this, 'handle_web_request']);
        // file db
        $this->file_db = new FileDB(CONFIGS_DIR);

    }

    public function start_session(){
        if(!$this->session->isStarted()){
            $this->session->start();
        }
        return $this->session;
    }

    public function file_db(){
        return $this->file_db;
    }

    public function get_auth(){
        if(is_null($this->auth)){
            if(!$this->file_db->entity_exists('auths')){
                $this->auth = new Auth($this, null, null);
            }else{
                $session = $this->start_session();
                $username = $session->get('username', null);
                $password = $session->get('password', null);
                $auths_entity = $this->file_db->get_entity('auths');
                if($auths_entity->get('username') === $username && $auths_entity->get('password') === $password && !in_array(null, [$username, $password])){
                    $this->auth = new Auth($this, $username, $password);
                }else{
                    $this->auth = new Auth($this, null, null);
                }
            }
        }
        return $this->auth;
    }

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true){
        $web_req_event = new WebRequestEvent($request);
        $this->dispatch('web_request', $web_req_event);
    }

    public function handle_web_request(WebRequestEvent $web_req_event)
    {
        $request = $web_req_event->get_request();
        $context = new RequestContext();
        $context->fromRequest($request);
        $this->generator = new UrlGenerator($this->routes, $context);

        $matcher = new UrlMatcher($this->routes, $context);

        try{
            $attributes = $matcher->match($request->getPathInfo());
            $controller = $attributes['controller'];
            unset($attributes['controller']);
            array_unshift($attributes, $request);
            // determine type of controller and act accordingly.
            // if it is a closure

            // auth
            if($attributes['auth_needed']){
                $auth = $this->get_auth();
                if(!$auth->is_authentic()){
//                    var_dump('not authentic');
                    $this->redirect('login')->send();
                    return ;
                }
            }


            if(is_object($controller) && $controller instanceof \Closure){
                $response = call_user_func_array($controller, $attributes);
            }elseif(function_exists($controller)){
                $response = call_user_func_array($controller, $attributes);
            }else{
                $class_name = null;
                $method_name = null;
                $_ = explode('@', $controller);
                if(count($_) > 1){
                    $class_name = $_[0];
                    $method_name = $_[1];
                }else{
                    $class_name = $_[0];
                    $method_name = 'index';
                }
                $class_name = '\Controller\\' . $class_name;
                assert(is_subclass_of($class_name, '\Framework\BaseController'), 'Invalid controller');

                $instance = new $class_name($this);
                $response = call_user_func_array([$instance, $method_name], $attributes);
            }

        }catch (ResourceNotFoundException $err){
            $response =  new Response("Not Found" . $request->getPathInfo(), 404);
        }
        if(is_string($response)){
            $response = new Response($response);
        }

        return $response->send();
    }

    public function on($event_name, $handler){
        $this->dispatcher->addListener($event_name, $handler);
    }

    public function dispatch($event_name, Event $event=null){
        $this->dispatcher->dispatch($event_name, $event);
    }

    public function route($path, $controller, $params=[]){
        $name=!isset($params['name']) ? null : $params['name'];
        $methods=!isset($params['methods']) ? null : $params['methods'];
        $auth_needed=!isset($params['auth_needed']) ? null : $params['auth_needed'];
        if(is_null($name)){
            $name = $path;
        }
        $route = new Route($path, ['controller' => $controller]);
        $route->setDefault('auth_needed', $auth_needed);
        $this->routes->add($name, $route);
    }

    public function asset($path){
        $path = ltrim($path, '/');
        return URL_BASE . 'public' . "/" . $path;
    }

    public function url($name, $args=[]){
        return $this->generator->generate($name, $args);
    }

    public function redirect($route_name, $args=[]){
        $url = $this->url($route_name, $args);
        return new Response('Redirecting...', 303, ['Location' => $url]);
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
