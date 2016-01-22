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

    public static function get($key, $substituteEntities = array())
    {
        if (self::$me == null) {
            self::$me = new self();
        }

        if ( is_int(strpos($key, '|')))
        {
            $keys = explode('|', $key);

            $return = self::$me->$keys[0];

            if(count($keys) === 2)
                $return = $return[$keys[1]];

        } else {
            $return = self::$me->$key;
        }

        if (count($substituteEntities) > 0) {
            $i = 1;

            foreach($substituteEntities as $substitution) {
                if (strpos($return, ':var' . $i) === false) {
                    echo 'TexterError: Replacement ":var' . $i . '" not found in "' . $return . '".';
                    continue;
                }
                $return = str_replace(':var' . $i, $substitution, $return);
            }
        }

        return $return;
    }
}
