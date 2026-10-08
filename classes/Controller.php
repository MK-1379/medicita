<?php
abstract class Controller
{
    protected $request;
    protected $action;

    public function __construct($action, $request)
    {
        $this->action = $action;
        $this->request = $request;
    }

    public function executeAction()
    {
        return $this->{$this->action}();
    }

    protected function view($view, $data = [])
    {
        extract($data);
        require ROOT_PATH . "views/$view.php";
    }

    protected function redirect($url)
    {
        header("Location: " . BASE_URL . $url);
        exit;
    }

    protected function requireAuth($role)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== $role) {
            header("Location: " . BASE_URL . "auth/login");
            exit;
        }
    }

    // Las acciones que cambian datos solo aceptan POST. Así un enlace o una
    // imagen en otra web no pueden ejecutarlas, y el token CSRF siempre se comprueba.
    protected function requirePost($fallbackUrl)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($fallbackUrl);
        }
    }

    protected function requireGuest()
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL);
            exit;
        }
    }
}