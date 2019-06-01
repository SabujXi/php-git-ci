<?php


namespace Framework;
use \Symfony\Component\HttpFoundation\Response;

class Templates
{
    private $twig;
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->twig = new \Twig\Environment(
            new \Twig\Loader\FilesystemLoader(VIEWS_DIR),
            ['cache' => TEMPLATE_CACHE_DIR]
        );
    }

    public function render($name, $context=[]){
        $context['this'] = $this->app;
        $text = $this->twig->render($name, $context=$context);
        $response = new Response($text);
        return $response;
    }
}
