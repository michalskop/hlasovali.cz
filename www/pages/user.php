<?php
// user page

$u = $user->getUserMotionsCounts($_GET['u']);

$smarty->assign('organizations',$u);
if (isset($u[0])) {
    $smarty->assign('title',$u[0]->user_name);
} else {
    $smarty->assign('title',$t->get('users'));
}


$smarty->display('user.tpl');

?>
