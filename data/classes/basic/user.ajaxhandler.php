<?php

switch ($_POST['action']) {
    /*
        case 'sendMessage':
        if($this->user->sendMessage($_POST['values']['text'], $_POST['values']['recipientId']))
            echo json_encode(array('status'=>1, "time"=>date('H:i')));

        break;
    */

    case 'sendNewPassword':
        $user             = new user($_POST['values']['clientId']);
        $newPassword      = $user->generateRandomPassword();
        $loginCredentials = $user->createPassword($newPassword);

        if ($loginCredentials['success'] === 1) {
            $result = mailer::sendNewPasswordMail($user, $newPassword);

            if ($result === true) {
                $user->set('salt', $loginCredentials['salt']);
                $user->set('password', $loginCredentials['password']);
                $user->save();
                echo json_encode(array('status' => 'correct', 'msg' => Texter::get('client')['sendNewPassword']));
            } else {
                echo json_encode(array('status' => Texter::get('client')['sendNewPasswordfail']));
            }
        }
        break;

    default:
        echo json_encode(array('errmsg' => 'Unknown request on module '.$_POST['module']));
        break;
}//end switch
