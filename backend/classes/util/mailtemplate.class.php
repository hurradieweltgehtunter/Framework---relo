<?php

class mailTemplate
{
	public $_tpl_path = 'data/mailtemplates/';
	public $_tpl_filled;

	function __construct($filename, $substituteEntities)
	{
		$dom = new DOMDocument();
		if (!@$dom->loadHTMLFile($this->_tpl_path . $filename)) {
			throw new HtmlMailException("HTML-Teplate ".basename($this->_tpl_id)." could not be loaded");
		}
		
		$vars = $dom->getElementsByTagName(config::get('mailtemplate')['varname']);

		foreach($vars as $var)
		{
			$text = $dom->createTextNode($substituteEntities[$var->getAttribute('index')]);
			$var = $var->parentNode->replaceChild($text, $var);
		}

		$this->_tpl_filled = $dom->save();
	}

}

class HtmlMailException extends Exception {}

?>