<?php
$api = new Api($_REQUEST);
if ($api->errmsg === false) {
    switch ($_REQUEST['method']) {
        case 'put':
            switch ($_REQUEST['action']) {
                case 'test':
                break;
            }
        break;

        // end method put
        case 'get':
            switch ($_REQUEST['action']) {
                case 'getUser':
                    $status = 1;
                    $return = array('user' => $api->user);
                break;

                case 'getChat':
                    $status = 1;
                    $return = array(
                               'chat',
                               $api->user->getChat(),
                              );
                break;

                default:
                    $status = 0;
                    $return = array('error' => Texter::get('api|noActionSet'));
                break;
            }
        break;

        // end method get
        default:
            $status = 0;
            $return = array('error' => Texter::get('api|noMethodSet'));
        break;
    }//end switch

    echo json_encode(array('status' => $status, 'api' => $api->about, $return));
}//end if
