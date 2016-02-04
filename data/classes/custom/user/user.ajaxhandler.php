<?php

switch ($_POST['action']) {
    case 'saveData':
        $user = new user($_SESSION['user_id']);

        if (isset($_POST['values']['user'])) {
            foreach ($_POST['values']['user'] as $key => $val) {
                $user->set($key, $val);
            }
        }

        if (isset($_POST['values']['files'])) {
            foreach ($_POST['values']['files'] as $key => $val) {
                $user->files[$val['id']]['comment'] = $val['comment'];
            }
        }

        $user->save();
        logging::log(3, $user, $_POST['values']['user']);
        echo json_encode(array('status' => 1));
        break;

    case 'sendMessage':
        $return = $this->user->sendMessage($_POST['values']['text'], $_POST['values']['recipientId']);

        if ($return !== false) {
            echo json_encode(array('status' => 1, 'time' => date('d.m.Y H:i'), 'msgid' => $return, 'profilepic' => $this->user->get('profilepic'), 'username'=>$this->user->get('firstname') . ' ' . $this->user->get('lastname')));
        }
        break;

    case 'chatinit':
        $return = '';
        $user   = new user($_SESSION['user_id']);
        $RS     = $user->getChat($_POST['values']['lastmsgid']);
        foreach ($RS as $key => $msg) {
            $RS[$key]['time'] = date('d.m.Y H:i', $msg['time']);
        }

        echo json_encode(array('messages' => $RS, 'count' => count($RS)));
        break;

    case 'setNewPassword':
        if ($_POST['values']['password_new1'] != $_POST['values']['password_new2']) {
            $errmsg[]          = Texter::get('user|passwordNotEqualFail');
            $return['success'] = 0;
        } else {
            $return = $this->user->createPassword($_POST['values']['password_new1'], $this->user->get('id'));

            if ($return['success'] == 1) {
                if (isset($_COOKIE['authCookie'])) {
                    $this->user->verifyPassword($this->user->get('mail'), $_POST['values']['password_new1'], 1);
                } else {
                    $this->user->verifyPassword($this->user->get('mail'), $_POST['values']['password_new1'], 0);
                }
            } else {
                $errmsg[] = $return['errmsg'];
            }
        }

        if ($return['success'] == 0 && count($errmsg) > 0) {
            echo json_encode(array('success' => 0, 'errmsg' => $errmsg));
        } else {
            echo json_encode(array('success' => 1));
            Logging::log(7, $this->user);
        }
        break;

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
                echo json_encode(array('status' => 'correct', 'msg' => Texter::get('client|sendNewPassword')));
            } else {
                echo json_encode(array('status' => Texter::get('client|sendNewPasswordfail')));
            }
        }
        break;

    default:
        echo json_encode(array('errmsg' => Texter::get('system|unknownRequest', array($_POST['module']))));
        break;
}//end switch
