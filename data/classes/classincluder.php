<?php
/* load all classes */
/* Classes from backend */

$includes[] = 'backend/classes/basic/dataprovider.class.php';
$includes[] = 'backend/classes/basic/request.class.php';
$includes[] = 'backend/classes/basic/database.class.php';

$includes[] = 'backend/classes/basic/post.class.php';
$includes[] = 'backend/classes/basic/log.class.php';

$includes[] = 'backend/classes/custom/mailer/mailer.class.php';
$includes[] = 'backend/classes/util/mailtemplate.class.php';


//Frontend classes
$includes[] = 'basic/user.class.php';

/* --------------------------------------- */


//include custom classes
$dir = "data/classes/custom";
$dirs = array();
$dh  = opendir($dir);

while (false !== ($dirname = readdir($dh))) {
    if($dirname != '.' && $dirname != '..' && !is_file($dirname))
    	$includes[] = 'custom/' . $dirname . '/' . $dirname . '.class.php';
}
/* --------------------------------------- */
?>