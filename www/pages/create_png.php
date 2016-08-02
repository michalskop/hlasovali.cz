<?php
// creating one svg and two pngs (one smaller)
$cache_life = 1;  //caching time, in seconds
$filenamesvg = 'cache/svg/' . $_POST['name'].'.svg';
$fmtime = filemtime($filenamesvg);
// full pic

if (!$fmtime or (time() - $fmtime >= $cache_life)) {
    file_put_contents($filenamesvg,$_POST['svg']);

    $com = 'inkscape -z -e cache/png/ve_' . $_POST['name'].'.png cache/svg/' .  $_POST['name'].'.svg';
    exec($com);
}

//smaller pic (hardcoding, based od 400:300)
$filenamesvg = 'cache/svg/ve_' . $_POST['name'].'.svg';
$fmtime = filemtime($filenamesvg);
if (!$fmtime or (time() - $fmtime >= $cache_life)) {
    file_put_contents($filenamesvg,$_POST['svg']);

    $com = 'inkscape -z -a 0:53:400:300 -h 180 -e cache/png/compact_' . $_POST['name'].'.png cache/svg/' .  $_POST['name'].'.svg';
    exec($com);
}
?>
