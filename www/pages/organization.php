<?php

// organization

spl_autoload_register(function ($class) {
    global $settings;
    include $settings->app_path . 'www/classes/' . $class . '.php';
});

switch ($action) {
    case 'select':
        select($settings);
}

function select($settings) {
    $cityhall = new CityHall($settings);
    $cityhall->set($_GET['org']);
    header("Location: " . $_GET['continue']);
    exit;
}

?>
