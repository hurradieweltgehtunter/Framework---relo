<?php

session_destroy ();
unset($_COOKIE['auth_cookie']);
setcookie('auth_cookie', null, -1, '/backend');


header("Location: login?reason=logout");

?>