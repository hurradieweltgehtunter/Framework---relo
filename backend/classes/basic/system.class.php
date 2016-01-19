<?php
date_default_timezone_set('Europe/Berlin');

class system
{
    public static $me = null;
    public $currentView = '';
    public $requestedView;
    public $jsContents = array();
    public $R = array();
    public $backend = true;
        
    private $pages = array();
    private $db;
    private $container = array();



    
    function __construct()
    {
        require_once('classes/basic/config.class.php');
    }
       
    public function load()
	{
        
        //load all requiered classes
        $system = $this;        
        include 'classes/classincluder.php';
    
        foreach($includes as $include) 
            include config::get('root') . 'classes/' . $include;
       
        if(!isset($this->db)) 
            $this->db = new database;

        system::$me = $this;     

        session_start();   
    }

    private function parseUrl()
    {
        if(request::get(0) == '')
            header('Location: ' . config::get('system')['startpage']);
        else
            $this->requestedView = request::get(0);
        
        
        if(!isset($_GET['m'])) 
            $_GET['m'] = '';
            
        $this->requestedModule = $_GET['m'];
    }

    public function output()
    {
        $this->parseUrl();

        if((isset($_COOKIE['relo_backend']) && beuser::verifyCookie($_COOKIE['relo_backend']) !== false) || (isset($_SESSION['beuser_id']) && $_SESSION['beuser']->isAdmin() === true))
        {
            // logged in
            $user = new beuser($_SESSION['beuser_id']);

            beuser::setCookie($user->get('id'), $user->get('password'));
            $_SESSION['beuser'] = $user;
            $_SESSION['beuserId'] = $user->get('id');

            $this->user = $user;

            if($this->requestedView == 'login')
                header('Location: ' . config::get('system')['startpage']);
        }
        else
        {
            // Not Logged In
            if(($this->requestedView == 'ajax' && (isset($_POST['module']) && ($_POST['module'] == 'login' ))))
            {   

            }
            else
            {    
                if($this->requestedView != 'login')
                {
                    header('Location: login');
                    exit();
                }
            }
        }

        switch($this->requestedView)
        {
            case 'imagemanager':
                $image = new Image(request::get(1), request::get(2));
                break;

            case 'upload':
                include ('classes/util/upload.class.php');
                $upload_handler = new UploadHandler();
                break;

            case 'ajax':
                if($_POST['module'] == 'system')
                    include('classes/basic/system.ajaxhandler.php');
                else if($_POST['module'] == 'autofill')
                    include('classes/util/autofill.php');
                else if($_POST['module'] == 'beuser')
                    include('classes/basic/beuser.ajaxhandler.php');
                else
                    include('classes/custom/' . $_POST['module'] . '/' . $_POST['module'] . '.ajaxhandler.php');
                break;

            case 'code':
                $this->requestedView = 'default';
                
            default:
                $this->renderContent($this->requestedView);

                /* if module is requested, execute it */
                if($this->requestedModule != '') 
                    $this->processModule();

                $this->OutputContainer = implode($this->container);
                
                //load view-specific template
                include './data/template/standard.tmpl.php';    

                break;
        }        
    }
    
    private function processModule() 
    {
        include './classes/custom/' . strtolower($this->requestedModule) . '/' . strtolower($this->requestedModule) . '.class.php';
        include './classes/custom/' . strtolower($this->requestedModule) . '/' . strtolower($this->requestedModule) . '.controller.php';
        $classname = strtolower($this->requestedModule);
        $module = new $classname;
        $this->container[] = $module->output();
    }
    
    private function renderContent($requestedView)
    {
        $container = array();
        $this->cssContents = [];
        $this->jsContents = [];

        /* einzubindende JavaScriptDateien */         
        if(file_exists('data/js/pagespecific/' . str_replace(' ', '_', strtolower($requestedView)) . '.js'))
            $this->jsContents[] = 'data/js/pagespecific/' . str_replace(' ', '_', strtolower($requestedView)) . '.js';    

        /* einzubindende CSS-Dateien */
        if(file_exists('data/css/pagespecific/' . str_replace(' ', '_', strtolower($requestedView)) . '.css'))
            $this->cssContents[] = 'data/css/pagespecific/' . str_replace(' ', '_', strtolower($requestedView)) . '.css';

        if(file_exists('data/views/' . str_replace(' ', '_', strtolower($requestedView)) . '.view.php'))
        {
            if(file_exists('data/views/' . str_replace(' ', '_', strtolower($requestedView)) . '.controller.php'))
            {
                ob_start();
                include 'data/views/' . str_replace(' ', '_', strtolower($requestedView)) . '.controller.php';
                $container[] = ob_get_contents();
                ob_end_clean();   
            }

            ob_start();
            require 'data/views/' . str_replace(' ', '_', strtolower($requestedView)) . '.view.php';
            $container[] = ob_get_contents();
            ob_end_clean();
        }    
    

        $this->container[] = implode($container);    

    }
}


?>