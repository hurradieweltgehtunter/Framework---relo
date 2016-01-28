<?php
/**
 * MailerClass.
 */
class Mailer
{

    /**
     * The subject of the mail.
     *
     * Set via mailer->et('subject')
     *
     * @var string
     */
    public $subject = '';

    /**
     * The recipient, the mail should be sent to.
     *
     * Set via mailer->set('to'). Can be a mailaddress or an array containing the display name: array('<display name>'=>'<mailaddress>')
     *
     * @var string
     */
    public $to;

    /**
     * The body of the mail.
     *
     * Set via mailer->set('body')
     *
     * @var string
     */
    public $body = '';

    /**
     * Possible Attachments to a mail.
     *
     * Add attachments via mailer->addAttachment(<filepath>)
     *
     * @var array
     */
    public $attachments = array();

    /**
     * Result of sending the message.
     *
     * If successful, result will contain the count of sent messages. If not, it will be 0.
     *
     * @var string
     */
    public $result;

    /**
     * Any errormessages.
     *
     * If no errors occur, it stays empty
     *
     * @var string
     */
    public $errmsg = '';

    /**
     * Path to the directory containing the mail templates.
     *
     * @var string
     */
    private $_tplPath = 'data/mailtemplates/';

    /**
     * Variable for self instacing.
     *
     * @var object
     */
    private static $_me = null;


    /**
     * Constructor of Class.
     *
     * @return object
     */
    public function __construct()
    {
        if (strpos(getcwd(), 'backend') !== false) {
            include_once 'classes/util/swift/swift_required.php';
        } else {
            include_once 'backend/classes/util/swift/swift_required.php';
        }

        if (strpos(getcwd(), 'backend') !== false) {
            $this->_file = '../'.$this->_file;
        }

    }//end __construct()


    /**
     * Sets a value.
     *
     * @param string $key   The name of the value to set.
     * @param string $value The value to set.
     *
     * @return void
     */
    public function set($key, $value)
    {
        $this->$key = $value;

    }//end set()


    /**
     * Adds an attachment.
     *
     * @param string $filepath The path to the file (including filename).
     *
     * @return void
     */
    public function addAttachment($filepath)
    {
        $this->attachments[] = $filepath;

    }//end addAttachment()


    /**
     * Sends a mail. Saves the outcome to @param result
     *
     * @return void
     */
    public function send()
    {
        if ($this->to === '') {
            $this->errmsg = Texter::get('mailererror|noRecipient');
        }

        if ($this->subject === '') {
            $this->errmsg = Texter::get('mailererror|noSubject');
        }

        $header  = 'MIME-Version: 1.0'."\r\n";
        $header .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
        $header .= 'From: '.config::get('mailer')['from']."\r\n";

        $this->result = mail($this->to, $this->subject, $this->body, $header);

        // Create the message
        /*
            $message = Swift_Message::newInstance()

            // Give the message a subject
            ->setSubject($this->subject)

            // Set the From address with an associative array
            ->setFrom(config::get('mailer')['from'])

            // Set the To addresses with an associative array
            ->setTo($this->to)

            // Give it a body
            ->setBody($this->body, 'text/html');


            if(count($this->attachments) > 0)
            {
            foreach($this->attachments as $attachment)
                $message->attach(Swift_Attachment::fromPath($attachment));
            }

            // Create the Transport
            $transport = Swift_SmtpTransport::newInstance(config::get('mailer')['host'], config::get('mailer')['port'])
              ->setUsername(config::get('mailer')['user'])
              ->setPassword(config::get('mailer')['password']);

            // Create the Mailer using your created Transport
            $mailer = Swift_Mailer::newInstance($transport);

            $logger = new Swift_Plugins_Loggers_EchoLogger();
            $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));


            // Send the message
            $this->result = $mailer->send($message);
        */

    }//end send()


    /**
     * Fills a mail template
     *
     * @param string $filename           The filename of the to be used mailtemplate.
     * @param array  $substituteEntities An array containing all substitutions used in the mailtemplate (array('$<fieldname>'=>'<value>')).
     *
     * @return string Returns the final mailbody
     */
    public function processHTMLTemplate($filename, $substituteEntities)
    {
        $template = new Template($this->_tplPath.$filename);
        foreach ($substituteEntities as $key => $val) {
            $template->set($key, $val);
        }

        return $template->render();

    }//end processHTMLTemplate()


    /**
     * Sends a Registration-Mail to a user
     *
     * When a new user is registering, he will get a registrationmail with an activation link to confirm the registration and activate the account
     *
     * @param object $user The user object to send to.
     *
     * @return integer Returns number of sent mails (1 or 0)
     */
    public static function sendRegistrationMail($user)
    {
        if (self::$_me === null) {
            self::$_me = new self();
        }

        $substituteEntities = array(
                               'title'          => 'TEST',
                               'activationlink' => $user->getActivationLink(),
                              );

        self::$_me->set('subject', 'superdupersubject');
        self::$_me->set('to', $user->get('mail'));
        self::$_me->set('body', self::$_me->processHTMLTemplate('register.mail', $substituteEntities), 'text/html');
        self::$_me->send();

        if(self::$_me->result == 1) {
            Logging::log(100, $user);
        }

        return self::$_me->result;

    }//end sendRegistrationMail()


    /**
     * Sends a new random password to a user
     *
     * @param object $user        The user object to send to.
     * @param string $newPassword The raw new password (use user->generateRandomPassword()).
     *
     * @return integer Returns number of sent mails (1 or 0)
     */
    public static function sendNewPasswordMail($user, $newPassword)
    {
        if (self::$_me === null) {
            self::$_me = new self();
        }

        $substituteEntities = array('newPassword' => $newPassword);

        self::$_me->set('subject', Texter::get('newPasswordMail|subject'));
        self::$_me->set('to', $user->get('mail'));
        self::$_me->set('body', self::$_me->processHTMLTemplate('newPassword.mail', $substituteEntities), 'text/html');
        self::$_me->send();

        if(self::$_me->result == 1) {
            Logging::log(101, $user);
        }

        return self::$_me->result;

    }//end sendNewPasswordMail()
}//end class

class Template
{

    /**
     * Path & filename of the templatefile to be used
     *
     * @var string
     */
    private $_file;

    /**
     * Array of plaholders and their corresponding values, which should be replaced in the template
     *
     * Set via Template->set($key, $value)
     *
     * @var string
     */
    private $_data = array();


    /**
     * Constructor of class Template
     *
     * This class is used to fill out a template. Placeholders in the template
     * are plain php variables ($variablename).
     *
     * @param string $file The full filepath to a mailtemplate.
     *
     * @return object templateobject
     */
    public function __construct($file = null)
    {
        $this->_file = $file;

    }//end __construct()


    /**
     * Sets values
     *
     * @param string $key   The name of the value to set.
     * @param string $value The value to set.
     *
     * @return object Returns the template object
     */
    public function set($key, $value)
    {
        $this->_data[$key] = $value;

        return $this;

    }//end set()


    /**
     * Renders the template
     *
     * All placeholders in the template will be replaced. See mailer->processHTMLTemplate().
     *
     * @return string Returns the final mailbody
     */
    public function render()
    {
        extract($this->_data);
        ob_start();
        include $this->_file;

        return ob_get_clean();

    }//end render()
}//end class
