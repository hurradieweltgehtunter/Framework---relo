<?php
/* load all classes */
$includes[] = 'basic/dataprovider.class.php';

$includes[] = 'basic/request.class.php';
$includes[] = 'basic/database.class.php';
$includes[] = 'basic/post.class.php';
$includes[] = 'basic/log.class.php';

$includes[] = '../../data/classes/basic/user.class.php';
$includes[] = 'basic/beuser.class.php';

/* --------------------------------------- */


//include custom classes
$dir = "./classes/custom";
$dirs = array();
$dh  = opendir($dir);

while (false !== ($dirname = readdir($dh))) {
    if($dirname != '.' && $dirname != '..' && !is_file($dirname))
    	$includes[] = 'custom/' . $dirname . '/' . $dirname . '.class.php';
}
/* --------------------------------------- */
?>