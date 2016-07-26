<?php

// motion

spl_autoload_register(function ($class) {
    global $settings;
    include $settings->app_path . 'www/classes/' . $class . '.php';
});

switch ($action) {
    case 'new':
        neww();
        break;
    case 'create':
        create();
        break;
}

function create() {
    global $user, $cityhall, $settings;
    require_once($settings->app_path . 'www/helpers/globalfunctions_helper.php');
    $motion = new Motion($settings);
    $data = $motion->parseForm($_POST);
    $data['organization_id'] = $cityhall->getCityHall()->id;
    $data['user_id'] = $user->getUser()->id;
    $motion->create($data);
}

function neww() {
    global $user, $cityhall, $smarty;
    if ($cityhall->information->selected) {
        $smarty->assign('user_has_author_privilages', $user->hasAuthorPrivilages($cityhall->information->id));
    }

    $smarty->display('motion_new.tpl');
}


?>
