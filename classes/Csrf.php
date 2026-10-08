<?php
// Protección CSRF con el patrón "synchronizer token":
// cada sesión tiene un token aleatorio que todo formulario POST debe reenviar.
class Csrf
{
    public static function token()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function field()
    {
        return '<input type="hidden" name="csrf_token" value="' . self::token() . '">';
    }

    public static function verify()
    {
        $sent = $_POST['csrf_token'] ?? '';
        return isset($_SESSION['csrf_token'])
            && is_string($sent)
            && hash_equals($_SESSION['csrf_token'], $sent);
    }

    // Se llama al iniciar sesión, igual que session_regenerate_id().
    public static function regenerate()
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}
