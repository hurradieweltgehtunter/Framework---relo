<?php
class config
{
    private static $_me = null;

    public function __construct()
    {
        global $_CONFIG;

        if (strpos(getcwd(), 'backend') !== false) {
            require_once '../data/config/systemsettings.conf.inc';
        } else {
            require_once 'data/config/systemsettings.conf.inc';
        }

        foreach ($_CONFIG as $key => $val) {
            $this->$key = $val;
        }
    }//end __construct()

    public static function get($key)
    {
        if (self::$_me === null) {
            self::$_me = new self();
        }

        return self::$_me->$key;
    }//end get()
}//end class

