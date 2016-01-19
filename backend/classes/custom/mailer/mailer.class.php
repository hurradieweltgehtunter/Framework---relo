<?php




if(strpos(getcwd(), 'backend') !== false)
	require_once 'classes/util/swift/swift_required.php';
else
	require_once 'backend/classes/util/swift/swift_required.php';


class mailer
{
	public $subject = '';
	public $to; //either mailadress or array(mail=>displayname)
	public $body = '';
	public $attachments = array();
	public $result;

	public $_tpl_path = 'data/mailtemplates/';
	public $_tpl_filled;

	private $host;
	private $user;
	private $password;
	private $port;
	private static $me = null;


	function __construct() 
	{

	}

	public function set($key, $value)
	{
		$this->$key = $value;
	}

	public function addAttachment($filepath)
	{
		$this->attachments[] = $filepath;
	}

	public function send()
	{

		$empfaenger = $this->to;
		$betreff = $this->subject;
		$nachricht = $this->body;
		$header  = 'MIME-Version: 1.0' . "\r\n";
		$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$header .= 'From: ' . config::get('mailer')['from'] . "\r\n";

		mail($empfaenger, $betreff, $nachricht, $header);
		$this->result = 1;


		// Create the message
		/*$message = Swift_Message::newInstance()

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
	}

	public function processHTMLTemplate($filename, $substituteEntities)
	{
		$template = new Template($this->_tpl_path . $filename);
        foreach($substituteEntities as $key=>$val)
            $template->set($key, $val);
        return $template->render();
	}

	public static function sendRegistrationMail($user)
	{
		if (self::$me == null)
			self::$me = new mailer();
		
		$substituteEntities = array('title'=>'TEST', 'activationlink'=>$user->getActivationLink());

		self::$me->set('subject', 'superdupersubject');
		self::$me->set('to', $user->get('mail'));
		self::$me->set('body', self::$me->processHTMLTemplate('register.mail', $substituteEntities), 'text/html');
		self::$me->send();
		return self::$me->result;
		
	}

}


class Template
{
    protected $_file;
    protected $_data = array();

    public function __construct($file = null)
    {
        $this->_file = $file;
    }

    public function set($key, $value)
    {
        $this->_data[$key] = $value;
        return $this;
    }

    public function render()
    {
        extract($this->_data);
        ob_start();
        include($this->_file);
        return ob_get_clean();
    }
}

?>