<?php

/**
 * Class to log all activities in the system
 *
 * Codes:
 *  1-99: User actions
 * 
 *  1: user registered
 *  2: user activated his account
 *  3: user updated his data
 *  4: User logged in
 *  5: User added a user image
 *  6: User updated his profile pic
 * 7: User updated his password
 * 10: User logout
 *
 *
 * 100-199 System actions
 *
 * 100: registration mail sent
 */

class Logging
{


    public static function log($logType, $user = false, $data1 = false)
    {
        $browser = new Browser();

        $text      = '';
        $timestamp = time();
        if ($user !== false) {
            $userId = $user->get('id');
        } else {
            $userId = 0;
        }

        $data = '';

        switch ($logType) {
            case 3:
                foreach ($data1 as $key => $value) {
                    $data .= ' '.$key.': '.$value.'|';
                }
                break;

            case 4:
                $data = 'Plattform: '.$browser->getPlatform().' | Browser: '.$browser->getBrowser().' | Version: '.$browser->getVersion().' | UserAgent: '.$browser->getUserAgent();
                break;

            default:
                
                break;
        }

        $text = Texter::get('log|'.$logType);

        database::Query('INSERT INTO log (`type`, `text`, `data`, `userId`, `timestamp`) VALUES ('.$logType.', :var1, :var2, :var3, :var4);', array('var1' => $text, 'var2' => $data, 'var3' => $userId, 'var4' => $timestamp));

    }//end log()
}//end class
