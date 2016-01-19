<?php
class user
{
	public $data = array();
	public $files = array();

	public $status = 0;
	public $errmsg = '';

	public static $me = null;

	function __construct($id = 0)
	{
		
		if($id == 0)
		{
			$RS = database::Query('Show COLUMNS FROM users', array());
			foreach($RS as $DS)
			{
				if($DS['Default'] != null)
					$this->data[$DS['Field']] = $DS['Default'];	
				else
					$this->data[$DS['Field']] = '';
			}
		}
		else
		{
			$userdata = database::Query('SELECT * FROM users WHERE id = ' . $id . ';', array());
			if(count($userdata) == 1)
				$this->data = $userdata[0];
			else
				$this->status = false;
			
			$RS = database::Query('SELECT * FROM files WHERE user_id = ' . $id . ';', array());
			foreach($RS as $DS)
			{
				$this->files[$DS['id']] = $DS;
			}

			$me = $this;
		}   
	}

	public static function registerUser($data){
		
		$errmsg = '';
		$user = new user();

		if($data['data']['mail'] == '')
			$user->errmsg = 'Keine E-Mail-Adresse angegeben';

		database::Query('SELECT * FROM users WHERE mail=:var1', array('var1'=>$data['data']['mail']), $stats);
		
		if($stats > 0)
			$user->errmsg = 'Diese E-Mail-Adresse ist bereits registriert';

		$loginCredentials = $user->createPassword($data['password']);
		
		if($loginCredentials['success'] == 0)
			$user->errmsg = $loginCredentials['errmsg'];

		if($data['password'] != $data['password2'])
			$user->errmsg = 'Passwörter stimmen nicht überein';

		if($user->errmsg == '')
		{
			foreach($data['data'] as $key=>$val)
			{
				$user->set($key, $val);
			}
			$user->set('accesscode', $user->getAccessCode());
			$user->set('salt', $loginCredentials['salt']);
			$user->set('password', $loginCredentials['password']);
			$user->save();
		}

		return $user;		
	}

	public static function generateSalt(){
		$charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789/\\][{}\'";:?.>,<!@#$%^&*()-_=+|';
		$randString = "";
		$randStringLen = 32;

		while(strlen($randString) < $randStringLen) {
			$randChar = substr(str_shuffle($charset), mt_rand(0, strlen($charset)), 1);
	 		$randString .= $randChar;
		}

		return $randString;
	}

	function getAccessCode()
	{
		$charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789()-_';
		$randString = "";
		$randStringLen = 64;

		while(strlen($randString) < $randStringLen) {
			$randChar = substr(str_shuffle($charset), mt_rand(0, strlen($charset)), 1);
	 		$randString .= $randChar;
		}

		return $randString;
	}

	public static function createPassword($rawPassword, $userId=0)
	{

		$errmsg = false;

		if(strlen($rawPassword) < 6)
			$errmsg = 'Dieses Passwort ist zu kurz';
		
		if($errmsg === false)
		{
			$salt = user::generateSalt();
			$password = hash("sha512", $salt . $rawPassword);
			
			if($userId > 0)
				$RS = database::Query('UPDATE users SET password=:var1, salt=:var2 WHERE id=' . $userId . ';', array("var1"=>$password, "var2"=>$salt));

			return array("success"=>1, 'salt'=>$salt, 'password'=>$password);	
		}
		else
			return array('success'=>0, 'errmsg'=>$errmsg);
		
	}

	public function save()
	{
		
		$sqls = array();
		$vals = array();

		$i = 1;

		foreach($this->data as $field=>$val)
		{

			if($field == 'last_update')
			{
				$sqls[] = 'last_update=' . time();
				continue;
			}

			$sqls[] = "$field=:var$i";
			$vals[] = $val;

			$i++;
		}
	

		if($this->get('id') == 0)
		{
			$RS = database::Query('INSERT users SET ' . implode(',', $sqls) . ';', $vals, $user_id);
			$this->set('id', $user_id);
		}
		else
			$RS = database::Query('UPDATE users SET ' . implode(',', $sqls) . ' WHERE id = ' . $this->get('id') . ';', $vals);

		//save img comments
		foreach($this->files as $id=>$file)
			database::Query('UPDATE files SET comment=:var1 WHERE id=' . $id, array('var1'=>$file['comment']));

		return true;
	}

	public static function verifyPassword($mail, $password, $storelogin=0)
	{
		$RS = database::Query('SELECT id, salt, password, status FROM users WHERE mail=:var1', array('var1'=>$mail), $stats);
		
		if($stats == 0)
			return "Diese E-Mailadresse-Passwort-Kombination ist uns nicht bekannt.";
		
		if($RS[0]['status'] == 1)
		{
			if(hash("sha512", ($RS[0]['salt'] . $password)) == $RS[0]['password'])
			{
				if($storelogin == 1)
					user::setCookie($RS[0]['id'], $RS[0]['password']);

				$_SESSION['user_id'] = $RS[0]['id'];
				$_SESSION['user'] = new user($RS[0]['id']);

				return true;
			}
			else
				return "Diese E-Mailadresse-Passwort-Kombination ist uns nicht bekannt.";	
		}
		else
			return "Dieser Account ist noch nicht aktiviert.";
	}

	public static function setCookie($userId, $password)
	{
		$expiration = time() + 2629743;

		$hash  = hash('md5', $userId . $_SERVER['REMOTE_ADDR'] . $password);

		$cookie = $userId . '|' . $expiration . '|' . $hash;

		setcookie('auth_cookie', $cookie, $expiration, '/');
	}

	public static function verifyCookie($cookie)
	{
		$parts = explode('|', $cookie);
		$RS = database::Query('SELECT password FROM users WHERE id=:var1', array("var1"=>$parts[0]));

		$hash  = hash('md5', $parts[0] . $_SERVER['REMOTE_ADDR'] . $RS[0]['password']);

		if($parts[2] == $hash && time() <= $parts[1])
		{
			$_SESSION['user_id'] = $parts[0];
			$_SESSION['user'] = new user($parts[0]);
			return true;
		}
		else
			return false;
	}

	public function sendMessage($text, $recipientId){

		database::Query('INSERT INTO messages SET sender_id="' . $this->data['id'] . '", recipient_id=:var1, text=:var2, time=' . time() . ';', array('var1'=>$recipientId, 'var2'=>$text), $msgid);
		return $msgid;
	}

	public function getChat($lastmsgid = 0)
	{
		$RS = array();
		if($lastmsgid == 0)
		{
			$RS = database::Query('	SELECT * FROM messages 
									WHERE sender_id=' . $this->data['id']. ' OR recipient_id=' . $this->data['id']. ' 
									ORDER BY id DESC LIMIT 6', array());
		}
		else		
			$RS = database::Query('SELECT a.*, b.profilepic FROM messages a JOIN users b ON a.sender_id = b.id WHERE (a.sender_id=' . $this->data['id']. ' OR a.recipient_id= ' . $this->data['id']. ') AND a.id > ' . $lastmsgid . '  ORDER BY a.id DESC LIMIT 6', array());
		
		$RS = array_reverse($RS);	

		return $RS;
	}

	public function getActivationLink()
	{
		$link = config::get('system')['baseURL'] . config::get('system')['subDir'] . 'login/activation/' . $this->get('accesscode');
		return $link;
	}

	public static function activate($code)
	{
		$RS = database::Query('SELECT id FROM users WHERE accesscode=:var1 AND status=0', array('var1'=>$code), $stats);
		
		if($stats == 1)
		{
			$user = new user($RS[0]['id']);
			$user->set('status', 1);
			$user->save();
		}
		else
		{
			$user = new user();
			$user->errmsg = 'Dieser Code ist nicht gültig';
		}
		return $user;
	}

	/* FRONTEND FUNCTIONS END*/


	public function set($key, $value)
	{
		$this->data[$key] = $value;
	}

	public function get($key)
	{
		return $this->data[$key];
	}



	/* --------------------- */


    public static function getSetting($key)
    {
    	if (self::$me == null)
			self::$me = new user();
		
		return (self::$me->settings[$key]);
    }



    /**
	saves settings to user
	$key -->	which columns should be updated (value, value2 ...)
	$name -->	which setting should be updated (stored in column 'name')
	$value -->	which value should be saved
    */
    public static function setSetting($key, $name, $value)
    {
		if (self::$me == null)
			self::$me = new user();
		
		database::Update('UPDATE user_settings SET ' . $key . ' = "' . $value . '" WHERE name = "' . $name . '" AND user_id = ' . user::get('id') . ';', $RS);
    }

	static function getAll()
	{
		$users = array();
		
		database::Query('SELECT * FROM users ORDER BY lastname ASC', $RS);
		while($RS->get($DS))
			$users[] = $DS;

		return $users;
	}

	
/*
	// Zugriffsmethoden     **********************************************************************************************
	
	public function FullName()
	{
		if (self::$me == null)
			self::$me = new user();
		return self::$me->firstname . ' ' . self::$me->lastname;
	}

	// Statische Funktionen **********************************************************************************************

	public static function isUser()
	{
		$ReturnValue = false;

		if (self::$me == null)
			self::$me = new user();

		if (self::$me->getID() != 0)
			$ReturnValue = true;

		return ($ReturnValue);
	}


	public static function getContext()
	{
		if (self::$me == null)
			self::$me = new user();

		return (self::$me);
	}

	public static function id()
	{
		return $this->id;
	}


	public static function mail()
	{
		if (self::$me == null)
			self::$me = new system();

		return (self::$me->user['mail']);
	}


	public static function status()
	{
		if (self::$me == null)
			self::$me = new user();

		return (self::$me->getStatus());
	}
*/
}


?>