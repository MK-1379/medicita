<?php
class Bootstrap
{
    private $controller;
    private $action;
    private $params;

    public function __construct()
    {
        $url = $this->parseUrl();

        if(empty($url[0])){
            $this->controller = 'HomeController';
        } else {
            $this->controller = ucfirst($url[0]) . 'Controller';
        }

        if(empty($url[1])){
            $this->action = 'index';
        } else {
            $this->action = $url[1];
        }

        $this->params = array_slice($url, 2);
    }

    public function run()
    {
        if(class_exists($this->controller)){
            $parents = class_parents($this->controller);
            if(in_array('Controller', $parents)){
                if(method_exists($this->controller, $this->action)){
                    $controller = new $this->controller($this->action, $_GET);
                    call_user_func_array([$controller, $this->action], $this->params);
                } else {
                    echo '<h1>Method does not exist</h1>';
                }
            } else {
                echo '<h1>Base controller not found</h1>';
            }
        } else {
            echo '<h1>Controller class does not exist</h1>';
        }
    }

    private function parseUrl()
    {
        if(isset($_GET['url'])){
            $url = trim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return ['home'];
    }
}