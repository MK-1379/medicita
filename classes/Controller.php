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

    protected function requireGuest()
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL);
            exit;
        }
    }
}