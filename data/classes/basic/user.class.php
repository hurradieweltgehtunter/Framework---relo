<?php

class User
{

    public $data = array();

    public $files = array();

    public $status = 0;

    public $errmsg = '';

    public static $me = null;


    public function __construct($id = 0)
    {
        if ($id == 0) {
            $RS = database::Query('Show COLUMNS FROM users', array());
            foreach ($RS as $DS) {
                if ($DS['Default'] != null) {
                    $this->data[$DS['Field']] = $DS['Default'];
                } else {
                    $this->data[$DS['Field']] = '';
                }
            }
        } else {
            $userdata = database::Query('SELECT * FROM users WHERE id = '.$id.';', array());
            if (count($userdata) === 1) {
                $this->data = $userdata[0];
            } else {
                $this->status = false;
            }

            $RS = database::Query('SELECT * FROM files WHERE user_id = '.$id.';', array());
            foreach ($RS as $DS) {
                $this->files[$DS['id']] = $DS;
            }

            $me = $this;
        }//end if

    }//end __construct()


    public static function registerUser($data)
    {
        $errmsg = '';
        $user   = new self();

        if ($data['data']['mail'] == '') {
            $user->errmsg = 'Keine E-Mail-Adresse angegeben';
        }

        database::Query('SELECT * FROM users WHERE mail=:var1', array('var1' => $data['data']['mail']), $stats);

        if ($stats > 0) {
            $user->errmsg = 'Diese E-Mail-Adresse ist bereits registriert';
        }

        $loginCredentials = $user->createPassword($data['password']);

        if ($loginCredentials['success'] == 0) {
            $user->errmsg = $loginCredentials['errmsg'];
        }

        if ($data['password'] != $data['password2']) {
            $user->errmsg = 'Passwörter stimmen nicht überein';
        }

        if ($user->errmsg == '') {
            foreach ($data['data'] as $key => $val) {
                $user->set($key, $val);
            }

            $user->set('accesscode', $user->getAccessCode());
            $user->set('salt', $loginCredentials['salt']);
            $user->set('password', $loginCredentials['password']);
            $user->save();
        }

        return $user;

    }//end registerUser()


    public static function generateSalt()
    {
        $salt  = substr(base64_encode(openssl_random_pseudo_bytes(17)), 0, 22);
        $salt  = str_replace("+", ".", $salt);
        
        return $salt;

    }//end generateSalt()


    public function getAccessCode()
    {
        $charset       = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789()-_';
        $randString    = '';
        $randStringLen = 64;

        while (strlen($randString) < $randStringLen) {
            $randChar    = substr(str_shuffle($charset), mt_rand(0, strlen($charset)), 1);
            $randString .= $randChar;
        }

        return $randString;

    }//end getAccessCode()


    public function generateRandomPassword()
    {
        $charset       = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randString    = '';
        $randStringLen = 6;

        while (strlen($randString) < $randStringLen) {
            $randChar    = substr(str_shuffle($charset), mt_rand(0, strlen($charset)), 1);
            $randString .= $randChar;
        }

        return $randString;

    }//end generateRandomPassword()

    public function encryptPassword($password, $salt) {

        $cost=11;

        $param = '$'.implode('$',array(
                 "2y", //select the most secure version of blowfish (>=PHP 5.3.7)
                 str_pad($cost,2,"0",STR_PAD_LEFT), //add the cost in two digits
                 $salt //add the salt
        ));

        return crypt($password,$param);
    }

    public function createPassword($rawPassword, $userId = 0)
    {
        $errmsg = false;

        if (strlen($rawPassword) < 6) {
            $errmsg = 'Dieses Passwort ist zu kurz';
        }

        if ($errmsg === false) {
            $salt     = $this->generateSalt();
            $password = $this->encryptPassword($rawPassword, $salt);

            if ($userId > 0) {
                $RS = database::Query('UPDATE users SET password=:var1, salt=:var2 WHERE id='.$userId.';', array('var1' => $password, 'var2' => $salt));
            }

            return array(
                    'success'  => 1,
                    'salt'     => $salt,
                    'password' => $password,
                   );
        } else {
            return array(
                    'success' => 0,
                    'errmsg'  => $errmsg,
                   );
        }

    }//end createPassword()


    public function save()
    {
        $sqls = array();
        $vals = array();

        $i = 1;

        foreach ($this->data as $field => $val) {
            if ($field == 'last_update') {
                $sqls[] = 'last_update='.time();
                continue;
            }

            $sqls[] = "$field=:var$i";
            $vals[] = $val;

            ++$i;
        }

        if ($this->get('id') == 0) {
            $RS = database::Query('INSERT users SET '.implode(',', $sqls).';', $vals, $user_id);
            $this->set('id', $user_id);
        } else {
            $RS = database::Query('UPDATE users SET '.implode(',', $sqls).' WHERE id = '.$this->get('id').';', $vals);
        }

        // save img comments
        foreach ($this->files as $id => $file) {
            database::Query('UPDATE files SET comment=:var1 WHERE id='.$id, array('var1' => $file['comment']));
        }

        return true;

    }//end save()


    public static function verifyPassword($mail, $password, $storelogin = 0)
    {
        $RS = database::Query('SELECT id, salt, password, status FROM users WHERE mail=:var1', array('var1' => $mail), $stats);

        if ($stats == 0) {
            return Texter::get('user')['accNotFound'];
            exit();
        }

        $user = new self($RS[0]['id']);

        if ($user->get('status') === 1) {
            if ($user->encryptPassword($password, $user->get('salt')) == $user->get('password')) {
                $user->doLogin($storelogin);

                return true;
            } else {
                return Texter::get('user')['accNotFound'];
            }
        } else {
            return Texter::get('user')['accNotActivated'];
        }

    }//end verifyPassword()


    public function doLogin($storelogin = 0)
    {
        if ($storelogin == 1) {
            self::setCookie($this->get('id'), $this->get('password'));
        }

        $_SESSION['user_id'] = $this->get('id');
        $_SESSION['user']    = $this;

    }//end doLogin()


    public static function setCookie($userId, $password)
    {
        $expiration = (time() + 2629743);

        $hash = hash('md5', $userId.$_SERVER['REMOTE_ADDR'].$password);

        $cookie = $userId.'|'.$expiration.'|'.$hash;

        setcookie('auth_cookie', $cookie, $expiration, '/');

    }//end setCookie()


    public static function verifyCookie($cookie)
    {
        $parts = explode('|', $cookie);
        $RS    = database::Query('SELECT password FROM users WHERE id=:var1', array('var1' => $parts[0]));

        $hash = hash('md5', $parts[0].$_SERVER['REMOTE_ADDR'].$RS[0]['password']);

        if ($parts[2] == $hash && time() <= $parts[1]) {
            $_SESSION['user_id'] = $parts[0];
            $_SESSION['user']    = new self($parts[0]);

            return true;
        } else {
            return false;
        }

    }//end verifyCookie()


    public function sendMessage($text, $recipientId)
    {
        database::Query('INSERT INTO messages SET sender_id="'.$this->data['id'].'", recipient_id=:var1, text=:var2, time='.time().';', array('var1' => $recipientId, 'var2' => $text), $msgid);

        return $msgid;

    }//end sendMessage()


    public function getChat($lastmsgid = 0)
    {
        $RS = array();
        if ($lastmsgid == 0) {
            $RS = database::Query(
                '	SELECT * FROM messages 
									WHERE sender_id='.$this->data['id'].' OR recipient_id='.$this->data['id'].' 
									ORDER BY id DESC LIMIT 6',
                array()
            );
        } else {
            $RS = database::Query('SELECT a.*, b.profilepic FROM messages a JOIN users b ON a.sender_id = b.id WHERE (a.sender_id='.$this->data['id'].' OR a.recipient_id= '.$this->data['id'].') AND a.id > '.$lastmsgid.'  ORDER BY a.id DESC LIMIT 6', array());
        }

        $RS = array_reverse($RS);

        return $RS;

    }//end getChat()


    public function getActivationLink()
    {
        $link = config::get('system')['baseURL'].config::get('system')['subDir'].'login/activation/'.$this->get('accesscode');

        return $link;

    }//end getActivationLink()


    public static function activate($code)
    {
        $RS = database::Query('SELECT id FROM users WHERE accesscode=:var1 AND status=0', array('var1' => $code), $stats);

        if ($stats == 1) {
            $user = new self($RS[0]['id']);
            $user->set('status', 1);
            $user->save();
        } else {
            $user         = new self();
            $user->errmsg = 'Dieser Code ist nicht gültig';
        }

        return $user;

    }//end activate()


    // FRONTEND FUNCTIONS END
    public function set($key, $value)
    {
        $this->data[$key] = $value;

    }//end set()


    public function get($key)
    {
        return $this->data[$key];

    }//end get()
}//end class
