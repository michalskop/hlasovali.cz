<?php

$widget = false;
include_once('settings.php');
include_once('include.php');

if (isset($_GET['identifier']) and (trim(($_GET['identifier']) != ''))) {
    include_once('vote-event_common.php');
    if ($error['error'])
        $smarty->display('error.tpl');
    else
        $smarty->display('vote_event-page.tpl');
} else {
    include_once('vote-events_common.php');
    $smarty->display('vote_events-page.tpl');
}

?>
