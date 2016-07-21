<?php

// logout

spl_autoload_register(function ($class) {
    global $settings;
    include $settings->app_path . 'www/classes/' . $class . '.php';
});

switch ($action) {
    case 'select':
    default:
        $user = new User($settings);
        $user->logout();
        header("Location: " . $_GET['continue']);
        exit;
}

?>
