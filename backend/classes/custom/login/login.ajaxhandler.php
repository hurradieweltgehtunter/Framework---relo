<?php

switch ($_POST['action']) {
    case 'verifyLogin':
        $user  = new User();
        $login = $user->verifyPassword($_POST['values']['mail'], $_POST['values']['password'], $_POST['values']['storeLogin']);

        if ($login === true) {
            $status = 'correct';
        } else {
            $status = $login;
        }

        echo json_encode(array('status' => $status));
        break;
}
