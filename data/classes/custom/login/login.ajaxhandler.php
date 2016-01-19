<?php

switch($_POST['action'])
{
	case 'verifyLogin':
		if (!isset($_POST['values']['storeLogin']))
			$_POST['values']['storeLogin'] = 0;
		
		$return = user::verifyPassword($_POST['values']['mail'], $_POST['values']['password'], $_POST['values']['storeLogin']);

		if($return === true)
			echo json_encode(array('status'=>'correct', 'user'=>$_SESSION['user']));
		else
			echo json_encode(array('status'=>$return));
		break;	

	case "register":
		$user = user::registerUser($_POST['values']);
        		
		if($user->errmsg == '')
		{
			if(mailer::sendRegistrationMail($user) == 1)
				echo json_encode(array('status'=>'correct', 'msg'=>'Registrierung abgeschlossen. Bitte überprüfe dein Mailpostfach um dein Account zu bestätigen.'));
			else
				echo json_encode(array('status'=>'Beim Versand der Registrierungsmail ist ein Problem aufgetreten.'));

			/*
			if(user::verifyPassword($user->get('mail'), $_POST['values']['password'], $_POST['values']['storeLogin']) === true)
				echo json_encode(array('status'=>'correct'));
			else
				echo json_encode(array('status'=>$user->errmsg));
			*/
		}
		else
			echo json_encode(array('status'=>$user->errmsg));
		break;
}

?>