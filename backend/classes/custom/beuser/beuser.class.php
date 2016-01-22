<?php

class Beuser extends User
{


    public function isAdmin()
    {
        if (self::$me == null) {
            self::$me = new beuser($_SESSION['beuser_id']);
        }

        if ($this->get('is_admin') == 1) {
            return true;
        } else {
            return false;
        }

    }//end isAdmin()


    public function verifyPassword($mail, $password, $storelogin = 0)
    {
        $RS = database::Query('SELECT id, salt, password, status, is_admin FROM users WHERE mail=:var1', array('var1' => $mail), $stats);

        if ($stats == 0 || $RS[0]['is_admin'] == 0) {
            return Texter::get('user|accNotFound');
        }

        if ($RS[0]['status'] == 1) {
            if ($this->encryptPassword($password, $RS[0]['salt']) == $RS[0]['password']) {
                if ($storelogin == 1) {
                    self::setCookie($RS[0]['id'], $RS[0]['password']);
                }

                $_SESSION['beuser_id'] = $RS[0]['id'];
                $_SESSION['beuser']    = new beuser($RS[0]['id']);

                return true;
            } else {
                return Texter::get('user|accNotFound');
            }
        } else {
            return Texter::get('user|accNotActivated');
        }

    }//end verifyPassword()


    public function getChat($lastmsgid = 0, $clientid = 0)
    {
        $RS = array();

        if ($lastmsgid == 0) {
            $RS = database::Query(
                'SELECT * FROM messages WHERE (
				sender_id='.$clientid.' AND recipient_id = 0
				) OR (
				 recipient_id='.$clientid.'
				) ORDER BY id DESC LIMIT 16',
                array()
            );
        } else {
            $RS = database::Query(
                '	SELECT a.*, b.profilepic 
									FROM messages a JOIN users b ON a.sender_id = b.id 
									WHERE ((a.recipient_id = 0 AND a.sender_id = '.$clientid.') OR (a.recipient_id = '.$clientid.')) AND a.id > '.$lastmsgid.' 
									ORDER BY id',
                array()
            );
        }

        $RS = array_reverse($RS);
        return $RS;

    }//end getChat()


    public static function setCookie($userId, $password)
    {
        $expiration = (time() + 2629743);

        $hash = hash('md5', $userId.$_SERVER['REMOTE_ADDR'].$password);

        $cookie = $userId.'|'.$expiration.'|'.$hash;

        setcookie('relo_backend', $cookie, $expiration, '/backend');

    }//end setCookie()


    public static function verifyCookie($cookie)
    {
        $parts = explode('|', $cookie);
        $RS    = database::Query('SELECT password FROM users WHERE id=:var1', array('var1' => $parts[0]));

        $hash = hash('md5', $parts[0].$_SERVER['REMOTE_ADDR'].$RS[0]['password']);

        if ($parts[2] == $hash && time() <= $parts[1]) {
            $_SESSION['beuser_id'] = $parts[0];
            $_SESSION['beuser']    = new beuser($parts[0]);
            return true;
        } else {
            return false;
        }

    }//end verifyCookie()
}//end class
