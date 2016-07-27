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
    case 'update':
        update();
        break;
    case 'view':
    default:
        view();
}

function update() {
    global $user, $cityhall, $settings;

    //update motion
    require_once($settings->app_path . 'www/helpers/globalfunctions_helper.php');
    $motion = new Motion($settings);
    $data = $motion->parseForm($_POST);
    $data['organization_id'] = $cityhall->getCityHall()->id;
    $data['user_id'] = $user->getUser()->id;
    $new_motion = $motion->update($data,$_POST['motion_id']);

    //update tags
    $tags = new Tag($settings);
    $t_arr = $tags->parseForm($_POST);
    $tags->update($t_arr,$_POST['motion_id']);
    header("Location: " . $_GET['continue']);
}

function create() {
    global $user, $cityhall, $settings;

    //create motion
    require_once($settings->app_path . 'www/helpers/globalfunctions_helper.php');
    $motion = new Motion($settings);
    $data = $motion->parseForm($_POST);
    $data['organization_id'] = $cityhall->getCityHall()->id;
    $data['user_id'] = $user->getUser()->id;
    $new_motion = $motion->create($data);

    //create tags
    $tags = new Tag($settings);
    $t_arr = $tags->parseForm($_POST);
    $tdata = [];
    foreach ($t_arr as $t) {
        $item = new StdClass();
        $item->tag = $t;
        $item->motion_id = $new_motion->id;
        $item->active = TRUE;
        $tdata[] = $item;
    }
    $tags->create($tdata);

    header("Location: index.php?page=motion&action=view&m=" . $new_motion->id);
}

function neww() {
    global $user, $cityhall, $smarty;
    if ($cityhall->information->selected) {
        $smarty->assign('user_has_author_privilages', $user->hasAuthorPrivilages($cityhall->information->id));
    }

    $smarty->display('motion_new.tpl');
}

function view() {
    global $user, $settings, $smarty;

    require_once($settings->app_path . 'www/helpers/globalfunctions_helper.php');

    $motion = new Motion($settings);
    $tags = new Tag($settings);
    if (isset($_GET['m'])) {
        $m = $motion->getMotion($_GET['m']);
        $smarty->assign('motion',$m);
        if ($m->exist) {
            $smarty->assign('date_and_time',preformat_date($m->date,$m->date_precision));
        }
        $smarty->assign('tags',$tags->getTags($_GET['m']));
        $smarty->assign('user_can_edit', $user->canEditMotion($_GET['m']));

    } else {
        $smarty->assign('motion',$motion->getMotion());
        $smarty->assign('tags',[]);
        $smarty->assign('user_can_edit', false);
    }

    $smarty->display('motion_view.tpl');
}


?>
