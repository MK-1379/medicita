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
        // Todas las peticiones pasan por aquí, así que es el único sitio
        // donde hace falta comprobar el token CSRF de cualquier formulario.
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !Csrf::verify()) {
            http_response_code(403);
            echo '<h1>La solicitud no es válida. Vuelve atrás, recarga la página e inténtalo de nuevo.</h1>';
            return;
        }

        if (!class_exists($this->controller) || !is_subclass_of($this->controller, 'Controller')) {
            $this->notFound();
            return;
        }

        if (!$this->isCallableAction($this->controller, $this->action)) {
            $this->notFound();
            return;
        }

        $controller = new $this->controller($this->action, $_GET);
        call_user_func_array([$controller, $this->action], $this->params);
    }

    // Solo se pueden llamar desde la URL los métodos públicos propios del
    // controlador, nunca el constructor ni los métodos de la clase base.
    private function isCallableAction($class, $action)
    {
        if (!method_exists($class, $action) || str_starts_with($action, '__')) {
            return false;
        }
        $method = new ReflectionMethod($class, $action);
        return $method->isPublic() && $method->getDeclaringClass()->getName() === $class;
    }

    private function notFound()
    {
        http_response_code(404);
        echo '<h1>Página no encontrada</h1>';
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