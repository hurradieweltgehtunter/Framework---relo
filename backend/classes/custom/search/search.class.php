<?php

class search {
	public $needle = '';
	public $result = Array();
	public $ignoreAdmins = true;

	function __construct($needle)
	{
		$this->needle = $needle . '%';
	}

	public function doSearch()
	{
		if(substr($this->needle, 0, 1) == '-')
			$this->result = database::Query('SELECT id, firstname, lastname, city, phone, mail FROM users WHERE (firstname!=:var1 AND lastname!=:var1 AND street!=:var1 AND zip!=:var1 AND city!=:var1 AND country!=:var1 AND phone!=:var1 AND mail!=:var1 AND biketype!=:var1 AND reseller!=:var1) AND is_admin = 0;', array('var1'=>substr($this->needle, 1)));
		else
		{

			$this->result = database::Query('SELECT id, firstname, lastname, city, phone, mail FROM users WHERE (firstname LIKE :var1 OR lastname LIKE :var1 OR street LIKE :var1 OR zip LIKE :var1 OR city LIKE :var1 OR country LIKE :var1 OR phone LIKE :var1 OR mail LIKE :var1 OR biketype LIKE :var1 OR reseller LIKE :var1) AND is_admin = 0;', array('var1'=>$this->needle));
			
			/*
			if(strpos($this->needle, '%') === false)
				$this->result = database::Query('SELECT id, firstname, lastname, city, phone, mail FROM users WHERE (firstname=:var1 OR lastname=:var1 OR street=:var1 OR zip=:var1 OR city=:var1 OR country=:var1 OR phone=:var1 OR mail=:var1 OR biketype=:var1 OR reseller=:var1) AND is_admin = 0;', array('var1'=>$this->needle));
			else
			{
				
			}	
			*/
		}
		
	}
}

?>