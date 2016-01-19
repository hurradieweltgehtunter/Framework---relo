<?php

class logging
{
    public static function log($logType, $data1='', $data2='')
    {
        
        global $system;

        $log_group = 0;
        /*
        log_group gruppiert Log-Einträge themenbezogen:
        1:  Angebot
            $data1 ist hier immer die Angebots-ID
        2:
        3:
        */

        //if frontend, user_id will be 0
        $user_id = user::get('id');
        
        switch($logType)
        {
            case 1:
                /*
                client logged in
                data1:  userID
                */
                break;

            case 2:
                /*
                Angebot wurde gespeichert
                data1:  Angebot-ID
                */
                $log_group = 1;
                break;

            case 3:
                /*
                PDF wurde erstellt
                data1:  Angebot-ID
                data2:  PDF-Typ (Angebot, Rechnung etc...)
                */
                $log_group = 1;
                break;

            case 4:
                /*
                Status eines Angebots geändert
                data1:  Angebot-ID
                data2:  Status alt | Status neu
                */
                $log_group = 1;
                break;

            case 5:
                /*
                Angebot angelegt
                data1:  Angebot-ID
                */
                $log_group = 1;
                break;
                
        }    
        
        database::Insert('INSERT INTO log (log_group, type, user_id, timestamp, data1, data2) VALUES (' . $log_group . ', ' . $logType . ', "' .$user_id . '", ' . time() . ', "' . mysql_real_escape_string($data1) . '", "' . mysql_real_escape_string($data2) . '")', $RS);
    }

    public static function logOutput($logID)
    {
        global $system;

        $output = '';
        database::Query('SELECT * FROM log WHERE id = ' . $logID . ';', $RS);
        $RS->get($DS);

        switch($DS['type'])
        {
            case 1:
                break;

            case 2:
                $output = 'Angebot gespeichert';
                break;

            case 3:
                $output = 'Angebots-PDF erstellt';
                break;

            case 4:
                $status = explode('|', $DS['data2']);
                database::Query('SELECT `text` FROM offer_status WHERE id = ' . $status[0], $RS);
                $RS->get($DS);

                database::Query('SELECT `text` FROM offer_status WHERE id = ' . $status[1], $RS);
                $RS->get($DS2);

                $output = 'Status des Angebots von "' . $DS['text'] . '" auf "' . $DS2['text'] . '" geändert';
                break;

            case 5:
                $output = 'Angebot erstellt';
                break;
        }

        return $output;
    }
    
    
    
}

?>