<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


//einzubindende Klassen

include 'classes/basic/system.class.php';

define('__DEBUG', false);


//Initialaktionen
$system = new system();
$system->load();
$system->output();

?>