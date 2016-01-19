<?php

switch($_POST['action'])
{
	case 'search':
		$search = new search($_POST['values']['needle']);
		$search->doSearch();

		echo json_encode(array('result'=>$search->result));
		break;
}

?>