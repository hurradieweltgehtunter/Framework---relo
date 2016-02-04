<?php

class client extends system
{


    static public function getImages($user_id)
    {
        $return = '';
        $RS     = database::Query('SELECT * FROM files WHERE user_id=:var1', array('var1' => $user_id), $stats);
        if ($stats > 0) {
            foreach ($RS as $file) {
                $return .= '<div class="row imgrow">
		                        <div class="col-sm-3">
		                            <div class="img_wrap">
		                                <img src="../data/img/_users/'.$file['filename'].'" />
		                            </div>
		                        </div>

		                        <div class="col-sm-9">
		                            <form class="form-horizontal">
		                                <div class="row">
		                                	<div class="col-sm-3">
				                                <div class="form-group">
	                                        		<label>ID</label>
			                                        <input type="text" class="form-control" value="'.$file['id'].'" disabled>
			                                    </div>
				                            </div>

				                            <div class="col-sm-3 col-sm-offset-1">
				                                <div class="form-group">
	                                        		<label>Hochgeladen am</label>
			                                        <input type="text" class="form-control" value="'.date('d.m.Y H:i', $file['date']).'" disabled>
			                                    </div>
	                                        </div>
				                        </div>

				                        <div class="form-group">
	                                        <label for="known_from">Dein Kommentar</label>
	                                        <textarea class="form-control imgcomment" data-id="'.$file['id'].'" rows="3" disabled>'.$file['comment'].'</textarea>
	                                    </div>
		                            </form>
		                        </div>
		                    </div>';
            }//end foreach
        }//end if

        return $return;

    }//end getImages()


    static public function getChat($clientid)
    {
        $return = '';

        $user = new beuser($_SESSION['beuser_id']);
        $RS   = $user->getChat(0, $clientid);

        $client = new user($clientid);

        foreach ($RS as $msg) {
            if ($msg['recipient_id'] == 0) {
                // FROM client
                $return .= '<div class="row chat_entry chat_relo" data-msgid="' . $msg['id'] . '">
		                        <div class="col-xs-1">
		                        	<img class="chat_userimg" src="../data/img/_users/_thumbs/' . $client->get('profilepic') . '">
		                        </div>

		                        <div class="col-xs-6 chat_message">
		                        	<div class="chat_time">
		                        		' . $client->get('firstname') . ' ' . $client->get('lastname') . ' | ' . date('d.m.Y H:i', $msg['time']) . '
			                        </div>
		                        		' . $msg['text'] . '
		                        </div>
		                    </div>';
            } else if ($msg['recipient_id'] == $clientid) {
            	//FROM RELO
                $messenger = new beuser($msg['sender_id']);

                $return .= '<div class="row chat_entry chat_client" data-msgid="' . $msg['id'] . '">

		                        <div class="col-xs-6 col-xs-offset-5 text-right chat_message">
		                        	<div class="chat_time text-right">
			                        	' . $user->get('firstname') . ' ' . $user->get('lastname') . ' | ' . date('d.m.Y H:i', $msg['time']) . '
			                        </div>
		                        		' . $msg['text'] . '
		                        </div>

		                        <div class="col-xs-1">
		                        	<img class="chat_userimg" src="../data/img/_users/_thumbs/' . $user->get('profilepic') . '">
		                        </div>
		                    </div>';
            }
        }//end foreach

        return $return;

    }//end getChat()


    public static function getLog($user)
    {
        $return = '	<table class="table table-striped" id="userlog">
						<thead>
							<th>Datum</th>
							<th>Nachricht</th>
							<th>&nbsp;</th>
						</thead>
						<tbody>';
		$entries = $user->getLog();
        foreach ($entries as $entry) {
            $return .= '<tr><td>'.date('d.m.Y H:i', $entry['date']).'</td><td>'.$entry['text'].'</td><td>' . $entry['data'] . ' </td></tr>';
        }

        $return .= '</tbody></table>';

        return $return;
    }//end getLog()
}//end class
