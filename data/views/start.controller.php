<?php

class Start extends system
{


    public static function getImages($user_id)
    {
        $return = '';
        $user   = new User($user_id);

        foreach ($user->files as $file) {
            $return .= '<div class="row imgrow" data-image-id="'.$file['id'].'" >
	                        <div class="col-sm-3">
	                            <div class="img_wrap" >	
	                                <img src="data/img/_users/'.$file['filename'].'" />
	                                <div class="img-toolbar">
	                                	<span class="glyphicon glyphicon-remove-circle remove" aria-hidden="true"></span>
	                                	<span class="glyphicon glyphicon-fullscreen fullscreen" aria-hidden="true"></span>
	                                </div>
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
                                        <textarea class="form-control imgcomment" data-id="'.$file['id'].'" rows="3">'.$file['comment'].'</textarea>
                                    </div>
	                            </form>
	                        </div>
	                    </div>';
        }//end foreach

        return $return;

    }//end getImages()


    public static function getChat()
    {
        $return = '';
        $user   = new user($_SESSION['user_id']);
        $RS     = $user->getChat();

        foreach ($RS as $msg) {
            $remoteuser = new user($msg['sender_id']);

            if ($msg['recipient_id'] == $_SESSION['user_id']) {
                // msg from relo
                $return .= '<div class="row chat_entry chat_relo" data-msgid="'.$msg['id'].'">
		                        <div class="col-xs-1">
		                        	<img class="chat_userimg" src="data/img/_users/_thumbs/'.$remoteuser->get('profilepic').'">
		                        </div>

		                        <div class="col-xs-6 chat_message">
		                        	<div class="chat_time">
		                        		Florian Lenz '.date('d.m.Y H:i', $msg['time']).'
			                        </div>
		                        		'.$msg['text'].'
		                        </div>
		                    </div>';
            }

            if ($msg['sender_id'] == $_SESSION['user_id']) {
                // msg sent by client
                $return .= '<div class="row chat_entry chat_client" data-msgid="'.$msg['id'].'">
		                        
		                        <div class="col-xs-6 col-xs-offset-5 text-right chat_message">
		                        	<div class="chat_time text-right">
			                        	Florian Lenz | '.date('d.m.Y H:i', $msg['time']).'
			                        </div>
		                        		'.$msg['text'].'
		                        </div>

		                        <div class="col-xs-1">
		                        	<img class="chat_userimg" src="data/img/_users/_thumbs/'.$user->get('profilepic').'">
		                        </div>
		                    </div>';
            }
        }//end foreach

        return $return;

    }//end getChat()


    public static function getProfilePic()
    {
        $RS = database::Query('SELECT profilepic FROM users WHERE id ='.$_SESSION['user_id'].';', array());
        if ($RS[0]['profilepic'] != '') {
            return '<img class="profilepic" src="data/img/_users/'.$RS[0]['profilepic'].'">';
        } else {
            return '';
        }

    }//end getProfilePic()
}//end class
