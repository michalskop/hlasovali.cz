<?php

// about

spl_autoload_register(function ($class) {
    global $settings;
    include $settings->app_path . 'www/classes/' . $class . '.php';
});

//include text
$parsedown = new Parsedown();
$content_md = file_get_contents("pages/about_cs_CZ.md");
$content = $parsedown->text($content_md);



$smarty->assign('title',$t->get('about_project'));

$smarty->assign('content',$content);

$smarty->display('about.tpl');

?>
