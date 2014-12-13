<?php
/**
about
*/

$widget = true;
include_once('../settings.php');
include_once('../include.php');

$html = file_get_contents("methodology.html");


$lwt_ar = explode('/',$header['link_without_term']);
unset($lwt_ar[count($lwt_ar)-2]);

$header['link_without_term'] = implode('/',$lwt_ar);

//smarty
$smarty->assign('header',$header);
$smarty->assign('text',$text);
$smarty->assign('title',"about");

$smarty->assign('html',$html);


$smarty->display('about.tpl');

?>
