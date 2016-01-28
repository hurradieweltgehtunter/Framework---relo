<?php

/* load all classes */
/* Classes from backend */

$includes[] = 'backend/classes/basic/dataprovider.class.php';
$includes[] = 'backend/classes/basic/request.class.php';
$includes[] = 'backend/classes/basic/database.class.php';
$includes[] = 'backend/classes/basic/post.class.php';
$includes[] = 'backend/classes/basic/log.class.php';
$includes[] = 'backend/classes/basic/texter.class.php';

$includes[] = 'backend/classes/custom/mailer/mailer.class.php';

$includes[] = 'backend/classes/util/mailtemplate.class.php';
$includes[] = 'backend/classes/util/browser.class.php';





//Frontend classes

/* --------------------------------------- */

//include custom classes
$dir = 'data/classes/custom';
$dirs = array();
$dh = opendir($dir);

while (false !== ($dirname = readdir($dh))) {
    if ($dirname !== '.' && $dirname !== '..' && is_file($dirname) === false) {
        $includes[] = 'custom/'.$dirname.'/'.$dirname.'.class.php';
    }
};
