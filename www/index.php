<?php
$settings = json_decode(file_get_contents("settings.json"));

//get page
$page = isset($_GET['page']) ? $_GET['page'] : 'login';

// do 'selects' directly
$action = isset($_GET['action']) ? $_GET['action'] : 'view';
if ($action == 'select') {
    require("pages/" . $page . ".php");
    die();
}

require($settings->smarty_path);
$smarty = new Smarty();
$smarty->setTemplateDir($settings->app_path . 'smarty/templates/');
$smarty->setCompileDir($settings->app_path . 'smarty/templates_c/');

spl_autoload_register(function ($class) {
    global $settings;
    include $settings->app_path . 'www/classes/' . $class . '.php';
});

$smarty->assign('settings', $settings);
$smarty->assign('request_uri', $_SERVER['REQUEST_URI']);
$smarty->assign('page', $page);


// set up user
$user = new User($settings);
$smarty->assign('user', $user->getUser());

// set up city hall
$cityhall = new CityHall($settings);
$smarty->assign('cityhall', $cityhall->getCityHall());
$smarty->assign('cityhalls', $cityhall->selectFrom());

// get texts
$t = new Text($settings);
$smarty->assign('t',$t);

//print_r($cityhall->select_from());die();
//print_r($_SERVER);die();

require("pages/" . $page . ".php");

// switch ($page) {
//
// }
//$smarty->display($page . '.tpl');

?>
