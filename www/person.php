<?php

$widget = false;
include_once('settings.php');
include_once('include.php');

include_once('person_common.php');
if ($error['error'])
    $smarty->display('error.tpl');
else
    $smarty->display('person-page.tpl');

?>
