<?php

switch($_POST['action'])
{

	case 'locksystem':


		echo json_encode(array('status'=>1, 'locked'=>system::lockSystem()));

		break;


}

?>