<?php

/*

Content :b Web Application

*/

// REQUEST PARSER -  lädt den REQUEST und stellt ihn dann zur Verfügung

class request
{
	private static $mySelf = null;
	
	private $Request = array();

	private function __construct()
	{
		#remove the directory path we don't want 
		$request  = str_replace("/" . config::get('system')['subDir'], "", $_SERVER['REQUEST_URI']); 

		if(strpos($request, 'backend/') !== false)
			$request  = str_replace('backend/', "", $request); 

		if(substr($request, 0, 1) == '/')
			$request = ltrim($request, '/');

		if(strpos($request, '?') !== false)
			$request = substr($request, 0, strpos($request, '?'));

		$this->Request = explode("/", $request);

		#split the path by '/'   
		$this->Request = explode("/", $request);
	}

	private function iget($offset)
	{

		$ReturnValue = '';
		
		if ($offset >= 0)
		{
			if (isset($this->Request[$offset]))
				$ReturnValue=$this->Request[$offset];
			else
				return false;
		}
		else if ($offset < 0)
		{
			if (isset($this->InvertedRequest[$offset]))
				$ReturnValue=$this->InvertedRequest[$offset];
			else
				return false;
		}
		else
		{
			$ReturnValue=$this->Request;
		}
		
		
		return ($ReturnValue);
	}
	
	public static function get($offset)
	{

		$ReturnValue='';
		
		if (self::$mySelf == null)
			self::$mySelf = new Request();
		
		$ReturnValue=self::$mySelf->iget($offset);
		
		return ($ReturnValue);

	}

}




/*	
	private function iChunks()
	{
		$ReturnValue=$this->Request;
		return($ReturnValue);
	}
	
	private function igetBase()
	{
		$ReturnValue='';
		
		$ReturnValue=$this->Base;
		
		return ($ReturnValue);
	}

	private function iget($Offset)
	{
		$ReturnValue='';
		
		if ($Offset >= 0)
		{
			if (isset($this->Request[$Offset]))
				$ReturnValue=$this->Request[$Offset];
		}
		else if ($Offset < 0)
		{
			if (isset($this->InvertedRequest[$Offset]))
				$ReturnValue=$this->InvertedRequest[$Offset];
		}
		else
		{
			$ReturnValue=$this->Request;
		}
		
		return ($ReturnValue);
	}

	private function iLenght()
	{
		$ReturnValue=$this->Length;
		return ($ReturnValue);
	}

	private function iSelf()
	{
		$ReturnValue='';
		$ReturnValue=$this->igetBase().'/'.implode('/',$this->Request);
		return ($ReturnValue);
	}

	private function iPath()
	{
		$ReturnValue='';
		
		$isNumeric=false;
		$iPath=array();
		
		foreach ($this->Request AS $cChunk)
		{
			if (is_numeric($cChunk))
			{
				$isNumeric=true;	
			}
			else
			{
				$isNumeric=false;
				break;
				
			}

			if ($isNumeric)
			{
				$iPath[]=$cChunk;
			}
		}
		
		$ReturnValue='/'.implode('/',$iPath);		
		
		return ($ReturnValue);
	}

		private function igetRoot()
		{
			$ReturnValue='';
			
			$ReturnValue=$this->Root;
			
			return ($ReturnValue);
		}


	
	// Public functionen --> Alle Static
	
	public static function path()
	{
		if (self::$mySelf == null)
			self::$mySelf = new Request();
		$ReturnValue=self::$mySelf->iPath();
		
		return ($ReturnValue);
	
	}
	
	public static function self()
	{
		if (self::$mySelf == null)
			self::$mySelf = new Request();
		$ReturnValue=self::$mySelf->iSelf();
		
		return ($ReturnValue);
	
	}
	
	public static function getBase()
	{
		if (self::$mySelf == null)
			self::$mySelf = new Request();
		$ReturnValue=self::$mySelf->igetBase();
		
		return ($ReturnValue);
	}

	public static function get($Offset)
	{
		$ReturnValue='';
		
		if (self::$mySelf == null)
			self::$mySelf = new Request();
		$ReturnValue=self::$mySelf->iget($Offset);
		
		return ($ReturnValue);
	}

	public static function length()
	{
		$ReturnValue='';

		if (self::$mySelf == null)
			self::$mySelf = new Request();
		
		$ReturnValue=self::$mySelf->iLenght();

		return ($ReturnValue);
	}

	public static function chunks()
	{
		$ReturnValue='';

		if (self::$mySelf == null)
			self::$mySelf = new Request();
		
		$ReturnValue=self::$mySelf->iChunks();

		return ($ReturnValue);
	}
	
	
	public static function link ($Node,$Append=array(),$Level=0)
	{
		if (self::$mySelf == null)
			self::$mySelf = new Request();
		
		$ReturnValue=self::$mySelf->Base.'/';

		$Chunks=self::$mySelf->Request;
		$ChunkCount=count($Chunks);
		
		$ChunkCount+=$Level;
		$ChunkIsFound=false;

		for($i=0; $i < $ChunkCount; $i++)
		{
			$CurrentChunk=$Chunks[$i];

			$ReturnValue.= $Chunks[$i].'/';
			
			if ($CurrentChunk == $Node)
			{
				$ChunkIsFound=true;
				break;
			}
		}
		
		if (!$ChunkIsFound)
		{
			$ReturnValue.=$Node.'/';
		}

		if (count($Append) > 0)
		{
			$ReturnValue.=implode('/',$Append).'/';
		}

		return ($ReturnValue);
	}

	public static function getNodePath($NodeID)
	{
		$ReturnValue='';
		$QueryString='SELECT * FROM display_pathcache WHERE node="'.$NodeID.'" AND language="'.cLang::get().'"';
		$DataSet=null;
		$ResultSet=null;
		
		if (database::query($QueryString,$ResultSet))
		{
			if ($ResultSet->get($DataSet))
			{
				$ReturnValue=$DataSet['path'];
			}
		}

		return ($ReturnValue);
	}
	
	public static function getRoot()
	{
		if (self::$mySelf == null)
			self::$mySelf = new Request();
		$ReturnValue=self::$mySelf->igetRoot();
		
		return ($ReturnValue);
	}
	
	
	public static function getParentPath($CurrentPath)
	{
		$ReturnValue = '';
		
		return ($ReturnValue);
	}


	public static function __registerClass()
	{
	}


}
*/
?>