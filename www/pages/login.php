<?php

// login

spl_autoload_register(function ($class) {
    global $settings;
    include $settings->app_path . 'www/classes/' . $class . '.php';
});

switch ($action) {
    case 'select':
        $user = new User($settings);
        $user->login($_POST['email'],$_POST['pass']);
        header("Location: " . $_GET['continue']);
        exit;
    case 'view':
    default:

}

?>
