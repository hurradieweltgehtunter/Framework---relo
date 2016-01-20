<?php
/**
switch($_POST['action'])
{
	case 'sendMessage':
		if($this->user->sendMessage($_POST['values']['text'], $_POST['values']['recipientId']))
			echo json_encode(array('status'=>1, "time"=>date('H:i')));

		break;
}
*/

?>