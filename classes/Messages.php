<?php
class Messages
{

    public static function set($type, $text)
    {
        $type = ($type === 'danger') ? 'danger' : 'success';
        $_SESSION['flash'][$type][] = $text;
    }

    public static function display()
    {
        if (empty($_SESSION['flash'])) {
            return;
        }
        foreach ($_SESSION['flash'] as $type => $messages) {
            foreach ($messages as $text) {
                echo '<div class="alert alert-' . $type . '" role="alert">'
                    . htmlspecialchars($text, ENT_QUOTES, 'UTF-8')
                    . '</div>';
            }
        }
        unset($_SESSION['flash']);
    }
}
