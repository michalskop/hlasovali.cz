<?php

// logout

spl_autoload_register(function ($class) {
    global $settings;
    include $settings->app_path . 'www/classes/' . $class . '.php';
});

switch ($action) {
    case 'run':
    default:
        $user = new User($settings);
        $user->logout();
        if (isset($_GET['continue'])) {
            header("Location: " . $_GET['continue']);
        } else {
            header("Location: index.php?page=motion");
        }
        exit;
}

?>
