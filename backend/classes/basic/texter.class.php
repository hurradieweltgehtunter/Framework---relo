<?php

class Texter
{
    private static $me = null;

    public function __construct()
    {
        global $_txtr;

        if (strpos(getcwd(), 'backend') !== false) {
            require_once '../data/config/texter.conf.inc';
        } else {
            require_once 'data/config/texter.conf.inc';
        }

        foreach ($_txtr as $key => $val) {
            $this->$key = $val;
        }
    }

    public static function get($key)
    {
        if (self::$me == null) {
            self::$me = new self();
        }

        return self::$me->$key;
    }
}
