<?php
// user page

$u = $user->getUserMotionsCounts($_GET['u']);

$smarty->assign('organizations',$u);

$smarty->display('user.tpl');

?>
