<?php
$settings = json_decode(file_get_contents("settings.json"));


require($settings->smarty_path);
$smarty = new Smarty();
$smarty->setTemplateDir($settings->app_path . 'smarty/templates/');
$smarty->setCompileDir($settings->app_path . 'smarty/templates_c/');

spl_autoload_register(function ($class) {
    global $settings;
    include $settings->app_path . 'www/classes/' . $class . '.php';
});

$smarty->assign('settings', $settings);


//get page
$page = isset($_GET['page']) ? $_GET['page'] : 'frontpage';
$smarty->assign('page', $page);

// set up user
$user = new User($settings);
$smarty->assign('user', $user->info());

switch ($page) {

}
$smarty->display($page . '.tpl');

?>
