<?php



/*********************************************************************************************************\

$File:        CheckLib.class.php
$Class:       CheckLib
$Description: Standandardklasse zur Werteüberprüfung in PHP Anwendungen
$Version:     0.1.0

\*********************************************************************************************************/


class CheckLib
{
	
//	*****************************************************************************************************
//
//	Überprüft eine ID, ob sie formal korrekt ist (numerisch, größer als Null) und überprüft deren Existenz in der Datenbank
//
//  Parameter:
//  
//  $DataID    - Die ID, die überprüft werden soll
//  $TableName - Die Tabelle, in der die ID Enthalten ist
//  $dbLookUp  - Boolean, gibt an, ob die Überprüfung durchgeführt werden soll oder nicht
//
//
//  Rückgabe:
//  true, wenn die ID existiert und korrekt ist.
//  false, wenn die ID nicht existiert oder nicht korrekt ist.
//
//	*****************************************************************************************************
	
	static function cTableID($DataID,$TableName='',$dbLookUp=false)
	{
		global $_SYSTEM;
	
		$ReturnValue=false;
		
		$DataID=intval($DataID);
		
		if (is_int($DataID) && $DataID > 0)
		{
			if ($TableName !='' && $dbLookUp)
			{
				$QueryString='SELECT count(*) FROM '.addslashes(DB_PREFIX.$TableName).' WHERE id="'.addslashes($DataID).'"';
				if ($_SYSTEM['database']->Count($QueryString) > 0)
				{
					$ReturnValue=true;
				}
			}
			else
				$ReturnValue=true;
		}
		return ($ReturnValue);
	}


//	*****************************************************************************************************
//
//	Überprüft einen optionalen  String. Die Stringlänge darf maximal 255 Zeichen betragen
//
//  Parameter:
//  
//  $DataProvider - Die Klasse, die den String enthält
//  $Key          - Der Name des Schlüssels, unter dem der String abgerufen werden kann
//  $TooLongKey   - Der Schlüssel, der zurückgegeben werden soll, wenn der String zu lang ist. 
//
//
//  Rückgabe:
//  true, wenn der String korrekt ist
//  false, wenn der String nicht korrekt ist. Zusätzlich erfolgt noch ein Eintrag in die Fehlerliste der Klasse, die den String enthält
//
//	*****************************************************************************************************


	static function isOptionalString(&$DataProvider,$Key,$TooLongKey='CheckLib_OptionalString_TooLong')
	{
		$ReturnValue=true;
		
		$Value=$DataProvider->get($Key);
		
		if (strlen($Value) > 255)
		{
			$DataProvider->setError($Key,$TooLongKey);
			$ReturnValue=false;
		}
		
		return ($ReturnValue);
	}


//	*****************************************************************************************************
//
//	Überprüft einen benötigten String. Die Stringlänge darf maximal 255 Zeichen betragen und muss minimal ein Zeichen betragem
//
//  Parameter:
//  
//  $DataProvider - Die Klasse, die den String enthält
//  $Key          - Der Name des Schlüssels, unter dem der String abgerufen werden kann
//
//
//  Rückgabe:
//  true, wenn der String korrekt ist
//  false, wenn der String nicht korrekt ist. Zusätzlich erfolgt noch ein Eintrag in die Fehlerliste der Klasse, die den String enthält
//
//	*****************************************************************************************************


	static function isRequiredString(&$DataProvider,$Key,$NoneKey='CheckLib_RequiredString_None',$TooLongKey='CheckLib_RequiredString_TooLong')
	{
		$ReturnValue=true;
		
		$Value = $DataProvider->get($Key);
		
		if (strlen($Value) == 0)
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$NoneKey);
		}	
		else if (strlen($Value) > 255)
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$TooLongKey);
		}	
		
		return ($ReturnValue);
	}


//	*****************************************************************************************************
//
//	Überprüft einen benötigten String. Die Stringlänge darf maximal 255 Zeichen betragen und muss minimal ein Zeichen betragen, ausserdem darf der String nur einmal vorkommen
//
//  Parameter:
//  
//  $DataProvider - Die Klasse, die den String enthält
//  $Key          - Der Name des Schlüssels, unter dem der String abgerufen werden kann
//
//
//  Rückgabe:
//  true, wenn der String korrekt ist
//  false, wenn der String nicht korrekt ist. Zusätzlich erfolgt noch ein Eintrag in die Fehlerliste der Klasse, die den String enthält
//
//	*****************************************************************************************************


	static function isRequiredUniqueString(&$DataProvider,$Key,$Table,$FieldName,$IDName,$IDValue,$NoneKey='CheckLib_RequiredUniqueString_None',$TooLongKey='CheckLib_RequiredUniqueString_TooLong',$DuplicateKey='Checklib_RequiredUniqueString_Duplicate')
	{
	
		$ReturnValue=true;
		
		$Value = $DataProvider->get($Key);
		
		if (strlen($Value) == 0)
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$NoneKey);
		}	
		else if (strlen($Value) > 255)
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$TooLongKey);
		}	
		else
		{
			if (!self::isUniqueEntry($Table,$FieldName,$Value,$IDName,$IDValue))
			{
				$DataProvider->setError($Key,$DuplicateKey);
				$ReturnValue=false;
			}
		}
		return ($ReturnValue);
	}


//	*****************************************************************************************************
//
//	Überprüft einen benötigten Text. Die Stringlänge kann unbegrenzt lang sein
//
//  Parameter:
//  
//  $DataProvider - Die Klasse, die den String enthält
//  $Key          - Der Name des Schlüssels, unter dem der String abgerufen werden kann
//
//
//  Rückgabe:
//  true, wenn der String korrekt ist
//  false, wenn der String nicht korrekt ist. Zusätzlich erfolgt noch ein Eintrag in die Fehlerliste der Klasse, die den String enthält
//
//	*****************************************************************************************************


	static function isRequiredText(&$DataProvider,$Key, $MaxLength=-1, $NoneKey='CheckLib_RequiredText_None', $TooLongKey='CheckLib_RequiredText_TooLong')
	{

		$ReturnValue=true;
		
		$Value = $DataProvider->get($Key);
		
		if (strlen($Value) == 0)
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$NoneKey);
		}
		else if($MaxLength != -1)
		{
			if (strlen($Value) > $MaxLength)
			{
				$ReturnValue=false;
				$DataProvider->setError($Key,$TooLongKey);
			}
		}
	
		
		return ($ReturnValue);
	}	

//	*****************************************************************************************************
//
//	Überprüft einen benötigten Text. Die Stringlänge kann unbegrenzt lang sein
//
//  Parameter:
//  
//  $DataProvider - Die Klasse, die den String enthält
//  $Key          - Der Name des Schlüssels, unter dem der String abgerufen werden kann
//
//
//  Rückgabe:
//  true, wenn der String korrekt ist
//  false, wenn der String nicht korrekt ist. Zusätzlich erfolgt noch ein Eintrag in die Fehlerliste der Klasse, die den String enthält
//
//	*****************************************************************************************************


	static function isOptionalText(&$DataProvider,$Key, $MaxLength = -1, $TooLongKey='CheckLib_RequiredText_None')
	{
		$ReturnValue=true;
		
		if ($MaxLength != -1)
		{
			if (strlen($DataProvider->get($Key)) > $MaxLength)
			{
				$ReturnValue=false;
				$DataProvider->setError($Key,$TooLongKey);
			}	
		}
		return ($ReturnValue);
	}	

	
//	*****************************************************************************************************
//
//	Überprüft einen benötigten String. Die Stringlänge darf maximal 255 Zeichen betragen und muss minimal ein Zeichen betragem
//
//  Parameter:
//  
//  $DataProvider - Die Klasse, die den String enthält
//  $Key          - Der Name des Schlüssels, unter dem der String abgerufen werden kann
//
//
//  Rückgabe:
//  true, wenn der String korrekt ist
//  false, wenn der String nicht korrekt ist. Zusätzlich erfolgt noch ein Eintrag in die Fehlerliste der Klasse, die den String enthält
//
//	*****************************************************************************************************


	static function isRequiredMail(&$DataProvider,$Key,$NoneKey='CheckLib_RequiredMail_None',$TooLongKey='CheckLib_RequiredMail_TooLong',$FormatKey='CheckLib_RequiredMail_Format')
	{
		$ReturnValue=true;
		
		$Value = $DataProvider->get($Key);
		
		if (strlen($Value) == 0)
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$NoneKey);
		}	
		else if (strlen($Value) > 255)
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$TooLongKey);
		}	
		else if (!preg_match('|^.+@.+$|i',$Value))
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$FormatKey);
		}
		
		return ($ReturnValue);
	}	


//	*****************************************************************************************************
//
//	Überprüft einen benötigte E-Mail Adresse. Die Stringlänge darf maximal 255 Zeichen betragen und muss minimal ein Zeichen betragen, die E-Mail Adresse darf maximal einmal in der Tabelle vorkommen.
//
//  Parameter:
//  
//  $DataProvider - Die Klasse, die den String enthält
//  $Key          - Der Name des Schlüssels, unter dem der String abgerufen werden kann
//
//
//  Rückgabe:
//  true, wenn der String korrekt ist
//  false, wenn der String nicht korrekt ist. Zusätzlich erfolgt noch ein Eintrag in die Fehlerliste der Klasse, die den String enthält
//
//	*****************************************************************************************************


	//CheckLib::isRequiredUniqueMail($this,'mail','adm_user','mail','id',0,'Bitte geben Sie eine E-Mail Adresse an!','Der Eintrag in dieses Feld darf aus maximal 255 Zeichen bestehen!','Es existiert schon ein Eintrag mit dieser E-Mail Adresse');

	static function isRequiredUniqueMail(&$DataProvider, $Key, $Table, $FieldName, $IDName,$IDValue,$NoneKey='CheckLib_RequiredUniqueMail_None',$TooLongKey='CheckLib_RequiredUniqueMail_TooLong',$FormatKey='CheckLib_RequiredUniqueMail_Format',$DuplicateKey='CheckLib_RequiredUniqueMail_Duplicate')
	{
		$ReturnValue=true;
		
		$Value = $DataProvider->get($Key);
		
		if (strlen($Value) == 0)
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$NoneKey);
		}	
		else if (strlen($Value) > 255)
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$TooLongKey);
		}	
		else if (!preg_match('|^.+@.+\.[a-z]{2,4}$|i',$Value))
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$FormatKey);
		}
		else
		{
			if (!self::isUniqueEntry($Table,$FieldName,$Value,$IDName,$IDValue))
			{
				$DataProvider->setError($Key,$DuplicateKey);
				$ReturnValue=false;
			}
		
		}
		
		return ($ReturnValue);
	}	

//	*****************************************************************************************************
//
//	Überprüft eine benötigte E-Mail Adresse. Die Stringlänge darf maximal 255 Zeichen betragen und muss minimal ein Zeichen betragen
//
//  Parameter:
//  
//  $DataProvider - Die Klasse, die den String enthält
//  $Key          - Der Name des Schlüssels, unter dem der String abgerufen werden kann
//
//
//  Rückgabe:
//  true, wenn der String korrekt ist
//  false, wenn der String nicht korrekt ist. Zusätzlich erfolgt noch ein Eintrag in die Fehlerliste der Klasse, die den String enthält
//
//	*****************************************************************************************************


	static function isOptionalMail(&$DataProvider,$Key,$TooLongKey='CheckLib_OptionalMail_TooLong',$FormatKey='CheckLib_OptionalMail_Format')
	{
		$ReturnValue=true;
		
		$Value = $DataProvider->get($Key);
		
		if (strlen($Value) > 0)
		{
			if (strlen($Value) > 255)
			{
				$ReturnValue=false;
				$DataProvider->setError($Key,$TooLongKey);
			}	
			else if (!preg_match('|^.+@.+$|i',$Value))
			{
				$ReturnValue=false;
				$DataProvider->setError($Key,$FormatKey);
			}
		}
		
		return ($ReturnValue);
	}	
	
	
	
//	*****************************************************************************************************
//
//	Überprüft eine benötigte URL. Die URL-Länge darf maximal 255 Zeichen betragen.
//
//  Parameter:
//  
//  $DataProvider - Die Klasse, die den String enthält
//  $Key          - Der Name des Schlüssels, unter dem der String abgerufen werden kann
//
//
//  Rückgabe:
//  true, wenn die URL korrekt ist
//  false, wenn die URL nicht korrekt ist. Zusätzlich erfolgt noch ein Eintrag in die Fehlerliste der Klasse, die den String enthält
//
//	*****************************************************************************************************


	static function isRequiredURL(&$DataProvider,$Key,$CheckOnline=false,$NoneKey='CheckLib_RequiredURL_None',$TooLongKey='CheckLib_RequiredURL_TooLong',$FormatKey='CheckLib_RequiredURL_Format',$NetworkKey='CheckLib_RequiredURL_Network')
	{
		$ReturnValue=true;
		$Value=$DataProvider->get($Key);
		
		if (strlen($Value) == 0)
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$NoneKey);
		}	
		else if (strlen($Value) > 255)
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$TooLongKey);
		}	
		elseif (!preg_match('|^http://|',$Value))
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$FormatKey);
		}
		elseif ($CheckOnline)
		{
			if ($ContentFile=@fopen($Value,'r') !== false)
			{
				
			}
			else
			{
				$ReturnValue=false;
				$DataProvider->setError($Key,$NetworkKey);
			}
		}
		return ($ReturnValue);
	}


//	*****************************************************************************************************
//
//	Überprüft eine optionale Telefonnummer
//
//	*****************************************************************************************************
	

	static function isOptionalPhone(&$DataProvider,$Key,$TooLongKey='CheckLib_OptionalPhone_TooLong',$FormatKey='CheckLib_OptionalPhone_Format')
	{
		$ReturnValue=true;
		$Value=$DataProvider->get($Key);
				
		if (strlen($Value) > 0)
		{
			if (strlen($Value) > 255)
			{
				$ReturnValue=false;
				$DataProvider->setError($Key,$TooLongKey);
			}	
			elseif (!preg_match('|^[0-9-+/() ]+$|',$Value))
			{

				$ReturnValue=false;
				$DataProvider->setError($Key,$FormatKey);
			}
		}	
	
		return ($ReturnValue);
	}
	
//	*****************************************************************************************************
//
//	Überprüft eine notwendige Telefonnummer
//
//	*****************************************************************************************************
	

	static function isRequiredPhone(&$DataProvider,$Key,$NoneKey='CheckLib_RequiredPhone_None', $TooLongKey='CheckLib_RequiredPhone_TooLong',$FormatKey='CheckLib_RequiredPhone_Format')
	{
		$ReturnValue=true;
		$Value=$DataProvider->get($Key);
				
		if (strlen($Value) > 0)
		{
			$ReturnValue=CheckLib::isOptionalPhone($DataProvider,$Key,$TooLongKey,$FormatKey);
		}
		else
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$NoneKey);
		}
	
		return ($ReturnValue);
	}	


//	*****************************************************************************************************
//
//	Überprüft eine optionale PLZ (in Deutschland)
//
//	*****************************************************************************************************
	

	static function isOptionalZip(&$DataProvider,$Key,$WrongSizeKey='CheckLib_OptionalZip_WrongSize',$FormatKey='CheckLib_OptionalZip_Format')
	{
		$ReturnValue=true;
		$Value=$DataProvider->get($Key);
				
		if (strlen($Value) != 0)
		{
			if (strlen($Value) != 5)
			{
				$ReturnValue=false;
				$DataProvider->setError($Key,$WrongSizeKey);
			}	
			elseif (!preg_match('|^[0-9]+$|',$Value))
			{
				$ReturnValue=false;
				$DataProvider->setError($Key,$FormatKey);
			}
		}	
	
		return ($ReturnValue);
	}
	

//	*****************************************************************************************************
//
//	Überprüft eine notwendige PLZ (in Deutschland)
//
//	*****************************************************************************************************
	

	static function isRequiredZip(&$DataProvider,$Key,$NoneKey='CheckLib_RequiredZip_None',$WrongSizeKey='CheckLib_RequiredZip_WrongSize',$FormatKey='CheckLib_RequiredZip_Format')
	{
		$ReturnValue=true;
		$Value=$DataProvider->get($Key);
				
		if (strlen($Value) != 0)
		{
			$ReturnValue=self::isOptionalZip($DataProvider,$Key,$WrongSizeKey,$FormatKey);
		}	
		else
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$NoneKey);
		}
	
		return ($ReturnValue);
	}
	
	

//	*****************************************************************************************************
//
//	Überprüft eine optionale Datei
//
//	*****************************************************************************************************
	

	static function isOptionalFile(&$DataProvider,$Key,$TooLargeKey='CheckLib_OptionalFile_TooBig')
	{
		$ReturnValue=true;
		$Value=$DataProvider->get($Key);
	
		return ($ReturnValue);
	}
	

//	*****************************************************************************************************
//
//	Überprüft eine optionale URL. Die URL-Länge darf maximal 255 Zeichen betragen.
//
//  Parameter:
//  
//  $DataProvider - Die Klasse, die den String enthält
//  $Key          - Der Name des Schlüssels, unter dem der String abgerufen werden kann
//
//
//  Rückgabe:
//  true, wenn die URL korrekt ist
//  false, wenn die URL nicht korrekt ist. Zusätzlich erfolgt noch ein Eintrag in die Fehlerliste der Klasse, die den String enthält
//
//	*****************************************************************************************************


	static function isOptionalURL(&$DataProvider,$Key,$CheckOnline=false,$CheckHTTP=false,$TooLongKey='CheckLib_RequiredURL_TooLong',$FormatKey='CheckLib_RequiredURL_Format',$NetworkKey='CheckLib_RequiredURL_Network')
	{
		$ReturnValue=true;
		$Value=$DataProvider->get($Key);
		
		if (strlen($Value) > 0)
		{
			if (strlen($Value) > 255)
			{
				$ReturnValue=false;
				$DataProvider->setError($Key,$TooLongKey);
			}	
			elseif ($CheckHTTP && !preg_match('|^http://|',$Value))
			{
				$ReturnValue=false;
				$DataProvider->setError($Key,$FormatKey);
			}
			elseif ($CheckOnline)
			{
				if ($ContentFile=@fopen($Value,'r') !== false)
				{
				}
				else
				{
					$ReturnValue=false;
					$DataProvider->setError($Key,$NetworkKey);
				}
			}
		}
		return ($ReturnValue);
	}

	static function isGender(&$DataProvider,$Key,$SelectKey='CheckLib_isGender_Select')
	{
		$ReturnValue=true;
		$Value=$DataProvider->get($Key);
		
		if ($Value != 'm' && $Value != 'f')
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$SelectKey);
		}

		return ($ReturnValue);
	}


	static function isRequiredFile(&$DataProvider,$Key,$Path,$Extension='',$NoneKey='CheckLib_RequiredFile_None',$NotFoundKey='CheckLib_RequiredFile_NotFound')
	{
		$ReturnValue=true;
	
		$Value=$DataProvider->get($Key);

		$isEmpty=false;
		
		if (strlen($Value) == 0)
		{
		
			$DataProvider->setError($Key,$NoneKey);
			$ReturnValue=false;
		}
		else
		{
			if (file_exists($Path.$Value))
			{
			
				
			}
			else
			{
				$DataProvider->setError($Key,$NotFoundKey);
				$ReturnValue=false;
			}
		}
		
		
		
		return ($ReturnValue);
	}

	
	static function isInList(&$DataProvider,$Key,$List,$CanAll=false,$CanNone=false,$AllVal='all',$NoneVal='none',$NoneKey='CheckLib_InList_Select')
	{
		$ReturnValue=true;
	
		$Value=$DataProvider->get($Key);

		$isFound=false;

		if ($CanAll && $Value==$AllVal)	$isFound=true;
		if ($CanNone && $Value==$NoneVal)	$isFound=true;
		
		if (!$isFound)
		{
			foreach ($List AS $cEntry)
			{
				if ($cEntry==$Value)
				{
					$isFound=true;
					break;
				}
			}
		}
		
		if(!$isFound)
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$NoneKey);
		}
	
		return ($ReturnValue);
	}

	static function isInOList(&$DataProvider,$Key,$oName,$CanAll=false,$CanNone=false,$AllVal='all',$NoneVal='none',$NoneKey='CheckLib_InList_Select',$CallBack='getList')
	{
		$ReturnValue=true;
		$isFound=false;

		if (is_callable(array($oName,$CallBack)))
		{
			$Value=$DataProvider->get($Key);

			if ($CanAll && $Value==$AllVal)	$isFound=true;
			if ($CanNone && $Value==$NoneVal)	$isFound=true;
		
			if (!$isFound)
			{
				$List = call_user_func(array($oName,$CallBack));
				
				foreach ($List AS $cEntry)
				{
					if ($cEntry->get('id') == $Value)
					{
						$isFound=true;
						break;
					}
				}
			}
		}
	
		
		if(!$isFound)
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$NoneKey);
		}
	
		return ($ReturnValue);
	}
	
	
	
	static function isRequiredDate(&$DataProvider,$Key,$isFuture=true,$MissingKey='CheckLib_RequiredDate_Missing',$WrongKey='CheckLib_RequiredDate_Wrong',$FutureKey='CheckLib_RequiredDate_Future')
	{
		$ReturnValue=true;
		
		$Value=$DataProvider->get($Key);
		$Chunks=explode ("-",$Value);
		
		if ($Chunks[0] != '0000' && $Chunks[1] != '00' && $Chunks[2] != '00')
		{
			if (checkdate ($Chunks[1], $Chunks[2], $Chunks[0]))
			{
				if (!$isFuture)
				{
					$inFuture=false;
					$CurrentYear=date('Y',time());
					$CurrentMonth=date('m',time());
					$CurrentDay=date('d',time());
					
					if ($CurrentYear < $Chunks[0])
					{
						$inFuture=true;
					}
					elseif ($CurrentYear == $Chunks[0])
					{
						if ($CurrentMonth < $Chunks[1])
						{
							$inFuture=true;
						}
						elseif ($CurrentMonth == $Chunks[1])
						{
							if ($CurrentDay < $Chunks[2])
							{
								$inFuture=true;
							}
						}
					}
					if ($inFuture)
					{
						$ReturnValue=false;
						$DataProvider->setError($Key,$FutureKey);
					}
				}
			}
			else
			{
				$ReturnValue=false;
				$DataProvider->setError($Key,$WrongKey);
			}
		}
		elseif ($Value=='0000-00-00')
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$MissingKey);
		}
		else
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$MissingKey);
		}
		
		return ($ReturnValue);
	}
	
	static function isRequiredDateString(&$DataProvider,$Key,$CanFuture=true,$CanPast=true,$MissingKey='Bitte ein Datum eingeben',$FormatKey='Bitte geben Sie das Datum im Format TT.MM.JJJJ ein',$WrongKey='Dieses Datum existiert nicht',$FutureKey='CheckLib_RequiredDate_Future',$PastKey='CheckLib_RequiredDate_Past')
	{
		$ReturnValue=true;
		$Value=$DataProvider->get($Key);
		
		if ($Value != '')
		{
			if (preg_match('|^\\d\\d\.\\d\\d\.\\d\\d\\d\\d$|',$Value))
			{
				$Chunks=explode('.',$Value);
				
				if (checkdate ($Chunks[1], $Chunks[0], $Chunks[2]))
				{
				
				
				}
				else
				{
					$ReturnValue=false;
					$DataProvider->setError($Key,$WrongKey);
				}
					
				
			}
			else
			{
				$ReturnValue=false;
				$DataProvider->setError($Key,$FormatKey);
			}
		}
		else
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$MissingKey);
		}
		
		
		return ($ReturnValue);
	}

	static function isRequiredTimeString(&$DataProvider,$Key,$CanFuture=true,$CanPast=true,$MissingKey='Bitte eine Uhrzeit auswählen',$FormatKey='Die Zeitangabe liegt in einem falschem Format vor',$WrongKey='Dieses Datum existiert nicht',$FutureKey='CheckLib_RequiredDate_Future',$PastKey='CheckLib_RequiredDate_Past')
	{
		$ReturnValue=true;
		$Value=trim($DataProvider->get($Key));
		
		
		if ($Value != '' && $Value != 'none')
		{
			if (preg_match('|^\\d\\d.\\d\\d$|',$Value))
			{
				
			
				
			}
			else
			{
				$ReturnValue=false;
				$DataProvider->setError($Key,$FormatKey);
			}
		}
		else
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$MissingKey);
		}
		
		
		return ($ReturnValue);
	}	
	
	static function isRequiredNode (&$DataProvider,$Key,$ParentNode,$NodeType,$MissingKey='Bitte wählen Sie einen Knotenpunkt aus',$NotFoundKey='Der Datensatz wurde nicht gefunden')
	{
		$ReturnValue=true;
		$Value=$DataProvider->get($Key);
		
		
		if (is_numeric($Value) && $Value > 0)
		{
			$isFound = false;
			
			$pNode=new cNode($ParentNode);
			
			$cList  = $pNode->getChildren();
			$cCount =  count($cList);
			
			
			for ($i=0; $i < $cCount; $i++)
			{
				if ($cList[$i]->getID() == $Value)
				{
					$colObj=$cList[$i]->getCollection();

					
					if (method_exists($colObj,'getCID'))
					{
						if ($colObj->getCID() == $NodeType)
						{
							$isFound=true;
						}
					}
				}
			}
			
			if (!$isFound)
			{
				$ReturnValue=false;
				$DataProvider->setError($Key,$NotFoundKey);
			}
		}
		else
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$MissingKey);
		}


		return ($ReturnValue);
	}
	
	static function isRequiredSubNode (&$DataProvider,$Key,$ParentNode,$MissingKey='Bitte wählen Sie einen Knotenpunkt aus',$NotFoundKey='Der Datensatz wurde nicht gefunden')
	{
		$ReturnValue=true;
		$Value=$DataProvider->get($Key);
		
		if (is_numeric($Value) && $Value > 0)
		{
			$isFound = false;
			
			$pNode=new cNode($ParentNode);
			
			$cList  = $pNode->getChildren();
			$cCount =  count($cList);
			
			for ($i=0; $i < $cCount; $i++)
			{
				$scList=$cList[$i]->getChildren();
				$scCount=count ($scList);
				
				for ($j=0; $j < $scCount; $j++)
				{
					if ($scList[$j]->getID() == $Value)
					{
						$isFound=true;
					}
				}
			}
			
			if (!$isFound)
			{
				$ReturnValue=false;
				$DataProvider->setError($Key,$NotFoundKey);
			}
		}
		else
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$MissingKey);
		}


		return ($ReturnValue);
	}
		
	static function isRequiredNumber(&$DataProvider,$Key,$CanZero=true,$MissingKey='CheckLib_RequiredNumber_Missing',$FormatKey='CheckLib_RequiredNumber_Format',$ZeroKey='CheckLib_RequiredNumber_Zero')
	{
		$ReturnValue=true;
		$Value=$DataProvider->get($Key);
		$iValue=abs(intval($Value));

		if ($Value == '')
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$MissingKey);
		}
		elseif ($Value != $iValue || !is_numeric($Value))
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$FormatKey);
		}
		elseif (!$CanZero && $iValue==0)
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$ZeroKey);
		}
		return ($ReturnValue);
	}

	static function isOptionalNumber(&$DataProvider,$Key,$CanZero=true,$FormatKey='CheckLib_OptionalNumber_Format',$ZeroKey='CheckLib_OptionalNumber_Zero')
	{
		$ReturnValue=true;
		$Value=$DataProvider->get($Key);
		$iValue=abs(intval($Value));

		if ($Value != $iValue)
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$FormatKey);
		}
		elseif (!$CanZero && $iValue==0)
		{
			$ReturnValue=false;
			$DataProvider->setError($Key,$MissingKey);
		}
		
		return ($ReturnValue);
	}

	static function isYNValue(&$DataProvider,$Key,$canNone=false,$ErrorKey='CheckLib_YNValue_select',$aValue='yes',$bValue='no',$sValue='none')
	{
		$ReturnValue=true;
		$Value=$DataProvider->get($Key);

		if ($Value == $aValue || $Value == $bValue)
		{
			// ALLES OK
		}
		else
		{
			if ($canNone && $Value == $sValue)
			{
				// Auch noch alles OK
			}
			else
			{
				$ReturnValue=false;
				$DataProvider->setError($Key,$ErrorKey);
			}
		}
		return ($ReturnValue);
	}
	
	static function isCat(&$DataProvider, $Key, &$CatManager, $canAll=false, $canNone=false, $ErrorKey='CheckLib_IsCat_select',$aKey='all',$nKey='none')
	{
		$ReturnValue=false;
		
		$Value=$DataProvider->get($Key);
		
		if ($canNone && $Value == $nKey)
		{
			// ALLES OK
		}
		else if ($canAll && $Value == $aKey)
		{
			// Alles OK
		}
		else
		{
			// Kategorie überprüfen...		
			
			if ($CatManager->Check($Value,true))
			{
			}
			else
			{
				$ReturnValue=false;
				$DataProvider->setError($Key,$ErrorKey);
			}
		}
		return ($ReturnValue);
	}
	
	static function isImage(&$DataProvider, $Key,$Width=-1,$Height=-1,$autoResize=false)
	{
	
	}
	
	
	private static function isUniqueEntry($Table,$FieldName,$Value,$IDName,$IDValue)
	{
		$ReturnValue=false;
		$DataSet=null;
		$ResultSet=null;
			
		$QueryString='SELECT count(*) FROM `'.DB_PREFIX.$Table.'` WHERE `'.$FieldName.'` = "'.addslashes($Value).'" AND `'.$IDName.'` != "'.$IDValue.'"';
			
		if (Database::count($QueryString) == 0)
		{
			$ReturnValue=true;
		}
	
		return ($ReturnValue);
	}


	//	*****************************************************************************************************
	//
	//	SPEZIELL FÜR FÜRST 
	//
	//	*****************************************************************************************************
	
	
		static function isRequiredUniqueBKZ(&$DataProvider,$Key,$Table,$FieldName,$IDName,$IDValue,$NoneKey='CheckLib_isRequiredUniqueBKZ_None',$TooLongKey='CheckLib_isRequiredUniqueBKZ_TooLong',$FormatKey='Checklib_isRequiredUniqueBKZ_Format', $DuplicateKey='Checklib_isRequiredUniqueBKZ_Duplicate')
		{
			$ReturnValue=true;
			
			$Value = $DataProvider->get($Key);
			
			if (strlen($Value) == 0)
			{
				$ReturnValue=false;
				$DataProvider->setError($Key,$NoneKey);
			}	
			else if (strlen($Value) > 7)
			{
				$ReturnValue=false;
				$DataProvider->setError($Key,$TooLongKey);
			}	
			else if (!preg_match('|^[0-9]*$|',$Value))
			{
				$ReturnValue=false;
				$DataProvider->setError($Key,$FormatKey);
			}	
			else
			{
				if (!self::isUniqueEntry($Table,$FieldName,$Value,$IDName,$IDValue))
				{
					$DataProvider->setError($Key,$DuplicateKey);
					$ReturnValue=false;
				}
			}
			return ($ReturnValue);
	}

}

?>