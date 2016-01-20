<?php

class Api
{

    public $errmsg = false;

    public $user;

    public $about = array('version' => '1.0');


    public function __construct($request)
    {
        if (isset($request['method']) === false || $request['method'] === '') {
            $this->errmsg = Texter::get('api')['noMethodSet'];
            return false;
        }

        if (isset($request['action']) === false || $request['action'] === '') {
            $this->errmsg = Texter::get('api')['noActionSet'];
            return false;
        }

        if (isset($request['mail']) === true && isset($request['password']) === true) {
            $RS = database::Query('SELECT * FROM users WHERE mail=:var1', array('var1' => $request['mail']), $stats);

            $user = new User();

            if ($user->encryptPassword($request['password'], $RS[0]['salt']) === $RS[0]['password']) {
                $user = new User($RS[0]['id']);

                if ($user->get('status') === 0) {
                    $this->errmsg = Texter::get('user')['notActivated'];
                } else {
                    $this->user = $user;
                }
            } else {
                $this->errmsg = 'not authenticated';
            }
        }

    }//end __construct()
}//end class
