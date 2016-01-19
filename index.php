<?php
session_start();
error_reporting(E_ALL);
require_once 'data/classes/basic/system.class.php';

define('__DEBUG', false);

$system = new system();
$system->load();
$system->output();
