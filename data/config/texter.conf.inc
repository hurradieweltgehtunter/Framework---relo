<?php

$_txtr['client']['makeAdmin'][0] = 'Zum Admin ernennen';
$_txtr['client']['makeAdmin'][1] = 'Adminrechte entziehen';

$_txtr['client']['sendNewPassword']     = 'Neues Password versendet';
$_txtr['client']['sendNewPasswordfail'] = 'Beim Versenden der Mail ist ein Fehler aufgetreten.';

$_txtr['user']['registrationNoMailFail']       = 'Keine E-Mail-Adresse angegeben';
$_txtr['user']['registrationPasswordTooShort'] = 'Dieses Passwort ist zu kurz';
$_txtr['user']['passwordNotEqualFail']     	   = 'Passwörter stimmen nicht überein.';
$_txtr['user']['registrationMailFail']         = 'Diese E-Mail-Adresse ist bereits registriert.';
$_txtr['user']['activationFail']               = 'Dieser Code ist nicht gültig.';
$_txtr['user']['accNotActivated']              = 'Dieser Account wurde nocht nicht aktiviert.';
$_txtr['user']['accNotFound']                  = 'Diese E-Mailadresse-Passwort-Kombination ist uns nicht bekannt.';

$_txtr['beuser']['newMessage']  = ':var1 :var2 hat Ihnen eine neue Nachricht geschickt.';
$_txtr['beuser']['newMessages'] = 'Sie haben :var1 neue Nachrichten.';

$_txtr['newPasswordMail']['subject'] = 'Dein neues Passwort';

$_txtr['Mailererror']['noRecipient'] = 'Mailversand nicht möglich. Kein Empfänger gesetzt.';
$_txtr['Mailererror']['noSubject']   = 'Mailversand nicht möglich. Kein Betreff gesetzt.';

$_txtr['api']['noMethodSet'] = 'no method set. aborting.';
$_txtr['api']['noActionSet'] = 'no action set. aborting.';

$_txtr['sysem']['unknownRequest'] = 'Unknown request on module :var1';

//UPLOAD MESSAGES
$_txtr['upload']['wrongFiletype']  = 'Falscher Dateityp. Nur jpg, png und gif erlaubt. Datei wurde nicht hochgeladen.';
$_txtr['upload']['fileTooBig']  = 'Deine Datei ist zu groß. Max. 1MB. Datei wurde nicht hochgeladen.';


// LOG MESSAGES
// User actions (1-99)
$_txtr['log'][1]  = 'User registriert.';
$_txtr['log'][2]  = 'Useraccount aktiviert.';
$_txtr['log'][3]  = 'User hat Daten aktualisiert.';
$_txtr['log'][4]  = 'User hat sich eingeloggt.';
$_txtr['log'][5]  = 'Neues Userbild hinzugefügt.';
$_txtr['log'][6]  = 'Neues Profilbild hinzugefügt.';
$_txtr['log'][7]  = 'Passwort aktualisiert.';
$_txtr['log'][8]  = '';
$_txtr['log'][9]  = '';
$_txtr['log'][10] = 'User Logout.';

// System actions (100-199)
$_txtr['log'][100] = 'Aktivierungsmail an User versendet.';
$_txtr['log'][101] = 'Neues Passwort verschickt.';
