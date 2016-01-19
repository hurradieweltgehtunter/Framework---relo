<?php

class system
{
    public static $me = null;
    public $currentView = '';
    public $requestedView;
    public $jsContents = array();
    public $R = array();
    public $user = false;

    private $pages = array();
    private $db;
    private $cronjob = false;
    private $container = array();

    
    
    function __construct()
    {
        require_once('backend/classes/basic/config.class.php');  
    }
        
    public function load()
	{
        include 'data/classes/classincluder.php';

        foreach($includes as $include) 
        {
            if(strpos($include, 'backend') === false)
                require_once config::get('root') . 'data/classes/' . $include;
            else
                require_once config::get('root') . $include;
        }
    }

    private function parseUrl()
    {
        if(request::get(0) == '')
            header('Location: ' . config::get('system')['startpage']);
        else
            $this->requestedView = request::get(0);     
    }

    public function output()
    {
        $this->parseUrl();
        
        if((isset($_COOKIE['auth_cookie']) && user::verifyCookie($_COOKIE['auth_cookie']) !== false) || (isset($_SESSION['user_id']) && $_SESSION['user_id'] != 0) || (isset($_SESSION['beuser_id']) && $_SESSION['beuser_id'] != 0) )
        {
            // logged in
            if(isset($_SESSION['beuser_id']))
                $_SESSION['user_id'] = $_SESSION['beuser_id'];
            
            $user = new user($_SESSION['user_id']);
            if($user->status !== false)
            {
                user::verifyPassword($user->get('mail'), $user->get('password'));

                $this->user = $user;

                if($this->requestedView == 'login')
                    header('Location: ' . config::get('system')['startpage']);    
            }
            else
            {
                session_destroy();
                unset($_COOKIE['auth_cookie']);
                header('Location: login');
                exit();
            }
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

        switch(request::get(0))
        {
            case 'imagemanager':
                $image = new Image(request::get(1), request::get(2));
                break;

            case 'ajax':
                include('data/classes/custom/' . $_POST['module'] . '/' . $_POST['module'] . '.ajaxhandler.php');
                break;

            case 'upload':
                include ('backend/classes/util/upload.class.php');
                $upload_handler = new UploadHandler();
                break;

            default:

                $this->renderContent($this->requestedView);
                $this->OutputContainer = implode($this->container);
                
                //load view-specific template
                include './data/template/standard.tmpl.php';
             
                break;
        }        
    }
            
    private function renderContent($requestedView)
    {
        $container = array();
        $this->jsContents = array();
        $this->cssContents = array();

        /* einzubindende JavaScriptDateien */
        if(file_exists('data/js/pagespecific/' . str_replace(' ', '_', strtolower($requestedView)) . '.js'))
            $this->jsContents[] = 'data/js/pagespecific/' . str_replace(' ', '_', strtolower($requestedView)) . '.js';  
        
        /* einzubinende CSS Datei*/
        if(file_exists('data/css/pagespecific/' . str_replace(' ', '_', strtolower($requestedView)) . '.css'))
            $this->cssContents[] = 'data/css/pagespecific/' . str_replace(' ', '_', strtolower($requestedView)) . '.css';
        
        //INCLUDE VIEW CONTROLLER
        if(file_exists('data/views/' . str_replace(' ', '_', strtolower($requestedView)) . '.controller.php'))
        {
            ob_start();
            include('data/views/' . str_replace(' ', '_', strtolower($requestedView)) . '.controller.php');
            $container[] = ob_get_contents();
            ob_end_clean();
        }

        //INCLUDE VIEW
        if(file_exists('data/views/' . str_replace(' ', '_', strtolower($requestedView)) . '.view.php'))
        {
            ob_start();
            include('data/views/' . str_replace(' ', '_', strtolower($requestedView)) . '.view.php');
            $container[] = ob_get_contents();
            ob_end_clean();
        }
        else
        {
            header('Location: /' . config::get('system')['subDir'] . config::get('system')['startpage'] . '');
            exit();
        }

        $this->container[] = implode($container);          
    }
}
?>