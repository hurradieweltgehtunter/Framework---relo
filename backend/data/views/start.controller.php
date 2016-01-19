<?php

class start {
	static public function getTable() {
		$return = '';

		$RS = database::Query('SELECT id, firstname, lastname, city, phone, mail, last_update FROM users WHERE is_admin = 0 ORDER BY lastname ASC', array());

		foreach($RS as $DS)
		{
			$RS2 = database::Query('SELECT * FROM messages WHERE sender_id = ' . $DS['id'] . ' AND recipient_id = 0 AND read_time = 0', array(), $stats);
			if($stats > 0)
				$msg_notifier = '<div class="msg_count" title="neue Nachrichten vorhanden">' . $stats . '</div>';
			else
				$msg_notifier = '&nbsp;';

			if(($DS['last_update'] + 259200) >= time())
				$update_notifier = '<div class="update_notifier" title="Kunde hat Daten geändert"><span class="glyphicon glyphicon-star" aria-hidden="true"></span><div>';
			else
				$update_notifier = '&nbsp';


			$return .= '<tr onclick="document.location = \'client/' . $DS['id'] . '\';">
							<td>' . $DS['firstname'] . '</td>
							<td>' . $DS['lastname'] . '</td>
							<td>' . $DS['city'] . '</td>
							<td>' . $DS['phone'] . '</td>
							<td>' . $DS['mail'] . '</td>
							<td class="notifier">' . $msg_notifier . '' . $update_notifier . '</td>
						</tr>';
		}

		return $return;
	}

	public static function getProfilePic()
	{
		$RS = database::Query('SELECT profilepic FROM users WHERE id =' . $_SESSION['beuser_id'] . ';', array());
		if($RS[0]['profilepic'] != '')
			return '<img class="profilepic" src="../data/img/_users/' . $RS[0]['profilepic'] . '">';
		else
			return '';
	}
}

?>