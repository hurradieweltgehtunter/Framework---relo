<?php
class database extends system
{
	private static $myInstance = null;
	
	private $pdo = null;
	private $stmt = null;
	
	function __construct()
	{
		parent::__construct();
        $this->connect();
	}

	function connect()
	{
		$StartTime=microtime(true);

		if(!isset($this->pdo))
		{	
			$this->pdo = new PDO("mysql:dbname=" . config::get('mysql')['database'] . ";host=" . config::get('mysql')['host'] . ";charset=utf8", config::get('mysql')['user'], config::get('mysql')['password'], array("PDO::MYSQL_ATTR_INIT_COMMAND" => 'SET NAMES utf8'));
			$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
	}

	function DoQuery($request,$requestParameters, &$stats)
	{
		$userVar = false;

		if(substr_count($request, ':var1 ') > 1)
		{
			$sql = 'SET @term=:term;';
			try
			{
			    $stmt = $this->pdo->prepare($sql);
			    $stmt->bindValue(":term", $requestParameters['var1'], PDO::PARAM_STR);
			    $stmt->execute();
			}
			catch(PDOException $e)
			{
			    // error handling
			    echo '<pre>';
			    print_r($e);
			    echo '</pre>'; 
			}	

			$request = str_replace(':var1', '@term', $request);
			$userVar = true;			
		}
		

        try 
        {
        	$stmt = $this->pdo->prepare($request);

        	if($userVar)
        		$rs = $stmt->execute();
        	else
        		$rs = $stmt->execute($requestParameters);

        	if($rs)
			{
				
				if(substr($request, 0, 6) == 'INSERT')
					$stats = $this->pdo->lastInsertId();
				else if(substr($request, 0, 6) == 'SELECT')
					$stats = $stmt->rowCount();

				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				return $result;
			}
			else
			{
				echo 'error executing PDO command';
				$arr = $this->pdo->errorInfo();
				print_r($arr);
				echo $request;

			}
				
        }
		catch (PDOException $e) {
		  
		  echo '<pre>';
		  print_r($e->errorInfo[2]);
		  echo '</pre>';
		  echo 'REQUEST: ' . $request;

		}	
	}

	
	static function query($request,$requestParameters, &$stats=false)
	{
		if (self::$myInstance == null)
		{
			self::$myInstance = new Database();
		}
		$ReturnValue=self::$myInstance->doQuery($request,$requestParameters, $stats);

		return ($ReturnValue);
	}
	
}

/*

class ResultSet
{
	private $ResultSet;
	public $ResultCount=-1;
	
	function __construct(&$ResultSet)
	{
		$this->ResultSet=&$ResultSet;
		$this->ResultCount=@mysql_num_rows($ResultSet);
		$this->error = 0;
	}

	function getCount()
	{
		$ReturnValue=-1;
		$ReturnValue=$this->ResultCount;		
		return ($ReturnValue);
	}


	function get(&$DataSet)
	{
		$ReturnValue=true;
		$DataSet=array();
		
			
		if ($DataSet=mysql_fetch_assoc($this->ResultSet))
		{
			// All OK, nothing 2 do		
		}
		else
		{
			$ReturnValue=false;
			$DataSet=null;
		}
		
		
		return ($ReturnValue);
	}

	
	
	function __destruct()
	{
		//mysql_free_result($this->ResultSet);
	}
}
*/


?>