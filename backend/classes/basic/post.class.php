<?php
/*

class for performing several system tests to ensure, everything is set correctly

*/

class post extends system
{

	public $errors = array();

	function __construct($system)
	{
		$this->run($system);
		return $this->errors;
	}

	public function run($system)
	{
		$this->test_mysql(config::get('mysql'));
		$this->test_systemuser();
		$this->test_directoryRights();
		$this->test_mailer(config::get('mailer'));
	}

	private function test_mysql($mysql)
	{
		$link = mysql_connect($mysql['host'], $mysql['user'], $mysql['password']);
		if (!$link) {
		    $$this->errors['mysql'][] = 'Verbindung schlug fehl: ' . mysql_error();
		}
		else
		{
			$db_selected = mysql_select_db($mysql['database'], $link);
			if (!$db_selected) {
			    $this->errors['mysql'][] ='Kann ' . $mysql['database'] . ' nicht benutzen : ' . mysql_error();
			}	
		}
	}

	private function test_systemuser()
	{

		database::Query('SELECT * FROM ' . config::get('mysql')['dbprefix'] . 'users WHERE username = "system";', $RS);
		if($RS->ResultCount == 0)
			$this->$errors['system'] = 'System-Benutzer nicht gefunden.';
	}

	private function test_directoryRights()
	{

		$directories[] = 'data/config';
		$directories[] = 'data/config/systemsettings.conf.php';
		$directories[] = 'data/temp';

		foreach($directories as $dir)
		{
			if(substr(sprintf('%o', fileperms($dir)), -4) != '0777')
				$this->errors['permissions'][] = 'Verzeichnis oder Datei "' . $dir . '" ist weder ausführbar noch beschreibbar';
		}
	}

	private function test_mailer($mailer)
	{

		//SWIFT mailer relies on proc_* PHP functions. Check if available on Server
		if(!function_exists('proc_open'))
		 	$this->errors['mailer'][] = 'PHP-Funktionen proc_* nicht verfügbar. ';

		//Check SMTP-transport
		$transport = Swift_SmtpTransport::newInstance($mailer['mailserver'], 25)
		  ->setUsername($mailer['user'])
		  ->setPassword($mailer['password'])
		  ;

		// Create the Mailer using your created Transport
		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance('POST: Mailer-Test')
			->setFrom('system@lead-on.de')
		  	->setTo(array(self::$me->user['mail']))
		  	->setBody('LeadOn Mailer Test. If you can read this, the mailer works.')
		;

		try
		{
		  	// Send the message
			$numSent = $mailer->send($message);
		}

		catch(Exception $e)
  		{
  			echo $e->getMessage();
			$this->errors['mailer'][] = $e->getMessage();	
		}
	}

}


?>