<?php

$widget = false;
include_once('settings.php');
include_once('include.php');

include_once('party_common.php');

if ($error['error'])
    $smarty->display('error.tpl');
else
    $smarty->display('party-page.tpl');

?>
