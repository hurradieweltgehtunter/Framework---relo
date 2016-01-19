<?php

class login extends system
{
	public static function activate()
	{
		if(request::get(1) == 'activation')
		{
			$user = user::activate(request::get(2));

			return $user;
		}
		else
			return new user();
	}
}

?>