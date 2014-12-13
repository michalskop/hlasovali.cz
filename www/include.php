<?php
/**
Common includes
to be included at the beggining of the pages and widgets
after including settings.php
*/


// put full path to Smarty.class.php
require($settings['smarty_path']);

$smarty = new Smarty();
if ($widget) {
    $smarty_path = $settings['widget2page_path'] . $settings['page2smarty_path'];
    $path = $settings['widget2page_path'];
} else {
    $smarty_path = $settings['page2smarty_path'];
    $path = '';
}

$smarty->setTemplateDir($smarty_path . 'templates');
$smarty->setCompileDir($smarty_path . 'templates_c');

include_once($path . "functions.php");
include_once($path . "text.php");

setlocale(LC_ALL, $text['locale']); 

$issue = json_decode(file_get_contents($path . "json/issue.json"));
$terms = json_decode(file_get_contents($path . "json/terms.json"));

/* header */
$header = [
  'name' => $issue->title,
  'terms' => $terms,
  'link_without_term' => 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'].'?'.strip_term($_GET),
];

$smarty->assign('header',$header);
$smarty->assign('text',$text);

if (isset($_GET['term']))
    $term_chunk = '&term=' . trim($_GET['term']);
else
    $term_chunk = '';
$smarty->assign('term_chunk',$term_chunk);

$error = [
  'error' => false,
  'description' => ''
];

?>
