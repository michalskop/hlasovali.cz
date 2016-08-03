<?php

// login

spl_autoload_register(function ($class) {
    global $settings;
    include $settings->app_path . 'www/classes/' . $class . '.php';
});

switch ($action) {
    case 'run':
        $user = new User($settings);
        $user->login($_POST['email'],$_POST['pass']);
        if (isset($_GET['continue'])) {
            header("Location: " . $_GET['continue']);
        } else {
            header("Location: ?page=user");
        }
        exit;
    case 'view':
    default:
        $smarty->display('login.tpl');
}

?>
