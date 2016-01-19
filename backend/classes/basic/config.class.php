<?php

class config 
{

	private static $me = null;

	function __construct()
	{
		global $_CONFIG;

		if(strpos(getcwd(), 'backend') !== false)
			require_once('../data/config/systemsettings.conf.php');
		else
			require_once('data/config/systemsettings.conf.php');
		
		foreach($_CONFIG as $key=>$val)
			$this->$key = $val;

	}

	public static function get($key)
	{

		if (self::$me == null)
			self::$me = new config();
				
		return self::$me->$key;
	}

}

?>