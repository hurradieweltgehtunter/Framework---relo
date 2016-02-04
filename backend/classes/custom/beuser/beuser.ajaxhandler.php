<?php

switch ($_POST['action']) {
    case 'saveData':
        if (isset($_POST['clientid']) === true && $_POST['clientid'] !== 'undefined') {
            $user = new user($_POST['clientid']);
        } else {
            $user = new user($_SESSION['beuser_id']);
        }

        if (isset($_POST['values']['user']) === true) {
            foreach ($_POST['values']['user'] as $key => $val) {
                $user->set($key, $val);
            }
        }

        if (isset($_POST['values']['files']) === true) {
            foreach ($_POST['values']['files'] as $key => $val) {
                $user->files[$val['id']]['comment'] = $val['comment'];
            }
        }

        $user->save();
        echo json_encode(array('status' => 1));
        break;

    case 'sendMessage':
        $return = $this->user->sendMessage($_POST['values']['text'], $_POST['values']['recipientId']);

        if ($return !== false) {
            echo json_encode(array('status' => 1, 'time' => date('d.m.Y H:i'), 'profilepic' => $this->user->get('profilepic'), 'msgid' => $return, 'username'=>$this->user->get('firstname') . ' ' . $this->user->get('lastname')));
        }
        break;

    case 'chatinit':
        $return = '';
        $user   = new beuser($_SESSION['beuser_id']);

        $RS = $user->getChat($_POST['values']['lastmsgid'], $_POST['values']['clientid']);
        foreach ($RS as $key => $msg) {
            $RS[$key]['time'] = date('d.m.Y H:i', $msg['time']);
        }

        echo json_encode(array('messages' => $RS, 'count' => count($RS)));
        break;

    case 'checkNewMessages':
        /*
            * Handler for backend/start to check for new incoming messages
         */
        $RS = database::Query('SELECT * FROM messages WHERE recipient_id = 0 AND read_time = 0', array(), $stats);
        if ($stats === 1) {
            $user = new User($RS['sender_id']);
            echo json_encode(array('msg' => Texter::get('beuser|newMessage', array($user->get('firstname', $user->get('lastname'))))));
        } else {
            echo json_encode(array('msg' => Texter::get('beuser|newMessages', array($stats))));
        }
        break;

    case 'setNewPassword':
        $errmsg            = false;
        $return['success'] = 0;

        if ($_POST['values']['password_new1'] !== $_POST['values']['password_new2']) {
            $errmsg = 'Die Passwörter sind nicht identisch';
        } else {
            $return = $this->user->createPassword($_POST['values']['password_new1'], $this->user->get('id'));

            if ($return['success'] === 1) {
                if (isset($_COOKIE['relo_backend']) === true) {
                    $this->user->verifyPassword($this->user->get('mail'), $_POST['values']['password_new1'], 1);
                } else {
                    $this->user->verifyPassword($this->user->get('mail'), $_POST['values']['password_new1'], 0);
                }
            } else {
                $errmsg = $return['errmsg'];
            }
        }

        if ($return['success'] === 0 || $errmsg !== false) {
            echo json_encode(array('success' => 0, 'errmsg' => $errmsg));
        } else {
            echo json_encode(array('success' => 1));
        }
    break;

    case 'makeAdmin':
        $user = new beuser($_POST['values']['clientId']);

        if ($user->isAdmin() === true) {
            $user->set('is_admin', 0);
        } else {
            $user->set('is_admin', 1);
        }

        $user->save();
        echo json_encode(array('txt' => Texter::get('client|makeAdmin|' . $user->get('is_admin'))));
    break;

    default:
        echo json_encode(array('errmsg' => 'Unknown request on module '.$_POST['module']));
    break;
}//end switch
