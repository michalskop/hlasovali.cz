<?php
// user page

if (isset($_GET['u'])) {
    $u = $user->getUserMotionsCounts($_GET['u']);
} else {
    $cu = $user->getCurrentUser();
    if (isset($cu->id) and $cu->id) {
        $u = $user->getUserMotionsCounts($cu->id);
    } else {
        $u = $user->getUserMotionsCounts();
    }
}


$smarty->assign('organizations',$u);
if (isset($u[0])) {
    $smarty->assign('title',$u[0]->user_name);
} else {
    $smarty->assign('title',$t->get('users'));
}


$smarty->display('user.tpl');

?>
