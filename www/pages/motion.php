<?php

// motion

// spl_autoload_register(function ($class) {
//     global $settings;
//     include $settings->app_path . 'www/classes/' . $class . '.php';
// });

switch ($action) {
    case 'create':
        create();
}

function create() {
    global $user, $cityhall, $smarty;
    if ($cityhall->information->selected) {
        $smarty->assign('user_has_author_privilages', $user->has_author_privilages($cityhall->information->id));
    }

    $smarty->display('motion_create.tpl');
}


?>
