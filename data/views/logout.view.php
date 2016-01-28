<?php


logging::log(10, $_SESSION['user']);

session_destroy();
unset($_COOKIE['auth_cookie']);
setcookie('auth_cookie', null, -1, '/');

header('Location: login?reason=logout');
