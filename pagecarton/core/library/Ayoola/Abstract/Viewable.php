<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Abstract_Viewable
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Table.php 4.26.2012 10.08am ayoola $
 */

/**
 * @see Ayoola_Exception
 * @see Ayoola_Object_Interface_Viewable
 */

require_once 'Ayoola/Exception.php';
require_once 'Ayoola/Abstract/Viewable.php';

/**
 * @category   PageCarton
 * @package    Ayoola_Abstract_Viewable
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Abstract_Viewable implements Ayoola_Object_Interface_Viewable
{

    /**
     *
     *
     * @var string
     */
	protected static $_objectTitle = '';

    /**
     * Data storage
     *
     * @var Ayoola_Storage
     */
	protected static $_objectStorage;

    /**
     * Data storage device
     *
     * @var string e.g. Session, File
     */
	protected static $_objectStorageDevice = 'Session';

    /**
     * Useful for lists
     *
     * @var Ayoola_Paginator
     */
	protected $_list;


    /**
     * Save translated strings
     *
     * @var array
     */
	protected static $_translated;

    /**
     * Content for the view method
     *
     * @var string XML Document
     */
	protected $_viewContent;

    /**
     * Markup template to sent from layout template for this view
     *
     * @var string
     */
	protected $_markupTemplate;

    /**
     * Whether to wrap _viewContent in a tag
     *
     * @var boolean
     */
	public $wrapViewContent = true;

    /**
     * Whether to hash the form elements name as an antibot mechanism
     *
     * @var boolean
     */
	public $hashFormElementName = true;

    /**
     *
     */
	protected $_viewParameter;


    /**
     * Integrated view, option and other parameters.
     *
     * var array
     */
	protected $_parameter = array();

    /**
     * Values to use to replace placeholders in the markup templates
     *
     * var array
     */
	protected $_objectTemplateValues = array();

    /**
     * Use to count the instance
     *
     * @var int
     */
	protected static $_counter = 0;

    /**
     * For editable div in layout editor
     *
     * @var string
     */
	protected static $_editableTitle;

    /**
     * For editable div in layout editor
     *
     * @var string
     */
	protected static $_editableHideTitle = "Hide";

    /**
     *
     *
     * @var array
     */
	protected static $_hooks = array();

    /**
     *
     *
     * @var array
     */
	protected static $_widgetOptions = array();

    /**
     *
     *
     * @var array
     */
	protected static $_parameterKeys;

    /**
     * The Options Available as a Viewable Object
     * This property makes it possible to use this same class
     * To serve all document available on the site
     *
     * @var array
     */
	protected $_classOptions;

    /**
     * The option value selected
     *
     * @var mixed
     */
	protected $_viewOption;

    /**	Object Name
     *
     * @var string
     */
	public $objectName;

    /**	Whether to translate widget inner conetent
     *
     * @var bool
     */
	public static $translateInnerWidgetContent;

    /**	Set to true if the init method has been run
     *
     * @var boolean
     */
	public $initiated = false;

    /**
     *
     * @var boolean
     */
	public static $openViewParametersByDefault = true;

    /**
     *
     * @var boolean
     */
	public static $editorViewDefaultToPreviewMode = false;

    /** The tag of the element used in preparing the view content.
     *
     * @var string
     */
	protected static $_viewContentElementContainer = 'div';

    /**
     *
     * @var array
     */
	protected static $_authLevelOptions;
	
    /**
     * Singleton instance
     *
     * @var self
     */
	protected static $_instance;

    /**
     *
     * @var array
     */
	protected static $_wrapperOptions;


    /**
     * My User Agent Name
     *
     * @var string
     */
	public static $userAgent = 'Mozilla/5.0 ( compatible; pagecarton-bot/0.1; +http://pagecarton.org/bot/ )';

	/**
     * constructor
     *
     */
	public function __construct( $parameter = null )
    {
        try
        {
            self::setHook( $this, __FUNCTION__, $parameter );

            if( ! $parameter )
            {
                if( Ayoola_Application::isXmlHttpRequest() || Ayoola_Application::isClassPlayer() ){ return null; }
            }
            if( is_array( $parameter ) ){ $this->setParameter( $parameter ); }
        //    var_export( $this->getParameter( 'device_whitelist' ) );
        //    var_export( self::deviceIsAllowed() );
    
            if( ! $this->deviceIsAllowed() )
            {
                return false;
            }
    
        //	self::v( get_class( $this ) );
            $this->initOnce();
        }
        catch( Ayoola_Abstract_Exception $e  )
        {
            //  now hooks can avoid execution of a class init method
        }
		static::$_counter++;
    }

    /**
     * Returns a singleton Instance
     *
     * @param void
     * @return self
     */
    public static function getInstance()
    {
        $class = get_called_class();
    //    var_export( $class );
        if( empty( self::$_instance[$class] ) ){ self::$_instance[$class] = new $class( array( 'no_init' => true ) ); }
		return self::$_instance[$class];
    } 	

	/**
     * Check if a device is allowed to view
     *
     */
	public function deviceIsAllowed()
    {
        if( $this->getParameter( 'device_whitelist' ) )
        {
        //    var_export( PageCarton_Device::getInstance()->selectOne( null, array( 'device_name' => $this->getParameter( 'device_whitelist' ) ) ) );
            if( $device = PageCarton_Device::getInstance()->selectOne( null, array( 'device_name' => $this->getParameter( 'device_whitelist' ) ) ) )
            {
                foreach( $device['environment_key'] as $key => $x )
                {
                    switch( $device['equator'][$key] )
                    {
                        case 0:
                            if( is_int( stripos( $_SERVER[$device['environment_key'][$key]], $device['environment_value'][$key] )) )
                            {
                                return false;
                            }
                        break;
                        case 1:
                            if( ! is_int( stripos( $_SERVER[$device['environment_key'][$key]], $device['environment_value'][$key] )) )
                            {
                                return false;
                            }
                        break;
                        case 2:
                            if( ! preg_match( '/' . $device['environment_value'][$key] . '/i', $_SERVER[$device['environment_key']][$key] ) )
                            {
                                return false;
                            }
                        break;

                    }
                }
            }
        }
        elseif( $this->getParameter( 'device_blacklist' ) )
        {
            if( $device = PageCarton_Device::getInstance()->selectOne( null, array( 'device_name' => $this->getParameter( 'device_blacklist' ) ) ) )
            {
                foreach( $device['environment_key'] as $key => $x )
                {
                    switch( $device['equator'][$key] )
                    {
                        case 0:
                            if( ! is_int( stripos( $_SERVER[$device['environment_key'][$key]], $device['environment_value'][$key] )) )
                            {
                                return false;
                            }
                        break;
                        case 1:
                            if( is_int( stripos( $_SERVER[$device['environment_key'][$key]], $device['environment_value'][$key] )) )
                            {
                                return false;
                            }
                        break;
                        case 2:
                            if( preg_match( '/' . $device['environment_value'][$key] . '/i', $_SERVER[$device['environment_key']][$key] ) )
                            {
                                return false;
                            }
                        break;

                    }
                }
            }

        }
        return true;
    }

    /**
     * Method to set up a hook action in object. Hooks PageCarton_Widget to another PageCarton_Widget 
     *
     * @param PageCarton_Widget - Class to hook to this class
     * @param string method where the hook is being set up for
     * @param mixed Extra data passed to the PageCarton_Widget. This could be filtered in the hook class
     * 
     * @return boolean True on success
     *
     */
	public static function setHook( Ayoola_Abstract_Playable $object, $method, & $data )
	{
        foreach( self::getHooks() as $hook )
        {
            if( ! Ayoola_Loader::loadClass( $hook ) )
            {
                continue;
            }
            $hook::hook( $object, $method, $data );
        }
        return true;
    }

    /**
     *
     *
     */
	public static function getHooks()
	{
		$class = get_called_class();
//		var_export( $class );
		if( isset( static::$_hooks[$class] ) && null !== static::$_hooks[$class] )
		{
			return static::$_hooks[$class];
		}
		$hooks = array();
		if( $all = PageCarton_Hook::getInstance()->select( null, array( 'class_name' => array( $class, '*' ) ) ) )
		{
			foreach( $all as $each )
			{
				if( ! Ayoola_Loader::loadClass( $each['hook_class_name'] ) )
				{
					continue;
				}
				if( ! method_exists( $each['hook_class_name'], 'hook' ) )
				{
					continue;
				}
			//	if( $each['hook_class_name'] )
				$hooks[] = $each['hook_class_name'];
			}

		}
	//	var_export( $hooks );
		static::$_hooks[$class] = $hooks;
		return static::$_hooks[$class];
	}

    /**
     *
     *
     */
	protected function initOnce()
	{

		if( ! $this->initiated && ! $this->getParameter( 'no_init' ) ) //	compatibility
		{
	//		var_export( $this->initiated );
//			var_export( get_class( $this ) );
		//	self::v( $this->getParameter( 'no_init' ) );

			$this->initiated = true;

			if( $this->getParameter( 'url_blacklist' ) || $this->getParameter( 'url_whitelist' ) )
			{
				$currentUrl = rtrim( Ayoola_Application::getRuntimeSettings( 'real_url' ), '/' ) ? : '/';
				switch( $currentUrl )
				{
					case '/tools/classplayer':
					case '/object':
					case '/pc-admin':
					case '/widgets':
					case '/widget':
			//		case true:
						//	Do nothing.
						//	 had to go through this route to process for 0.00
				//		var_export( __LINE__ );
						if( @$_REQUEST['url'] && @$_REQUEST['name'] || ( @$_REQUEST['rebuild_widget'] ) )
						{
							$currentUrl = $_REQUEST['url'];
							$editorMode = true;
							break;
						}
					//	return false;
					break;
					default:
		//      var_export( $currentUrl );
					break;
				}
			}
			if( $this->getParameter( 'url_blacklist' ) )
			{
				$list = $this->getParameter( 'url_blacklist' );
				$list = array_map( 'trim', explode( ',', $list ) );
			//	var_export( $currentUrl );
				if( in_array( $currentUrl, $list ) )
				{
					return false;
				}
			}
			elseif( $this->getParameter( 'url_whitelist' ) )
			{
				$list = $this->getParameter( 'url_whitelist' );
				$list = array_map( 'trim', explode( ',', $list ) );
				if( ! in_array( $currentUrl, $list ) )
				{
					return false;
				}
			}
		//	var_export( __LINE__ );
			if( $this->init() )
			{

			}
		}
	}

    /**
     * default the class initialization process
     *
     */
	protected function init(){ }

    /**
     * shares the profile
     *
     */
	public static function getShareLinks( $fullUrl )
    {
		return '
				<!-- I got these buttons from simplesharebuttons.com -->
				<style type="text/css">
					.share-buttons img {
					width: 35px;
					padding: 5px;
					border: 0;
					box-shadow: 0;
					display: inline;
					}
				</style>
				<div class="share-buttons" >
					<!-- Facebook -->
					<a href="http://www.facebook.com/sharer.php?u=' . $fullUrl . '" target="_blank" title="Share on Facebook"><img src="' . Ayoola_Application::getUrlPrefix() . '/social-media-icons/facebook.png" alt="Facebook" /></a>

					<!-- Twitter -->
					<a href="http://twitter.com/share?url=' . $fullUrl . '&text=I think you might like this...&hashtags=" target="_blank" title="Share on Twitter"><img src="' . Ayoola_Application::getUrlPrefix() . '/social-media-icons/twitter.png" alt="Twitter" /></a>

					<!-- Google+ -->
					<a href="https://plus.google.com/share?url=' . $fullUrl . '" target="_blank" title="Share on Google+"><img src="' . Ayoola_Application::getUrlPrefix() . '/social-media-icons/google-plus.png" alt="Google" /></a>

					<!-- LinkedIn -->
					<a href="http://www.linkedin.com/shareArticle?mini=true&url=' . $fullUrl . '" target="_blank" title="Share on LinkedIn"><img src="' . Ayoola_Application::getUrlPrefix() . '/social-media-icons/linkedin.png" alt="LinkedIn" /></a>

					<!-- Email -->
					<a href="mailto:?Subject=Check out this link...&Body=I%20saw%20this%20and%20thought%20of%20you!%20 ' . $fullUrl . '" title="Share via E-mail"><img src="' . Ayoola_Application::getUrlPrefix() . '/social-media-icons/email.png" alt="Email" /></a>
				</div>
		';
	}

    /**
     * Sets the list
     *
     * @param Ayoola_Paginator
     */
	public function setList( Ayoola_Paginator $list = null )
    {
		if( is_null( $list ) ){ $list = $this->createList(); }
		$this->_list = $list; 
    }

    /**
     * Returns the storage object
     *
     * @param string Unique ID for Namespace
     * @return Ayoola_Storage
     */
	public static function getObjectStorage( $storageInfo = null )
    {
		$id = null;
		$device = static::$_objectStorageDevice;
		if( is_string( $storageInfo ) )
		{
			$id = $storageInfo;
		}
		elseif( is_array( $storageInfo ) )
		{
			$id = $storageInfo['id'];
			$device = @$storageInfo['device'] ? : $device;
			$timeOut = @$storageInfo['time_out'];
		}
		$id .= Ayoola_Application::getApplicationNameSpace();
		if( isset( static::$_objectStorage[$id] ) )
		{
			return static::$_objectStorage[$id];
		}
		static::$_objectStorage[$id] = new Ayoola_Storage();
//		var_export( get_called_class() );
		static::$_objectStorage[$id]->storageNamespace = get_called_class() . '-' . $id;
//		var_export( static::$_objectStorage[$id]->storageNamespace );
		static::$_objectStorage[$id]->timeOut = @$timeOut;
		$device ? static::$_objectStorage[$id]->setDevice( $device ) : null;
		return static::$_objectStorage[$id];
    }

    /**
     * Clean HTML
     *
     */
	public static function cleanHTML( $text, $strict = false )
    {
        $allowed = '<a> <address> <em> <strong> <b> <i> <big> <small> <sub> <sup> <cite> <code> <img> <ul> <ol> <li> <dl> <lh> <dt> <dd> <br> <p> <table> <th> <td> <tr> <pre> <blockquote> <nowiki> <h1> <h2> <h3> <h4> <h5> <h6> <hr> <select> <option> <input>';
		$text = strip_tags( $text, $allowed );

		//  remove attributes?
		if( $strict )
		{
    		$regex = "#<(/?\w+)\s+[^>]*>#is";
    		$text = preg_replace( $regex, '<${1}>', $text );
		}
        $text = strip_tags( $text, $allowed );
        $text = preg_replace( '|\<([^>]*) on([^><]*)\>|i', '<$1 data-on-$2>', $text );
        $text = preg_replace( '|\<([^>]*)javascript:([^><]*)\>|i', '<$1#$2>', $text );
		return $text;
	}

    /**
     * Sends email
     *
     */
	public static function sendMail( array $mailInfo )
    {
        try
        {

            self::setHook( static::getInstance(), __FUNCTION__, $mailInfo );

            if( empty( $mailInfo['body'] ) )
            {
                return false;
            }
            $realBody = $mailInfo['body'];
            if( empty( $mailInfo['from'] ) )
            {
                $mailInfo['from'] = '"' . htmlspecialchars( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Application::getDomainName() ) . '" <no-reply@' . Ayoola_Application::getDomainName() . '>' . "";
            }

            if( empty( $mailInfo['subject'] ) )
            {
                $mailInfo['subject'] = 'E-mail Notification';
            }
            $header = 'From: ' . $mailInfo['from'] . "\r\n";
            $header .= "Return-Path: " . @$mailInfo['return-path'] ? : $mailInfo['from'] . "\r\n";

            if( ! empty( $mailInfo['bcc'] ) )
            {
                $header .= "bcc: {$mailInfo['bcc']}\r\n";
            //	var_export( $header );
            }
            if( ! empty( $mailInfo['html'] ) || strip_tags( $mailInfo['body'] ) != $mailInfo['body'] )
            {
                if( stripos( $mailInfo['body'], '<body>' ) === false )
                {
                    $mailInfo['body'] = '<body>' . $mailInfo['body'] . '</body>';
                }
                $mailInfo['body'] = Ayoola_Page_Editor_Text::addDomainToAbsoluteLinks( $mailInfo['body'] );
                $realBody = $mailInfo['body'];
                if( stripos( $mailInfo['body'], '<html>' ) === false )
                {
                    $styleFile = Ayoola_Loader::checkFile( 'documents/css/pagecarton.css' );
                    $mailInfo['body'] = '
                                            <html>
                                                <head>
                                                    <style>
                                                        ' . file_get_contents( $styleFile ) . '
                                                    </style>
                                                </head>
                                                ' . $mailInfo['body'] . '
                                            </html>';
                }
                $header .= "MIME-Version: 1.0\r\n";
                $header .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            }
            if( ! empty( $mailInfo['to'] ) )
            {
        //		var_export( $mailInfo );
                $sent = mail( $mailInfo['to'], $mailInfo['subject'], $mailInfo['body'], $header );
            }
            $mailInfo['to'] = array_map( 'trim', explode( ',', $mailInfo['to'] ) );
            $mailInfo['cc'] = array_map( 'trim', explode( ',', $mailInfo['cc'] ) );
            $mailInfo['bcc'] = array_map( 'trim', explode( ',', $mailInfo['bcc'] ) );
            $mailInfo['body'] = $realBody;
        //    var_export( $mailInfo );
            Application_Notification::getInstance()->insert( $mailInfo );
            return $sent;
        }
        catch( Ayoola_Abstract_Exception $e  )
        {
            //  now hooks can avoid execution of a class init method
        }

    }

    /**
     * Fetches a remote link
     *
     * @param string Link to fetch
     * @param array Settings
     */
    public static function fetchLink( $link, array $settings = null )
    {
	//	self::V( $link );
		$key = md5( $link . serialize( $settings ) );
	//	$storage =  static::getObjectStorage( $key )
		$storage = self::getObjectStorage( array( 'id' => $key, 'device' => 'File', 'time_out' => 10000, ) );
		if( ! $response = $storage->retrieve() )
		{
	//	self::V( $response );
			if( ! function_exists( 'curl_init' ) )
			{
				//trigger_error( __METHOD__ . ' WORKS BETTER WHEN CURL IS ENABLED. PLEASE ENABLE CURL ON YOUR SERVER.' );
				return false;
			//	return file_get_contents( $link );
			}
			$request = curl_init( $link );
	//		curl_setopt( $request, CURLOPT_HEADER, true );
			curl_setopt( $request, CURLOPT_URL, $link );

            //	dont check ssl
            if( empty( $settings['verify_ssl'] ) )
            {
                curl_setopt( $request, CURLOPT_SSL_VERIFYHOST, 0 );
                curl_setopt( $request, CURLOPT_SSL_VERIFYPEER, 0 );
            }

			curl_setopt( $request, CURLOPT_USERAGENT, @$settings['user_agent'] ? : self::$userAgent );
			curl_setopt( $request, CURLOPT_AUTOREFERER, true );
			curl_setopt( $request, CURLOPT_REFERER, @$settings['referer'] ? : $link );
			if( @$settings['destination_file'] )
			{
			//	var_export( $settings );
				$fp = fopen( $settings['destination_file'], 'w' );
				curl_setopt( $request, CURLOPT_FILE, $fp );
				curl_setopt( $request, CURLOPT_BINARYTRANSFER, true );
				curl_setopt( $request, CURLOPT_HEADER, 0 );
			}
			else
			{
				curl_setopt( $request, CURLOPT_RETURNTRANSFER, true );
			}
	//		curl_setopt( $request, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $request, CURLOPT_FOLLOWLOCATION, @$settings['follow_redirect'] === false ? false : true ); //	By default, we follow redirect
			curl_setopt( $request, CURLOPT_CONNECTTIMEOUT, @$settings['connect_time_out'] ? : 1 );	//	Max of 1 Secs on a single request
			curl_setopt( $request, CURLOPT_TIMEOUT, @$settings['time_out'] ? : 2 );	//	Max of 1 Secs on a single request
			if( @$settings['post_fields'] )
			{
				curl_setopt( $request, CURLOPT_POST, true );
			//	var_export( $request );
			//	var_export( $settings['post_fields'] );
				curl_setopt( $request, CURLOPT_POSTFIELDS, $settings['post_fields'] );
            }
            elseif( @$settings['post'] )
            {
				curl_setopt( $request, CURLOPT_POST, true );
            }
			if( @$settings['raw_response_header'] )
			{
			//	var_export( $settings );
				$headerBuff = fopen( '/tmp/headers' . time(), 'w+' );
				//	var_export( $headerBuff );
				curl_setopt( $request, CURLOPT_WRITEHEADER, $headerBuff );
			}
			if( is_array( @$settings['http_header'] ) )
			{
				curl_setopt( $request, CURLOPT_HTTPHEADER, $settings['http_header'] );
			}
			$response = curl_exec( $request );
			$responseOptions = curl_getinfo( $request );

				// close cURL resource, and free up system resources
			curl_close( $request );
		//	var_export( htmlentities( $response ) );

			 //	var_export( $responseOptions );
		//	exit( var_export( $responseOptions ) );
			//	var_export( $settings['post_fields'] );
		 //	if( ! $response || $responseOptions['http_code'] != 200 ){ return false; }
			if( empty( $settings['return_error_response'] ) )
			{
			//	var_export( $response );
			//	var_export( $responseOptions );
				 if( $responseOptions['http_code'] != 200 ){ return false; }
			}
			if( @$settings['return_as_array'] == true )
			{
				if( @$settings['raw_response_header'] )
				{
				//	var_export( $headerBuff );
					rewind($headerBuff);
					$headers = stream_get_contents( $headerBuff );
					@$responseOptions['raw_response_header'] = $headers;
				}
				$response = array( 'response' => $response, 'options' => $responseOptions );
			}
			$storage->store( $response );
		}

 		//	var_export( $response );
		return $response;
    }

    /**
     * Returns the list
     *
     * @param void
     * @return Ayoola_Paginator
     */
	public function getList()
    {
		if( is_null( $this->_list ) ){ $this->setList(); }
		return $this->_list->view();
    }

    /**
     * Check if I have privilege to access a resource
     *
     * param array Allowed Access Levels
     * return boolean
     */
	public static function hasPriviledge( $allowedLevels = null, array $options = null )
	{
//		var_export( Ayoola_Application::getUserInfo( 'access_level' ) );
		//var_export( intval( Ayoola_Application::getUserInfo( 'access_level' ) ) );
		if( is_numeric( $allowedLevels ) )
		{
			$allowedLevels = array( $allowedLevels );
		}

		if( is_array( $allowedLevels ) )
		{
			$allowedLevels = array_map( 'intval', $allowedLevels );
		}
		else
		{
			$allowedLevels = array();
		}
//		var_export( in_array( 0, $allowedLevels ) );
		$myLevel = intval( Ayoola_Application::getUserInfo( 'access_level' ) );
		//	var_export( $allowedLevels );
		//	var_export( Ayoola_Application::getUserInfo( 'email' ) );
		//	var_export( Ayoola_Application::$GLOBAL['whitelist_email_address'] );
		//	var_export( $myLevel );
	//	var_export( ( in_array( 0, $allowedLevels ) && ! @$options['strict'] ) );
	//	var_export( in_array( $myLevel, $allowedLevels ) );
	//	var_export( $myLevel === 99 );
		$username = trim( strtolower( Ayoola_Application::getUserInfo( 'username' ) ) );
		if(
			( $myLevel === 99 && ! @$options['strict'] ) // Super user except if its strict
		|| ( in_array( 98, $allowedLevels ) && $username && $username === strtolower( @Ayoola_Application::$GLOBAL['username'] )  && ! @$options['strict'] ) //	Profile owner means he is authorized
		|| ( in_array( 97, $allowedLevels ) && $username && ( in_array( strtolower( Ayoola_Application::getUserInfo( 'email' ) ), array_map( 'strtolower', @Ayoola_Application::$GLOBAL['post']['whitelist_email_address'] ? : array() ) ) || $username === strtolower( @Ayoola_Application::$GLOBAL['post']['username'] ) ) ) //	We were invited to view a post/article
		|| ( in_array( 0, $allowedLevels ) && ! @$options['strict'] ) //	Public means everyone is welcome except if its strict
		|| in_array( $myLevel, $allowedLevels ) //	We are explicitly allowed
//		|| ( in_array( $_SERVER['REMOTE_ADDR' ], array( '127.0.0.1', '::1' ) ) && ! @$options['strict'] ) //	Localhost
		)
		{
		//	var_export( strtolower( @Ayoola_Application::$GLOBAL['username'] ) );
		//	var_export( $username );
			//	We are either a super user, or has a listed allowed user or the resource is public
			return true;
		}
		//	var_export( $allowedLevels );
		//	var_export( $myLevel );
	//		var_export( strtolower( @Ayoola_Application::$GLOBAL['username'] ) );
	//		var_export( $username );


	//	else		if
		//	MyLevel now has capabilities of inheriting from  other levels
		$authLevel = new Ayoola_Access_AuthLevel;
		$authLevel = $authLevel->selectOne( null, array( 'auth_level' => $myLevel ) );
		require_once 'Ayoola/Filter/SelectListArray.php';
	//	if( $myLevel == 5 )
		{
		//	var_export( $authLevel );
		//	exit();
		}
		$authLevel['parent_access_level'] = @$authLevel['parent_access_level'] ? : array();
	//	var_export( $authLevel );
		foreach( $authLevel['parent_access_level'] as $each )
		{
			if( $each < 10 )
			{
			//	if( $myLevel == 5 )
				{
				//	var_export( $each );
				//	exit();
				}
				if( in_array( $each, $allowedLevels ) )
				{
					return true;
				}
			}
		}
	//	if( $myLevel == 5 )
		{
		//	var_export( $authLevel );
	//		exit();
		}
		$access = new Ayoola_Access();
		$userInfo = $access->getUserInfo();
		@$userInfo['profiles'] = is_array( $userInfo['profiles'] ) ? $userInfo['profiles'] : array();

		//	$previous = Ayoola_Page::setPreviousUrl( '' );
	//		var_export( $userInfo );

		if( in_array( 98, $allowedLevels ) && ! empty( Ayoola_Application::$GLOBAL['profile_url'] ) && is_array( $userInfo['profiles'] ) && in_array( Ayoola_Application::$GLOBAL['profile_url'], $userInfo['profiles'] )  && ! @$options['strict'] ) //	profile owner
		{
			return true;
		}

		if( in_array( 1, $allowedLevels ) && intval( $myLevel ) > 1 && ! @$options['strict'] ) //	Deleted user levels should at least have the level of a standard user
		{
            //  this is making all user level have parent access of standard user
			return true;
		}


		//	No way jose
		return false;
	}

    /**
     * Used by administrators to inspect variables for debugging purposes.
     *
     */
	public static function v( $variable )
    {
		if( self::hasPriviledge( 98 ) || @$_REQUEST['pc_show_error']  )
		{
			var_export( $variable );
			echo "\r\n";
		}

	}

    /**
     * Do a one time parameter filter within widgets
     *
     */
	public static function filterParameters( & $parameters )
    {
        //  to be executed within the widget class
    }

    /**
     * Get site locale
     *
     */
	public static function getLocale()
    {
        $defaultLocale = PageCarton_Locale_Settings::retrieve( 'default_locale' );
        $options = PageCarton_Locale_Settings::retrieve( 'locale_options' );
        if( ! is_array( $options ) || ! in_array( 'auto_detect_user_locale', $options ) )
        {
            return $defaultLocale;
        }
    //    var_export();
        $storage = self::getObjectStorage( array( 'id' => 'locale' . $locale, 'time_out' => 1000000, ) );
        if( ! $locale = $storage->retrieve() )
        {
            $locale = $defaultLocale;
            if( ! $languages = PageCarton_Locale::getInstance()->select() )
            {
                return $locale;
            }

            $availableLocale = array();
            foreach( $languages as $each )
            {
                $availableLocale[] = $each['locale_code'];
            }
            //	system locale
        //	$locale = setlocale( LC_ALL, 0 );
    //		var_export( $localeSettings );

        //	var_export( $languages );
        //	var_export( $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
            $getPreferredLanguage = function ( array $available_languages, $http_accept_language = null )
            {
                if( is_null( $http_accept_language ) ) 
                {
                    if ( ! isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) 
                    {
                        return array();
                    }
                    $http_accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
                }
                $available_languages = array_flip( $available_languages );

                $langs;
                preg_match_all(' ~([\w-]+)(?:[^,\d]+([\d.]+))?~', strtolower( $http_accept_language ), $matches, PREG_SET_ORDER);
                foreach($matches as $match) {

                    list($a, $b) = explode('-', $match[1]) + array('', '');
                    $value = isset($match[2]) ? (float) $match[2] : 1.0;

                    if(isset($available_languages[$match[1]])) {
                        $langs[$match[1]] = $value;
                        continue;
                    }

                    if(isset($available_languages[$a])) {
                        $langs[$a] = $value - 0.1;
                    }

                }
                $langs ? arsort( $langs ) : null;

                return $langs ? : array();
            };
            if( $allPreferred = $getPreferredLanguage( $availableLocale ) )
            {
                $locale = array_shift( array_keys( $allPreferred ) );
            }
            $storage->store( $locale );
        //	var_export( $locale );
        }
        return $locale;
	}

    /**
     * Translate a string of text.
     *
     */
	public static function __( $string )
    {
        $options = PageCarton_Locale_Settings::retrieve( 'locale_options' );
        if( ! self::getLocale() && ! @in_array( 'auto_translate', $options ) )
		{
			//	was slowing down app
			return $string;
        }
        $id = sha1( json_encode( $string ) . 'ccc' . json_encode( PageCarton_Locale_Settings::retrieve() ) );
        if( isset( static::$_translated[$id] ) )
        {
            return static::$_translated[$id];
        }
        $translationStorage = self::getObjectStorage( array( 'id' => 'translation' . $id . 'dddss' . self::getLocale(), 'device' => 'File', 'time_out' => 1000000, ) );  
    //	var_export( json_encode( $string ) );
    //	var_export( $options );
    //	var_export( $id );
    //	var_export( $translationStorage->retrieve() );
        if( $stored = $translationStorage->retrieve() )
        {
        //    var_export( $stored );
            static::$_translated[$id] = $stored; 
        //    return $stored;
        }   
        if( is_array( $string ) )
        {
            foreach( $string as $key => $eachString )
            {
                $string[$key] = self::__( $eachString );
            }
            static::$_translated[$id] = $string; 
            $translationStorage->store( $string );
            return $string;
        }
		if( preg_match( '#(^[\s]*\{\{\{[^\{\}\s]*\}\}\}[\s]*$)|(^[\s]*\%[^%}\s]*\%[\s]*$)|(^[^a-zA-Z]+$)#', $string ) )
		{
        //	var_export( $string );
            $translationStorage->store( $string );
			return $string;
		}
		if( ! trim( $string ) || strpos( $string, '<style>' ) !== false || strpos( $string, '<script>' ) !== false || is_numeric( $string ) )
		{
            static::$_translated[$id] = $string; 
            $translationStorage->store( $string );
            return $string;
		}
		if( strip_tags( $string ) != $string )
		{
			$allStrings = preg_split( '#(\<[^<>]+\>)|(<!--)|(-->)|([\s]*\{\{\{[^\{\}\s]*\}\}\}[\s]*)#misU', $string );
		//	$allStrings = preg_split( '#<(?!a|span|/a|/span)[\s]?[^<>]*>#', $string );
		//	preg_match_all( '#[^<>]*(\<(?!a|span|/a|/span)[^<>]*>)?[^<>]*(\<(?!a|span|/a|/span)[^<>]*>)?[^<>]*#', $string, $matches );
	    //	var_export( $allStrings );
			if( count( $allStrings ) > 1 )
			{
				foreach( $allStrings as $each )
				{
                    if( ! trim( str_ireplace( '&nbsp;', ' ', $each ), "\r\n\t\s " ) ){ continue; }
                //    if( ! trim( $each, "\r\n\t\s " ) ){ continue; }
                //    var_export( $each );
					$translated = self::__( $each );
					$string = str_ireplace( '>' . $each . '<', '>' . $translated . '<', $string );
				}
                if( preg_match_all( '#(<input([^<>]*)placeholder="([a-zA-Z0-9 \.]*)"([^<>]*)>)#', $string, $allPlaceholders ) )
                {
                    foreach( $allPlaceholders[2] as $eachPlaceholder)
                    {
                        $translated = self::__( $eachPlaceholder );
                        $string = str_ireplace( 'placeholder="' . $eachPlaceholder . '"', 'placeholder="' . $translated . '"', $string );
                    }
                //	var_export( $string );
                    return $string;
                }
             //var_export( $string );
                static::$_translated[$id] = $string; 
                $translationStorage->store( $string );
				return $string;
			}
		}
    //	var_export( $string );
	//	var_export( $arr );
	//	$string = trim( $string );

		do
		{
            if( ! $locale = self::getLocale() )
            {
            //    var_export( $options );
                if( ! is_array( $options ) || ! in_array( 'autosave_new_words', $options ) )
                {
                    continue;
                }
            }
            //	var_export( $string );
            //	$translation = PageCarton_Locale_Translation::getInstance();
        
            //  don't store trimmed because of some valid spaces around html
            //	$string = trim( $string );

			//	cache is workaround because of insert not active until next load
			//	was causing double inserting of words when the words are double on same page
			$stringStorage = self::getObjectStorage( array( 'id' => 'stringInssfssso' . $id . 'dddss', 'device' => 'File', 'time_out' => 100000, ) );     
			if( ! $stringInfo = $stringStorage->retrieve() )
			{
                $words = PageCarton_Locale_OriginalString::getInstance();
                $url = Ayoola_Application::getRuntimeSettings( 'real_url' );
                switch( $url )
                {
                    case '/widgets':
                    case '/object':
                    case '/tools/classplayer':
                        $url = '/widgets/' . $_SERVER['HTTP_AYOOLA_PLAY_CLASS'];
                    break;
                }
            //    var_export( $string );
				if( ! $stringInfo = $words->selectOne( null, array( 'string' => $string ) ) )
				{
                    $trimmedString = trim( $string, " \t\r\n" );
                    if( ! $stringInfo = $words->selectOne( null, array( 'string' => $trimmedString ) ) ) 
                    {
                        //	var_export( $string );
                        $options = PageCarton_Locale_Settings::retrieve( 'locale_options' );
                        if( is_array( $options ) && in_array( 'autosave_new_words', $options ) )
                        {
                            if( 
                                false !== strpos( $string, '<' ) 
                                || false !== strpos( $string, '[]' ) 
                                || false !== stripos( $string, '%FIELD%' ) 
                                || false !== strpos( $string, '%KEY%' ) 
                                || ( strpos( $string, '[' ) && ( strpos( $string, '[' ) - strpos( $string, ']' ) < 3 ) )
                                || ( substr_count( $string, '{' ) > 2 ) 
                                || ( strlen( $string ) < 4 ) 
                                || false !== strpos( $string, DS ) 
                                || false !== strpos( $string, '_' ) 
                                || false !== strpos( $string, '://' ) 
                                || false !== strpos( $string, '=>' ) 
                            )
                            {
                                // don't autoinser
                            }
                            else
                            {
                                $stringInfo = $words->insert( array( 'string' => $string, 'trimmed_string' => $trimmedString, 'pages' => array( $url ), ) );
                            }
                        }
                    }
                }
                if( ! empty( $stringInfo['pages'] ) && ! in_array( $url, $stringInfo['pages'] ) )
                {
                    $stringInfo['pages'][] = $url;
                    $words->update( $stringInfo, array( 'originalstring_id' => $stringInfo['originalstring_id'] ) );
                }
				$stringStorage->store( $stringInfo );

			}
		//	var_export( $stringInfo );

			if( ! empty( $stringInfo['originalstring_id'] ) )
			{
				$translation = PageCarton_Locale_Translation::getInstance();
				if( ! $translatedString = $translation->selectOne( null, array( 'originalstring_id' => $stringInfo['originalstring_id'], 'locale_code' => $locale, ) ) )
				{
				//	$translation->insert( array( 'word' => $string, 'locale_code' => $locale, ) );
				}
				elseif( ! empty( $translatedString['translation'] ) )
				{
					$string = $translatedString['translation'];
                }
            //    self::v( $stringInfo['originalstring_id'] );
            //    self::v( $locale );
            //    if( empty( $translatedString['translation'] ) )
            //    if( $allStringEntries = PageCarton_Locale_OriginalString::getInstance()->select( null, array( 'string' => $string ) ) )
                {
                //    self::v( $string );
                //    self::v( $stringInfo );
                //    var_export( count( $allStringEntries ) );
                }
            //    self::v( $translatedString['translation'] );
			}
		//	var_export( $stringInfo );
		}
		while( false );
		//	var_export( $string );
        static::$_translated[$id] = $string; 
        $translationStorage->store( $string );
		return $string;
	}

    /**
     * Used by administrators to inspect variables for debugging purposes.
     *
     */
	public static function arrayToString( $values, array $options = null )
    {
		$options['separator'] = @$options['separator'] ? : "\r\n<br>";
		$options['separator'] = @$options['separator'] ? : "\r\n<br>";
		$stringValues = $options['separator'];
		foreach( $values as $key => $value )
		{
			if( is_array( $value ) )
			{
				$value = implode( ', ', $value );
			}
			$key = implode( ' ', array_map( 'ucfirst', explode( '_', $key ) ) );
			$stringValues .= "<strong>$key</strong>: $value" . $options['separator'];
		}
		$stringValues .= $options['separator'];
		return $stringValues;
	}

    /**
	 * Returns text for the "interior" of the Layout Editor
	 * The default is to display view and option parameters.
	 *
     * @param array Object Info
     * @return string HTML
     */
    public static function getHTMLForLayoutEditor( & $object )
	{
		$html = null;
		@$object['view'] = $object['view'] ? : $object['view_parameters'];
		@$object['option'] = $object['option'] ? : $object['view_option'];
//		$html .= "<span data-parameter_name='view' >{$object['view']}</span>";

		//	Implementing Object Options
		//	So that each objects can be used for so many purposes.
		//	E.g. One Class will be used for any object
	//	var_export( $object );
		if( method_exists( $object['class_name'], 'getClassOptions' ) )
		{
			$options = $object['class_name'];
			$options = new $options( array( 'no_init' => true ) );
	//		$options = array();
			$options = (array) $options->getClassOptions();
			$html .= '<select data-parameter_name="option">';
			foreach( $options as $key => $value )
			{
				$html .=  '<option value="' . $key . '"';
			//	var_export( $object['view'] );
				if( $object['option'] == $key ){ $html .= ' selected = selected '; }
				$html .=  '>' . $value . '</option>';
			}
			$html .= '</select>';
		}
		return $html;
	}

    /**
     * Returns an array of other classes to get parameter keys from
     *
     * @param void
     * @return array
     */
    protected static function getParameterKeysFromTheseOtherClasses( & $parameters )
    {
		return array();
	}

    /**
     *
     *
     * @param array
     * @return array
     */
    protected static function saveWidget( $class, array & $parameters, $url = '', $section = '' )
    {
        if( empty( $class ) )
        {
            throw new Ayoola_Abstract_Exception( 'A widget with an empty class cannot be saved' );
        }
        
        $parametersToSave = $parameters;
        unset( $parametersToSave['pagewidget_id'] );
        $parametersKey = md5( serialize( $parametersToSave ) );

        $whatToSave = array( 
                            //    'widget_name' =>  $parameters['widget_name'] , 
                            //    'url' =>  $url, 
                            //    'class_name' =>  $class, 
                                'parameters' => $parametersToSave, 
                                'parameters_key' => $parametersKey, 
                            //    'section_name' => $section, 
                            );
        if( ! empty( $parameters['widget_name'] ) )
        {
            $whatToSave['widget_name'] = $parameters['widget_name'];
        }
        if( ! empty( $url ) )
        {
            $whatToSave['url'] = $url;
        }
        if( ! empty( $class ) )
        {
            $whatToSave['class_name'] = $class;
        }
        if( ! empty( $section ) )
        {
            $whatToSave['section_name'] = $section;
        }
        if( 
            empty( $parameters['pagewidget_id'] ) 
            || ! Ayoola_Object_PageWidget::getInstance()->select( null,  array( 'pagewidget_id' =>  $parameters['pagewidget_id'] ) ) 
        )
        {
        	//    var_export( $parameters );
            if( 
            
                ! $lostInfo = Ayoola_Object_PageWidget::getInstance()->selectOne( null,  array( 'class_name' =>  $class, 'parameters_key' =>  $parametersKey ) )

            )
            {
        
                if( empty( $parameters['widget_name'] ) )
                {
                    $parameters['widget_name'] = trim( trim( ( $parameters['preserved_content'] ? : $parameters['codes'] ) ? : $parameters['editable'] ) ? : implode( ' - ', $parameters ), ' -' );
                    if( ! empty( trim( strip_tags( $parameters['widget_name'] ) ) ) )
                    {
                        $parameters['widget_name'] = trim( strip_tags( $parameters['widget_name'] ) );
                    }
                    $parameters['widget_name'] = trim( ( $parameters['widget_name'] ) ? : ( $class ), ' -' );
                    $parameters['widget_name'] = trim( preg_replace( '|(\s)+|', ' ', $parameters['widget_name'] ) );
                    if( empty( $parameters['widget_name'] ) )
                    {
                        $parameters['widget_name'] =  implode( ', ', $parameters );
                    }
                    $parameters['widget_name'] = trim( str_ireplace( array( 'array', ',', Ayoola_Application::getUrlPrefix() ), '', ( $parameters['widget_name'] ) ) );
                    $parameters['widget_name'] = trim( preg_replace( '|(\s)+|', ' ', strip_tags( $parameters['widget_name'] ) ) );
                    if( strlen( $parameters['widget_name'] ) > 120 )
                    {
                        $parameters['widget_name'] = trim( substr( $parameters['widget_name'], 0, 100 ) . ' - ' . strlen( $parameters['widget_name'] ), ' -' );
                    }
                    $parameters['widget_name'] = trim( $parameters['widget_name'], ' - ' ) ? : ( $class . ' - ' . time() );

                }
                $response = Ayoola_Object_PageWidget::getInstance()->insert( $whatToSave );
                $parameters += (array) $response;
                if( ! empty( $response['pagewidget_id'] ) )
                {
                    $parameters['pagewidget_id'] = $response['pagewidget_id'];
                    $whatToSave['widget_name'] = $parameters['widget_name'];
                }
        	//    var_export( $whatToSave );
        	//    var_export( $response );
        	//    var_export( $parameters );

            }
            elseif( ! empty( $lostInfo['pagewidget_id'] ) )
            {
                $parameters['pagewidget_id'] = $lostInfo['pagewidget_id']; 
                $parameters['widget_name'] = $lostInfo['widget_name']; 
                $whatToSave['widget_name'] = $lostInfo['widget_name']; 
            }
        	//    var_export( $lostInfo );

        }
       // 	    var_export( $parameters );
        if( 
            ! empty( $parameters['pagewidget_id'] ) AND $previousWidgetInfo = Ayoola_Object_PageWidget::getInstance()->selectOne( null,  array( 'pagewidget_id' =>  $parameters['pagewidget_id'] ) ) 
        )
        {
        //	    var_export( $previousWidgetInfo );
            //  save history
            $previousWidgetInfo['history'] = is_array( $previousWidgetInfo['history'] ) ? $previousWidgetInfo['history'] : array();
            $previousWidgetInfo['history'][time()] = $whatToSave['parameters'];

            $whatToSave['history'] = $previousWidgetInfo['history'];

            //  update
            $response = Ayoola_Object_PageWidget::getInstance()->update( $whatToSave, array( 'pagewidget_id' =>  $parameters['pagewidget_id'] ) );
            
            $response = Ayoola_Object_PageWidget::getInstance()->selectOne( null, array( 'pagewidget_id' =>  $parameters['pagewidget_id'] ) );
            if( $response['parameters'] !== $whatToSave['parameters'] )
            {
                //  for some reasons, what it is still saving old data.
                // Need this as workaround
            //    Ayoola_Object_PageWidget::getInstance()->delete( array( 'pagewidget_id' =>  $parameters['pagewidget_id'] ) );
            //    $response = Ayoola_Object_PageWidget::getInstance()->insert( $whatToSave );
            //    var_export( $response );
            }
        }
    //    var_export( $parameters );
    }

    /**
     *
     *
     * @param array
     * @return array
     */
    protected static function getParameterKeys( & $parameters )
    {
		$thisClass = get_called_class();
//		var_export( $thisClass );
	//	var_export( $parameters['markup_template_object_name'] );
		$thisObjectID = md5( $thisClass . $parameters['object_unique_id'] . json_encode( $parameters ) );
		if( ! empty( static::$_parameterKeys[$thisObjectID] ) )
		{
			return static::$_parameterKeys[$thisObjectID];
		}
		$classes = array( $thisClass );
	//	var_export( static::getParameterKeysFromTheseOtherClasses( $parameters ) );
		$parameterKeysClasses = static::getParameterKeysFromTheseOtherClasses( $parameters );
		if( is_array( $parameterKeysClasses ) )
		{
			$classes = array_merge( $classes, $parameterKeysClasses );
		}
	//	var_export( $thisClass );
	//	var_export( $classes );
		$classes = array_unique( $classes );
	//	var_export( $classes );
		$content = file_get_contents( __FILE__ ) ;
		$filter = new Ayoola_Filter_ClassToFilename();
		foreach( $classes as $class )
		{
			do
			{
				if( ! Ayoola_Loader::loadClass( $class ) )
				{
					continue;
				}
				$classFile = $filter->filter( $class );
				$classFile = Ayoola_Loader::getFullPath( $classFile );
			//	var_export( $classFile );
				$fileContent = file_get_contents( $classFile );
				$content .= $fileContent;
				preg_match_all( "/class\s([a-zA-Z_]*)\sextends\s([a-zA-Z_]*)/", $fileContent, $abstract );
		//		var_export( $class );
		//		var_export( $abstract[2][0] );
				$class = @$abstract[2][0];
				if( ! $class || in_array( $class, $classes ) )
				{
				//	var_export( $class );
					break;
				}
				else
				{
					$classes[] = $class;
				}
			//	var_export( $abstract[2] );
			}
			while( ! empty( $abstract[2] ) );
		}
//		$class = get_called_class();


		preg_match_all( "/getParameter\( '([a-z_-]*)' \)/", $content, $results );
	//	var_export( $class );
		$results[1] = array_unique( $results[1] );

		//
		$supplementary = array();
	//	var_export( $parameters['markup_template_object_name'] );
		if( ! empty( $parameters['markup_template_object_name'] ) && is_array( $parameters['markup_template_object_name'] ) )
		{
			foreach( $parameters['markup_template_object_name'] as $counter => $eachKey )
			{
				if( ! Ayoola_Loader::loadClass( $eachKey ) )
				{
					continue;
				}
				foreach( $results[1] as $each )
				{
					$supplementary[] = $each . '[' . $counter . ']';
				}
			}
	//		var_export( $parameters['markup_template_object_name'] );
	//		var_export( $supplementary );
		}
		$results[1][] = 'set_access_level';
		$results[1][] = 'wrap_widget';
		$results[1] = array_merge( $results[1], $supplementary );
		sort( $results[1] );
		static::$_parameterKeys[$thisObjectID] = $results[1];
	//	if( in_array( 'Application_Profile_View', $classes ) )
		{
	//		var_export( $results[1] );
		}

	//	var_export( $results[1] );
	//	var_export( $content );
	//	var_export( $classFile );
	//	exit();
		return static::$_parameterKeys[$thisObjectID];
	}

    /**
     * Produce the mark-up for each viewable object
     *
     * @param array viewableObject Information
     * @return string Mark-Up to Display Viewable Objects
     */
    protected static function getViewableObjectRepresentation( array $object )
    {
	//	var_export( $object );
		$html = null;
		$object['object_name'] = $object['object_name'] ? : $object['class_name'];
	//	$object['object_unique_id'] = @$object['object_unique_id'] ? : ( md5( $object['object_name'] ) . rand( 100, 1000 ) );
		$advancedName = 'advanced_parameters_' . $object['object_unique_id'] . '';
		$html .= "<div data-class_name='{$object['class_name']}' name='over_all_object_container' class='DragBox' id='" . $object['object_unique_id'] . "' title='Move this object by dragging it around - " . $object['view_parameters'] . "' data-object_name='{$object['object_name']}' >";
		$title = ( ( $object['view_parameters'] ? : $object['object_name']::getObjectTitle() ) ? : $object['object_name'] );
		//	title bar
		$html .= '<div draggable=\'true\' ondragstart=\'ayoola.dragNDrop.dragMyParent(event);\' style="cursor: move; cursor: -moz-grab;cursor: -webkit-grab;" title="' . $title . '" class="title_bar pc_page_object_specific_item" data-parameter_name="parent">';


		//	Delete button
		$html .= '<span class="title_button close_button"  name="" href="javascript:;" class="" title="Delete this object" onclick="this.parentNode.parentNode.parentNode.removeChild( this.parentNode.parentNode );"> x </span>';

		//	Maximize
		$html .= '<a class="title_button" name="' . $advancedName . '" href="javascript:;" title="Click to show or hide advanced settings" onclick="  var b = this.parentNode.parentNode.getElementsByClassName( \'advanced_options\' );for( var a = 0; a < b.length; a++ ){  b[a].style.display = ( b[a].style.display == \'none\' ) ? \'\' : \'none\'; this.style.display = \'\'; } "> <i class="fa fa-cog"></i> </a>';

		//	Minimize
		$html .= '<a class="title_button" name="' . $advancedName . '_interior" href="javascript:;" title="Minimize or open the body of this object" onclick="  var b = this.parentNode.parentNode.getElementsByClassName( \'object_exterior\' );for( var a = 0; a < b.length; a++ ){  b[a].style.display = ( b[a].style.display == \'none\' ) ? \'\' : \'none\'; this.style.display = \'\'; } "> _ </a>';

		//	title
		$html .= '<span >' . $title . '</span>';
		$html .= '<div style="clear:both;"></div>';

		$html .= '</div>';	//	 title bar

		//	advanced options

		$openAdvancedOption = 'display:none;';
		if( ! empty( $_REQUEST['rebuild_widget_box'] ) )
		{
			$openAdvancedOption = '';
		}
		$html .= '<div style="border: #ccc 1px solid;padding:0.5em;padding:0 0.5em 0 0.5em;' . $openAdvancedOption . '" title="" class="advanced_options pc_page_object_specific_item " data-parameter_name="parent">';


		//		$html .= '<div style="clear:both;" name="' . $advancedName . '" class=""><label>Inject some parameters to this object...</label></div>';

			$form = new Ayoola_Form( array( 'name' => $advancedName, 'data-parameter_name' => 'advanced_parameters', 'class' => '' ) );
			parse_str( @$object['advanced_parameters'], $advanceParameters );
		//	$advanceParameters['advanced_parameter_value'][] = 'tested' . time();
		//	$advanceParameters['advanced_parameter_name'][] = 'test' . time();
		//	var_export( $advanceParameters );
		//	var_export( $object );
			self::sanitizeParameters( $object );
			$object = array_merge( $advanceParameters, $object );
				//	var_export( $object );

			//	check it here first so that it can set the widget options
			if( @$object['savedwidget_id'] )
			{
				if( $widgetToRestore = Ayoola_Object_SavedWidget::getInstance()->selectOne( null, array( 'savedwidget_id' =>  $object['savedwidget_id'], ) ) )
				{
					$object = $widgetToRestore['parameters'];

					//	avoid double saves
					unset( $object['save_widget_as'] );
					$advanceParameters = $object;
				}
			}
            @$object['pagewidget_id'] = @$object['pagewidget_id_switch'] ? : @$object['pagewidget_id'];
        //    var_export( $object['pagewidget_id'] );
            if( @$object['pagewidget_id'] )
			{
				if( $pageWidgetToRestore = Ayoola_Object_PageWidget::getInstance()->selectOne( null, array( 'pagewidget_id' =>  $object['pagewidget_id'], ) ) )
				{
					if( ! empty( $_REQUEST['rebuild_widget_box'] ) && ( @$object['pagewidget_id_switch'] || @$object['pagewidget_id_version'] ) )
					{
					//	var_export( $object );
					//	var_export( $pageWidgetToRestore['parameters'] );
                        //  Set version in history
                    //    var_export( $pageWidgetToRestore['history'][$object['pagewidget_id_version']] );
                        if( ! empty( $object['pagewidget_id_version'] ) && ! empty( $pageWidgetToRestore['history'][$object['pagewidget_id_version']] ) )
                        {
                            $pageWidgetToRestore['parameters'] = $pageWidgetToRestore['history'][$object['pagewidget_id_version']];
                        }
						unset( $pageWidgetToRestore['parameters']['pagewidget_id_switch'] );
                        $object = $pageWidgetToRestore['parameters'] + array( 'class_name' => $object['class_name'] );
						$object['widget_options'][] = 'savings';
						$object['pagewidget_id'] = $pageWidgetToRestore['pagewidget_id'];
					//	$object['pagewidget_id_switch'] = $pageWidgetToRestore['pagewidget_id'];
                    //    var_export( $object );

						//	avoid double saves
						unset( $object['save_widget_as'] );
                        parse_str( @$object['advanced_parameters'], $advanceParameters );
                    //    var_export( $object );

					}
					elseif( empty( $_REQUEST['rebuild_widget_box'] ) && @$object['pagewidget_id'] )
					{
					//	var_export( $object );
						$object = $pageWidgetToRestore['parameters'];
					//	$object['widget_options'][] = 'savings';
                        $object['pagewidget_id'] = $pageWidgetToRestore['pagewidget_id'];

						//	avoid double saves
						unset( $object['save_widget_as'] );
						parse_str( @$object['advanced_parameters'], $advanceParameters );
					}
					else
					{
					//	var_export( $object );
						$object = $object + $pageWidgetToRestore['parameters'];
					}
				}
			}


			$availableOptions = ( static::$_widgetOptions ? : array() ) + array(
				'wrappers' => 'Wrappers',
				'parameters' => 'Parameters',
				'privacy' => 'Privacy',
				'savings' => 'Savings',
				'devices' => 'Devices',
			);
			$fieldset = new Ayoola_Form_Element();
			$fieldset->hashElementName = false;
			$fieldset->container = 'div';
			$fieldset->addElement( array( 'name' => 'widget_options', 'id' => $object['object_unique_id'] . '_widget_options', 'label' => ' ', 'type' => 'Checkbox', 'multiple' => 'multiple', 'value' => @$object['widget_options'], ), $availableOptions );
			$form->addFieldset( $fieldset );

			if( @in_array( 'savings', $object['widget_options'] ) )
			{
				if( $object['save_widget_as'] )
				{
					//	avoid double saves
					$widgetName = $object['save_widget_as'];
					unset( $object['save_widget_as'] );

				//	var_export( $object );
				//	var_export( $advanceParameters );
					$whatToSave = array( 'widget_name' =>  $widgetName, 'class_name' =>  $object['class_name'], 'parameters' => $object, );
					if( ! Ayoola_Object_SavedWidget::getInstance()->select( null,  array( 'widget_name' =>  $widgetName, 'class_name' =>  $object['class_name'] ) ) )
					{
						Ayoola_Object_SavedWidget::getInstance()->insert( $whatToSave );
					}
				}
				$fieldset = new Ayoola_Form_Element();
				$fieldset->hashElementName = false;
				$fieldset->container = 'div';

				//	My Saved Widgets
				$savedWidgets = Ayoola_Object_SavedWidget::getInstance()->select( null, array( 'class_name' =>  $object['class_name'], ) );
				$filter = new Ayoola_Filter_SelectListArray( 'savedwidget_id', 'widget_name');
				$savedWidgets = $filter->filter( $savedWidgets );
				$savedWidgets ? $fieldset->addElement( array( 'name' => 'savedwidget_id', 'label' => ' ', 'type' => 'Select', 'value' => @$object['savedwidget_id'] ), array( '' => 'Restore My Saved Widgets' ) + $savedWidgets ) : null;

				//	PageWidgets

				$pageWidgets = Ayoola_Object_PageWidget::getInstance()->select( null, array( 'class_name' =>  $object['class_name'], ) );
			//	var_export( $pageWidgets );
				$filter = new Ayoola_Filter_SelectListArray( 'pagewidget_id', 'widget_name');
				$pageWidgets = $filter->filter( $pageWidgets );
				$pageWidgets ? $fieldset->addElement( array( 'name' => 'pagewidget_id_switch', 'label' => ' ', 'type' => 'Select', 'value' => null ), array( '' => 'Restore Page Widgets' ) + $pageWidgets + array( '9x9' => 'New Page Widgets' ) ) : null;


                $form->addFieldset( $fieldset );

                $pageWidgetsVersionsKeys = array_keys( $pageWidgetToRestore['history'] );
            //    var_export( $pageWidgetsVersionsKeys );
                $filterTime = new Ayoola_Filter_Time();
                $pageWidgetsVersions = array();
                foreach( $pageWidgetsVersionsKeys as $widgetVersion )
                {
                    $pageWidgetsVersions[$widgetVersion] = $filterTime->filter( $widgetVersion );
                }

				$pageWidgetsVersions ? $fieldset->addElement( array( 'name' => 'pagewidget_id_version', 'label' => ' ', 'type' => 'Select', 'value' => null ), array( '' => 'Widget History' ) + $pageWidgetsVersions ) : null;

                $fieldset->addElement( array( 'name' => 'widget_name', 'label' => 'Save This Widget As', 'type' => 'InputText', 'value' => @$object['widget_name'] ? : @$pageWidgetToRestore['widget_name'] ) );
			}

			$fieldset->addElement( array( 'name' => 'pagewidget_id', 'label' => '', 'type' => 'Hidden', 'value' => @$object['pagewidget_id'] ) );

			if( @$object['savedwidget_id'] )
			{
			//	$savedWidgets ? $fieldset->addElement( array( 'name' => 'savedwidget_id', 'label' => ' ', 'type' => 'Select', 'value' => @$object['savedwidget_id'] ), array( '' => 'Restore Saved Widgets' ) + $savedWidgets ) : null;
				if( $widgetToRestore = Ayoola_Object_SavedWidget::getInstance()->selectOne( null, array( 'savedwidget_id' =>  $object['savedwidget_id'], ) ) )
				{
				//	var_export( $widgetToRestore );
					$object = $widgetToRestore['parameters'];

					//	avoid double saves
					unset( $object['save_widget_as'] );
					$advanceParameters = $object;
				}
			}
			if( method_exists( $object['class_name'], 'getHTMLForLayoutEditorAdvancedSettings' ) )
			{
				$fieldset = new Ayoola_Form_Element();
				$fieldset->addElement( array( 'name' => 'x', 'type' => 'html', ), array( 'html' => $object['class_name']::getHTMLForLayoutEditorAdvancedSettings( $object ) ) );
				$form->addFieldset( $fieldset );
			//	var_export( $object['class_name']::getHTMLForLayoutEditorAdvancedSettings( $object ) );
			//	$html .= ;
			}

			//	var_export( $advanceParameters );
			$form->wrapForm = false;

			$form->setParameter( array( 'no_required_fieldset' => true ) );
			$parameterOptions = array( '' => 'Select Parameter' ) + ( array_combine( static::getParameterKeys( $object ), static::getParameterKeys( $object ) ) ? : array() ) + array( '__custom' => 'Custom Parameter' );
		//	var_export( $advanceParameters['advanced_parameter_value'] );
			if( @in_array( 'parameters', $object['widget_options'] ) || @$advanceParameters['advanced_parameter_value'] )
			{
				$i = 0;
				do
				{

					$fieldset = new Ayoola_Form_Element;
					$fieldset->hashElementName = false;
					$fieldset->container = 'div';
					if( ! array_key_exists( @$advanceParameters['advanced_parameter_name'][$i], $parameterOptions ) )
					{
						$parameterOptions[$advanceParameters['advanced_parameter_name'][$i]] = $advanceParameters['advanced_parameter_name'][$i];
                    }
                    list( $aPName, ) = explode( '[', @$advanceParameters['advanced_parameter_name'][$i] );

                    $textAreaData = array(
                        'markup_template',
                        'code',
                        'json',
                    );
                    $textArea = false;
                    foreach( $textAreaData as $eachTextArea )
                    {
                        if( stripos( $aPName, $eachTextArea ) !== false )
                        {
                            $textArea = true;
                        }
                    }
                    if( $textArea )
                    {
                        if( ! array_key_exists( @$advanceParameters['advanced_parameter_name'][$i], $parameterOptions ) )
                        {
                            $parameterOptions[@$advanceParameters['advanced_parameter_name'][$i]] = @$advanceParameters['advanced_parameter_name'][$i];
                        }
                        $fieldset->addElement( array( 'name' => 'advanced_parameter_name[]', 'label' => '', 'placeholder' => 'Select Parameter', 'type' => 'Select', 'onchange' => 'if( this.value == \'__custom\' ){ var a = prompt( \'Custom Parameter Name\', \'\' ); if( ! a ){ this.value = \'\'; return false; } var option = document.createElement( \'option\' ); option.text = a; option.value = a; this.add( option ); this.value = a;  }', 'value' => @$advanceParameters['advanced_parameter_name'][$i] ), $parameterOptions );
                        $fieldset->addElement( array( 'name' => 'advanced_parameter_value[]', 'label' => '', 'placeholder' => 'Parameter Value', 'type' => 'TextArea', 'style' => 'width:100%;', 'value' => @$advanceParameters['advanced_parameter_value'][$i] ) );
                        $fieldset->placeholderInPlaceOfLabel = true;
                    }
                    elseif( static::getParameterKeys( $object ) )
                    {
                        $fieldset->addElement( array( 'name' => 'advanced_parameter_name[]', 'label' => '', 'placeholder' => 'Select Parameter', 'onchange' => 'if( this.value == \'__custom\' ){  var a = prompt( \'Custom Parameter Name\', \'\' ); if( ! a ){ this.value = \'\'; return false; } var option = document.createElement( \'option\' ); option.text = a; option.value = a; this.add( option ); this.value = a;  }', 'type' => 'Select', 'value' => @$advanceParameters['advanced_parameter_name'][$i] ), $parameterOptions );
                        $fieldset->addElement( array( 'name' => 'advanced_parameter_value[]', 'label' => '', 'placeholder' => 'Parameter Value', 'type' => 'InputText', 'value' => @$advanceParameters['advanced_parameter_value'][$i] ) );
                        $fieldset->placeholderInPlaceOfLabel = true;
                    }
					if( @$advanceParameters['advanced_parameter_name'][$i] && ( @$advanceParameters['advanced_parameter_value'][$i]
					||  @$advanceParameters['advanced_parameter_value'][$i] === '0' )
					)
					{
						$fieldset->duplicationData = array( 'add' => '+ New Parameter', 'remove' => '- Remove Above Parameter', 'counter' => 'parameter_counter', );
						$fieldset->allowDuplication = true;
					}
					$form->addFieldset( $fieldset );
					$i++;
				}
				while( ! empty( $advanceParameters['advanced_parameter_name'][$i] ) || ! empty( $advanceParameters['advanced_parameter_name'][++$i] ) );
			}
			if( ! self::$_authLevelOptions )
			{
				$authLevelOptions = new Ayoola_Access_AuthLevel;
				$authLevelOptions = $authLevelOptions->select();
				require_once 'Ayoola/Filter/SelectListArray.php';
				$filter = new Ayoola_Filter_SelectListArray( 'auth_level', 'auth_name' );
				$authLevelOptions = $filter->filter( $authLevelOptions );
				$authLevelOptions[0] = 'Users not logged in only';
				$authLevelOptions[1] = 'Signed in users only';
				$authLevelOptions[98] = 'Page Owners';
				self::$_authLevelOptions =  $authLevelOptions;
			}
			$fieldset = new Ayoola_Form_Element;
			$fieldset->hashElementName = false;
			if( @in_array( 'privacy', $object['widget_options'] ) || @$advanceParameters['object_access_level'] )
		//	if( $object['set_access_level'] || $advanceParameters['object_access_level'] )
			{
				$fieldset->addElement( array( 'name' => 'object_access_level', 'id' => $object['object_unique_id'] . '_object_access_level', 'label' => 'Who can view widget', 'placeholder' => '', 'type' => 'SelectMultiple', 'value' => @$advanceParameters['object_access_level'] ), self::$_authLevelOptions );
			}
			$inlineWrapperChange = false;
			if( $object['class_name'] == 'Ayoola_Page_Editor_Text' || $object['class_name'] == 'Ayoola_Page_Editor_Image' )
			{
			//	$inlineWrapperChange = true;

			$jsChangeWrapper = '
				var a = ayoola.div.getParent( { element: this, name: \'over_all_object_container\', counter: 10 } );
			//	alert( a );

				var b = this.options[this.selectedIndex].getAttribute( \'data-wrapper_prefix\' ) || \'\';
				var c = this.options[this.selectedIndex].getAttribute( \'data-wrapper_suffix\' ) || \'\';
				var d = a.getElementsByClassName( \'object_interior\' )[0];
				var e  = a.getElementsByClassName( \'object_exterior\' )[0];
			//	alert( a.getElementsByClassName( \'object_interior\' ).length );
				e.innerHTML = b + d.outerHTML + c;

				//	Regenerate d
				var d = a.getElementsByClassName( \'object_interior\' )[0];

				//	Automatically add parent indicator to the parents of object_interior
				var g = d.parentNode;
				for( var f = 0; g != e; f++ )
				{
					g.setAttribute( \'data-parameter_name\', \'parent\' );
				//	alert(g.outerHTML);
					g = g.parentNode;
					//	Prevent infinite loop
					if( f > 9 ){ break; }
				}
			//	alert( b ); data-parameter_name="parent"
			//	alert( c );
			//	alert( d );

				';
			}
		//	var_export( $advanceParameters );
			if( @in_array( 'wrappers', $object['widget_options'] ) || @$advanceParameters['wrapper_name'] )
		//	if( $object['wrap_widget'] || $advanceParameters['wrapper_name'] )
			{
				if( ! self::$_wrapperOptions )
				{
					$class = Ayoola_Object_Table_Wrapper::getInstance();
					self::$_wrapperOptions = $class->select();
				}
			//	var_export( @$object['wrapper_name'] );
		//		$options = '<select name="wrapper_name" onChange="' . $jsChangeWrapper . '">
				$options = '<select name="wrapper_name" onChange="">
								<option value="">No Wrapper...</option>
								';
			//	@$object['wrapper_name'] ? var_export( $object['wrapper_name'] ) : null;
				$currentWrapper = array();
				foreach( self::$_wrapperOptions as $eachWrapper )
				{
					$selected = null;
					if( @$eachWrapper['wrapper_name'] === @$advanceParameters['wrapper_name'] )
					{
						$currentWrapper = $eachWrapper;
						$selected = 'selected=selected';
					}
					if( ! $inlineWrapperChange )
					{
						$eachWrapper['wrapper_suffix'] = null;
						$eachWrapper['wrapper_prefix'] = null;
					}
					$options .= '<option ' . $selected . ' value="' . $eachWrapper['wrapper_name'] . '">' . $eachWrapper['wrapper_label'] . '</option>';
				}
				$options .= '</select>';
				$fieldset->addElement( array( 'name' => 'wrapper_label', 'type' => 'Html' ), array( 'html' => '<p><label>Wrapper</label>' . $options . '</p>', 'fields' => 'wrapper_name' ) );
            }
			if( @in_array( 'devices', $object['widget_options'] ) || @$advanceParameters['device_whitelist'] || @$advanceParameters['device_blacklist'] )
			{
                $options = '<label>Choose Device to Show Widget To...</label>
                            <select name="device_whitelist" onChange="">
								<option value="">None</option>';
				foreach( PageCarton_Device::getInstance()->select() as $eachDevice )
				{
                    $selected = null;
					if( @$advanceParameters['device_whitelist'] && @$eachDevice['device_name'] === @$advanceParameters['device_whitelist'] )
					{
						$selected = 'selected=selected';
					}
					$options .= '<option ' . $selected . ' value="' . $eachDevice['device_name'] . '">' . $eachDevice['device_name'] . '</option>';
				}
				$options .= '</select>';
                $fieldset->addElement( array( 'name' => 'devices-x', 'type' => 'Html' ), array( 'html' => '<p>' . $options . '</p>', 'fields' => 'device_whitelist' ) );
                
                $options = '<label>Choose Device to Hide Widget From...</label>
                            <select name="device_blacklist" onChange="">
								<option value="">None</option>';
				foreach( PageCarton_Device::getInstance()->select() as $eachDevice )
				{
                    $selected = null;
					if( @$advanceParameters['device_blacklist'] && @$eachDevice['device_name'] === @$advanceParameters['device_blacklist'] )
					{
						$selected = 'selected=selected';
					}
					$options .= '<option ' . $selected . ' value="' . $eachDevice['device_name'] . '">' . $eachDevice['device_name'] . '</option>';
				}
				$options .= '</select>';
				$fieldset->addElement( array( 'name' => 'devices-xy', 'type' => 'Html' ), array( 'html' => '<p>' . $options . '</p>', 'fields' => 'device_blacklist' ) );

			}
            
			$fieldset->placeholderInPlaceOfLabel = true;
			$form->addFieldset( $fieldset );
			$html .= $form->view();

		$html .= '</div>';	//	advanced options


	//	$html .= '<div></div>';	//	Wrapper Prefix
	//	$html .= '<div></div>';	//	Wrapper Suffix

		//	Retrieving object "interior" from the object class

		//	Determine if its opening or closing inside the "object".
		$openOrNot = static::$openViewParametersByDefault ? '' : 'display:none;';
		$html .= '<div class="object_exterior" data-parameter_name="parent">'; //	exterior
		$html .= @$currentWrapper['wrapper_prefix']; //	exterior
		$html .= '<div title="' . $object['view_parameters'] . '" style="' . $openOrNot . ' cursor: default;" name="' . $advancedName . '_interior" class="object_interior" data-parameter_name="parent">'; //	interior parent

		//	just for padding.
		$html .= '<div class="pc_page_object_specific_item" style="padding-top:0.5em; padding-bottom:0.5em;"></div>';
		$getHTMLForLayoutEditor = 'getHTMLForLayoutEditor';
		$innerSettingsContent = null;
		if( method_exists( $object['class_name'], $getHTMLForLayoutEditor ) )
		{
			$innerSettingsContent = $object['class_name']::$getHTMLForLayoutEditor( $object );
		}
		if( static::$_editableTitle )
		{
		//	$html .= '<button href="javascript:;" title="' . static::$_editableTitle . '"  class="" onclick="ayoola.div.makeEditable( this.nextSibling ); this.nextSibling.style.display=\'block\';"> edit </button>';
				//	var_export( $object );
		// /		var_export( $object['editable'] );
			$editableValue = '' . @$object['editable'] . '';
			$innerSettingsContent .= '<input placeholder="' . ( static::$_editableTitle ) . '" data-parameter_name="editable" type=text value="' . $editableValue . '" >';
		//	$html .= '<button href="javascript:;" style="display:none;" class="" title="' . static::$_editableTitle . '" onclick="this.previousSibling.style.display=\'none\';this.style.display=\'none\';"> hide </button>';
		}

		if( @$object['object_interior'] || static::$editorViewDefaultToPreviewMode || @in_array( 'object_interior', $advanceParameters['advanced_parameter_name'] ) )
		{
	//		var_export( $object );
			$classToView = $object['class_name'] ? : $object['object_name'];
	//		var_export( $classToView );
			static::$editorViewDefaultToPreviewMode && $innerSettingsContent ? $html .= '<div  data-parameter_name="parent" class="pc_page_object_specific_item pc_page_object_inner_settings_area" >' . $innerSettingsContent
/*
			.

			' <button onclick="
			var a = ayoola.div.getParentWithClass( this, \'DragBox\' );
			var b = ayoola.div.getParameterOptions( a );
			var c = a.getElementsByClassName( \'pc_page_object_inner_preview_area\' )[0];
			var ajax = ayoola.xmlHttp.fetchLink( { url: \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Object_Preview/?pc_show_error=1&rebuild_widget=1&class_name=' . $classToView . '\', data: b.content, container: c } );
			">Preview!</button>'
*/			.
			'</div>'

			 : null;

			//	/object/name/Ayoola_Object_Preview/?class_name=' . $classToView . '
			$parameters = Ayoola_Page_Editor_Layout::prepareParameters( $object );
			$html .= '<div data-parameter_name="parent" class="pc_page_object_inner_preview_area" >' . Ayoola_Abstract_Viewable::viewObject( $classToView, $parameters + array( 'rebuild_widget' => 1 ) ) . '</div>';
	//		var_export( $object );
		}
		elseif( $innerSettingsContent )
		{
			$html .= $innerSettingsContent;
		}
		//	var_export( $object );
		if( @$object['call_to_action'] )
		{
			$html .= '<textarea name="' . $advancedName . '" placeholder="Enter HTML for a Call-To-Action" data-parameter_name="call_to_action" style="width:100%;" onclick="">' . @$object['call_to_action'] . '</textarea>';
		}
		if( @$object['markup_template_namespace'] )
		{
			$html .= '<input name="' . $advancedName . '" placeholder="Choose a namespace for HTML template" data-parameter_name="markup_template_namespace" style="width:100%;" onclick="" value="' . @$object['markup_template_namespace'] . '" />';
		}

		//	just for padding.
		$html .= '<div class="pc_page_object_specific_item" style="padding-top:0.5em; padding-bottom:0.5em;"></div>';

		$html .= '</div>';	//	 interior
		$html .= @$currentWrapper['wrapper_suffix'];	//	 wrapper
		$html .= '</div>';	//	 exterior
		$html .= "<textarea onclick='this.focus();this.select()' style='display:none; width:100%;' class='import_export_content' title='Copy contents and paste where you want to export.'> </textarea>";

		//	status bar
		$html .= '<div name="' . $advancedName . '_interior" style="' . $openOrNot . '" title="' . $object['view_parameters'] . '" class="status_bar pc_page_object_specific_item pc_full_width">';

		//	Help
	//	$html .= '<a class="title_button" title="Seek help on how to use this page editor" name="" href="http://pagecarton.org/docs" onclick="this.target=\'_new\'">?</a>';

		//	Export
        $html .= '<a class="title_button" title="Import or export object" name="" href="javascript:;" onclick="var b = this.parentNode.parentNode.getElementsByClassName( \'import_export_content\' ); b = b[0];  if( b.style.display == \'none\' ){  b.value = this.parentNode.parentNode.outerHTML; b.style.display = \'block\'; b.focuc();  var c = this.parentNode.parentNode.getElementsByClassName( \'object_exterior\' )[0]; c.style.display = \'none\'; this.innerHTML = \'&#8635; Import\' } else {  b.style.display = \'none\'; b.value ? ( this.parentNode.parentNode.outerHTML = b.value ) : null; this.innerHTML = \'&#8635;\'; } pc_makeInnerSettingsAutoRefresh(); "><i class="fa fa-code"></i></a>';
        
        if( @$object['pagewidget_id'] )
        {
            $html .= '<a class="title_button" title="Preview widget on independent link" name="" href="javascript:;" onclick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/widgets/?widget_id=' . $object['pagewidget_id'] . '\' );"><i class="fa fa-external-link"></i></a><a class="title_button " title="Widget ID" name="" href="javascript:;" onclick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Object_PageWidget_Editor/?pagewidget_id=' . $object['pagewidget_id'] . '\' );">Widget ID ' . $object['pagewidget_id'] . ' </a>';
        }

		$html .= method_exists( $object['class_name'], 'getStatusBarLinks' ) ? $object['class_name']::getStatusBarLinks( $object ) : null;

		$html .= '<div style="clear:both;"></div>';
		$html .= '</div>';	//	 status bar
		$html .= '<div style="clear:both;"></div>';


		$html .= "</div>";

		return $html;
    }

    /**
	 * Replacing setViewOption and setViewParameter with a universal method
	 *
     * @param array Parameters meant for this object
     */
    public static function sanitizeParameters( & $parameters )
	{

		if( ! empty( $parameters['advanced_parameters'] ) )
		{
			parse_str( $parameters['advanced_parameters'], $advanceParameters );
		//	var_export( $advanceParameters );
			@$advanceParameters = array_combine( $advanceParameters['advanced_parameter_name'], @$advanceParameters['advanced_parameter_value'] ) ? : array();
		//	var_export( $advanceParameters );
			$parameters += $advanceParameters;
			unset( $parameters['advanced_parameters'] );
		}
	}

    /**
	 * Replacing setViewOption and setViewParameter with a universal method
	 *
     * @param array Parameters meant for this object
     */
    public function setParameter( array $parameters )
	{
        try
        {
            self::setHook( $this, __FUNCTION__, $parameter );

            self::sanitizeParameters( $parameters );
            if( isset( $parameters['view'] ) ){ $this->setViewParameter( $parameters['view'] ); }
            if( isset( $parameters['editable'] ) ){ $this->setViewParameter( $parameters['editable'] ); }
            if( isset( $parameters['option'] ) ){ $this->setViewOption( $parameters['option'] ); }
            $this->_parameter = array_merge( $this->_parameter, $parameters );
        }
        catch( Ayoola_Abstract_Exception $e  )
        {
            //  now hooks can avoid execution of a class init method
        }
	}

    /**
	 *
	 *
     * @param void
     * @return void
     */
    public function clearParametersThatMayBeDuplicated()
	{
		self::unsetParametersThatMayBeDuplicated( $this->_parameter );
	}


    /**
	 *
	 *
     * @param void
     * @return void
     */
    public static function unsetParametersThatMayBeDuplicated( & $parameters )
	{
		unset( $parameters['object_class'] );
		unset( $parameters['object_style'] );
		unset( $parameters['wrapper_name'] );
		unset( $parameters['markup_template_no_data'] );
	}

    /**
	 * Return $parameters
	 *
     * @param string If set, method returns value of $parameters[$key]
     * @return array $parameters
     */
    public function getParameter( $key = null )
	{
		if( is_null( $key ) )
		{
			return $this->_parameter;
		}
	//	var_export( $key . $this->_parameter['parameter_suffix'] );
	//	if( isset( $this->_parameter['parameter_suffix'] ) )
		{
		//	var_export( $key . $this->_parameter['parameter_suffix'] );
		//	return $this->_parameter[$key];
		}
		if( isset( $this->_parameter['parameter_suffix'] ) && array_key_exists( $key . $this->_parameter['parameter_suffix'], $this->_parameter ) )
		{
		//	var_export( $key . $this->_parameter['parameter_suffix'] );
			return $this->_parameter[$key . $this->_parameter['parameter_suffix']];
		}
		if( array_key_exists( $key, $this->_parameter ) )
		{
			return $this->_parameter[$key];
		}
	//	throw new Ayoola_Exception( 'KEY IS NOT AVAILABLE IN PARAMETERS: ' . $key );
	}

    /**
	 * Just incoporating this - So that the layout can be more interative
	 * The layout editor will be able to pass a parameter to the viewable object
     * @param mixed Parameter set from the layout editor
     * @return null
     */
    public function setViewParameter( $parameter )
	{
//	var_Export( __LINE__ );
		$this->_viewParameter = $parameter ;

		//	compatibility.
		$this->_parameter['view'] = $parameter;
	}

    public function setViewOption( $parameter )
	{
		$this->_viewOption = $parameter ;

	//	var_export( $parameter );

		//	compatibility.
		$this->_parameter['option'] = $parameter;
	}

    /**
     * Returns _objectTitle
     *
     */
	 public static function getObjectTitle( $generateName = true )
	 {
		if( static::$_objectTitle )
		{
			return static::$_objectTitle;
		}
		elseif( $generateName )
		{
			$title = str_ireplace( array( 'Ayoola_', 'PageCarton_', 'Application_', 'Article_', 'Object_', 'Classplayer_', ), '', get_called_class() );
			$title = ucwords( implode( ' ', explode( '_', $title ) ) );
			$title = ucwords( implode( ' ', explode( '-', $title ) ) );

			self::$_objectTitle = $title;;
		}
		return self::__( self::$_objectTitle );
	 }

    /**
     * Returns object_name will become the form name or id
     *
     */
	 protected function getObjectName( $className = null )
	 {
		if( $this->objectName )
		{
			return $this->objectName;
		}
		$className = $className ? : get_class( $this );
		$objectName = $className;
		$this->objectName = $objectName;
		return $objectName;
	 }

    /**
	 * Sets the _viewContent
	 *
     */
    public function setViewContent( $content = null, $options = '' )
	{
        try
        {
            if( is_object( $content ) ){ $content = $content->view(); }
            if( ! trim( $content ) )
            {
            //	var_export( $content );
                //	don't return empty tags
                return false;
            }
            if( $options && ! is_array( $options ) )
            {
                $ix = $options;
                $options = array();
                $options['refresh_content'] = $ix;
            }
            if( @$options['translate'] )
            {
                $content = self::__( $content );
            }
            self::setHook( $this, __FUNCTION__, $content );
            if( null === $this->_viewContent || true === @$options['refresh_content'] )
            { 
                $this->_viewContentText = null;
                $this->_viewContent = new Ayoola_Xml();
            //	self::v( get_class( $this ) );
            //	self::v( $this->wrapViewContent );
                if( $this->wrapViewContent && ! $this->getParameter( 'no_view_content_wrap' ) )
                {
                    $element = $this->getParameter( 'object_container_element' ) ? : static::$_viewContentElementContainer;
                    switch( strtolower( $element ) )
                    {
                        case 'div' :
                        case 'span' :
                        case 'section' :

                        break;
                        default:
                        $element = 'div';
                        break;
                    }
            //		self::v( $this->getParameter( 'object_container_element' )  );
                    $documentElement = $this->_viewContent->createElement( $element );
                    $documentElement->setAttribute( 'data-object-name', $this->getObjectName() );
                    $documentElement->setAttribute( 'name', $this->getObjectName() . '_container' );


                    $this->documentElementOTag = '<div>
                                                <' . $element . ' data-object-name="' . $this->getObjectName() . '" name="' . $this->getObjectName() . '_container' . '">';

                    $this->documentElementCTag = '	</' . $element . '>
                                            </div>';

                    $b = $this->_viewContent->createElement( 'div' );
                    $b->appendChild( $documentElement );
                    $this->_viewContent->appendChild( $b );

                    //	Use Named Anchor to reference this content
                    $a = $this->_viewContent->createElement( 'div' );
                    $a->setAttribute( 'name', $this->getObjectName() );
                    $documentElement->appendChild( $a );

                    $this->containerOTag = '<div name="' . $this->getObjectName() . '">';
                    $this->containerCTag = '</div>';
                }
            }
            $contentData = $this->_viewContent->createCDATASection( $content );
            $this->_viewContentText .= $content;
            if( $this->wrapViewContent && ! $this->getParameter( 'no_view_content_wrap' ) )
            {
                    $this->contentTagO = '<' . static::$_viewContentElementContainer . '>';

                    $this->contentTagC = '</' . static::$_viewContentElementContainer . '>';
                $contentTag = $this->_viewContent->createElement( static::$_viewContentElementContainer );
                $contentTag->appendChild( $contentData );
                $this->_viewContent->documentElement->firstChild->appendChild( $contentTag );
            }
            else
            {
                $this->_viewContent->appendChild( $contentData );
            }
            @$this->_viewContentHTML = 	$this->documentElementOTag .
                                            $this->containerOTag . $this->containerCTag .
                                            $this->contentTagO .
                                            $this->_viewContentText .
                                            $this->contentTagC .
                                        $this->documentElementCTag;
        }
        catch( Ayoola_Abstract_Exception $e  )
        {
            //  now hooks can avoid execution of a class init method
        }

	}

    /**
	 * Gets the _viewContent
	 *
     */
    public function getViewContent()
	{
		//why does here sometimes cause "Undefined property: Ayoola_Event_NewSession::$_viewContentHTML"
		return isset($this->_viewContentHTML) ? $this->_viewContentHTML : "";
	}

    /**
     * Returns the markup sent by template for the view method
     *
     * @param void
     * @return string Mark-Up for the view template
     */
    public function getMarkupTemplate( array $options = null )
	{
		/* ALLOWING TEMPLATES TO INJECT MARKUP INTO VIEWABLE OBJECTS */

		if( ! is_null( $this->_markupTemplate ) && ! $options['refresh'] )
		{
			return $this->_markupTemplate;
		}
		$storageNamespace = 'markup_template_c' . $this->getParameter( 'markup_template_namespace' ) . '_' . Ayoola_Application::getUserInfo( 'access_level' );
		$markup = $this->getParameter( 'markup_template_prefix' );
		$markup .= $this->getParameter( 'markup_template' );
		$markup .= $this->getParameter( 'markup_template_suffix' );

		//	Site Wide Storage of this value
		$storage = $this->getObjectStorage( array( 'id' => $storageNamespace, 'device' => 'File', 'time_out' => 100, ) );
		if( $this->getParameter( 'markup_template' ) )
		{
			$this->_markupTemplate = $markup;
			$storage->retrieve() != $this->_markupTemplate && $this->getParameter( 'markup_template_cache' ) ? $storage->store( $this->_markupTemplate ) : null;
		}
		elseif( $storage->retrieve() && ( $this->getParameter( 'markup_template_namespace' ) || Ayoola_Application::getRuntimeSettings( 'real_url' ) == '/tools/classplayer' ) )
		{
			$this->_markupTemplate =  $storage->retrieve();
			null;
		}
		elseif( $this->getParameter( 'markup_template_no_data' ) )
		{
			$this->_markupTemplate = $this->getParameter( 'markup_template_no_data' );
		}
		else
		{
			//	Turn me to false so we dont have to come here again for the same request.
			$this->_markupTemplate = false;
		}
		if( $this->getParameter( 'markup_template_no_data' ) )
		{

        }
		return $this->_markupTemplate;
	}

    /**
     * @param void
     * @return array
     */
    public function getObjectTemplateValues()
	{
		return $this->_objectTemplateValues ? : array();
	}

    /**
     * Returns html content that is useful for display.
     *
     * @param string class name for object to view
     * @param string parameters used to view the object
     * @return string Mark-Up for the view template
     */
    public static function viewObject( $objectName, $parameters = null )
	{
	//		var_export( $objectName );
		if( ! Ayoola_Loader::loadClass( $objectName ) )
		{
			return false;
		}
//		var_export( $objectName );

		return $objectName::viewInLine( $parameters );
	}

    /**
     * Returns html content that is useful for display.
     * Depends on the situation and environment, it will return different content
     * @param void
     * @return string Mark-Up for the view template
     */
    public function view()
	{
        if( ! $this->deviceIsAllowed() )
        {
            return false;
        }

		$this->_playMode = $this->getParameter( 'play_mode' ) ? : $this->_playMode;
		switch( $this->_playMode )
		{
			case static::PLAY_MODE_MUTE:
				exit();
			break;
			case static::PLAY_MODE_JSON:
				error_reporting( E_ALL & ~E_STRICT & ~E_NOTICE & ~E_USER_NOTICE );
				ini_set( 'display_errors', "0" );
				header( 'Content-Type: application/json; charset=utf-8' );
				if( @$_POST['PAGECARTON_RESPONSE_WHITELIST'] )
				{

					//	Limit the values that is being sent
					$whitelist = @$_POST['PAGECARTON_RESPONSE_WHITELIST'];
					$whitelist = is_array( $whitelist ) ? $whitelist : array_map( 'trim', explode( ',', $whitelist ) );
					$whitelist = array_combine( $whitelist, $whitelist );
					$this->_objectData = array_intersect_key( $this->_objectData, $whitelist );
				}
				$dataToSend = json_encode( $this->_objectData );

				//	json data was being truncated
			    //	header( 'Content-Length: ' . strlen( $dataToSend ) );
				echo $dataToSend;

				//	Log early before we exit
				Ayoola_Application::log();
				{
					exit();
				}
			break;
			case static::PLAY_MODE_JSONP:
				error_reporting( E_ALL & ~E_STRICT & ~E_NOTICE & ~E_USER_NOTICE );
				ini_set( 'display_errors', "0" );

				header( 'Content-Type: application/javascript;' );
				if( @$_POST['PAGECARTON_RESPONSE_WHITELIST'] )
				{

					//	Limit the values that is being sent
					$whitelist = @$_POST['PAGECARTON_RESPONSE_WHITELIST'];
					$whitelist = is_array( $whitelist ) ? $whitelist : array_map( 'trim', explode( ',', $whitelist ) );
					$whitelist = array_combine( $whitelist, $whitelist );
					$this->_objectData = array_intersect_key( $this->_objectData, $whitelist );
				}
				$dataToSend = json_encode( $this->_objectData );
			//	header( 'Content-Length: ' . strlen( $dataToSend ) );

				echo $dataToSend;
				//	Log early before we exit
				Ayoola_Application::log();
				{
					exit();
				}
			break;
			case 'ENCRYPTION':

				header( "Content-Disposition: attachment;filename=encryption" );
				header( 'Content-Type: application/octet-stream' );
				//	Introduce timeout to prevent a replay attack.
			//	if( isset( $_POST['pagecarton_request_timezone'], $_POST['pagecarton_request_time'], $_POST['pagecarton_request_timeout'] ) )
				{
					$this->_objectData['pagecarton_response_timezone'] = date_default_timezone_get();
					$this->_objectData['pagecarton_response_time'] = time();
					$this->_objectData['pagecarton_response_timeout'] = 50;
				}
				if( @$_POST['pagecarton_response_whitelist'] )
				{

					//	Limit the values that is being sent
					$whitelist = @$_POST['pagecarton_response_whitelist'];
					$whitelist = is_array( $whitelist ) ? $whitelist : array_map( 'trim', explode( ',', $whitelist ) );
					$whitelist = array_combine( $whitelist, $whitelist );
					$this->_objectData = array_intersect_key( $this->_objectData, $whitelist );
				}

				$dataToSend = json_encode( $this->_objectData );
				$encrypted = OpenSSL::encrypt( $dataToSend, $_SERVER['HTTP_PAGECARTON_RESPONSE_ENCRYPTION'] );
                echo $encrypted;
                
				//	Log early before we exit
				Ayoola_Application::log();
				{
					exit();
				}
			break;
			case static::PLAY_MODE_PHP:
				$dataToSend = serialize( $this->_objectData );
                echo $dataToSend;
                
				//	Log early before we exit
				Ayoola_Application::log();
				{
					exit();
				}
			break;
			case static::PLAY_MODE_HTML:
				$content = null;
				$html = null;
				$content = $this->getViewContent();

				if( ! $template = $this->getMarkupTemplate() )
				{
					//	Allow page builder to be able to set a default content incase theres no data used as template markup
			        //		$html = $this->getParameter( 'markup_template_no_data' );
					if( ! $template )
					{
						$html = $content;
					}
					else
					{
					//	var_export( $html );
					//	var_export( $this->getParameter( 'markup_template' ) );
					}
				}
				else
				{
					if( @$this->_form )
					{
						Application_Javascript::addCode
						(
							'
								ayoola.events.add
								(
									window,
									"load",
									function()
									{

										ayoola.xmlHttp.setAfterStateChangeCallback
										(
											function()
											{
												var a = document.getElementById( "' . $this->getObjectName() . '_form_goodnews" );
												if( a )
												{
													//	workaround for a bug that makes content for the goodnews show the whole view content
													a.id = "";
													ayoola.spotLight.popUp( a.innerHTML );
												}

											}
										);
									}
								);
							'
						);
						//	Lets insert form requirements in the artificial form fields
						$this->_objectTemplateValues = array_merge( $_REQUEST, $this->_objectTemplateValues );
						$this->_objectTemplateValues['template_object_name'] = $this->getObjectName();

						//	internally count the instance
						$this->_objectTemplateValues['template_instance_count'] = static::$_counter;
						$this->_objectTemplateValues['template_form_requirements'] = $this->getForm()->getRequiredFieldset()->view();
						$this->_objectTemplateValues['template_form_badnews'] = null;
						$this->_objectTemplateValues['template_form_goodnews'] = null;
						if( $this->getForm()->getBadnews() )
						{
							$this->_objectTemplateValues['template_form_badnews'] .= '<ul>';
							foreach( $this->getForm()->getBadnews() as $message )
							{
								$this->_objectTemplateValues['template_form_badnews'] .= "<li class='badnews'>$message</li>\n";
							}
							$this->_objectTemplateValues['template_form_badnews'] .= '</ul>';
						}
						elseif( $this->getForm()->getValues() )
						{
							//	used to disable forms for avoid multiple submissions after form completion
							$this->_objectTemplateValues['template_form_disable'] = 'disabled="disabled"';

							$this->_objectTemplateValues['template_form_goodnews'] = '<span id="' . $this->getObjectName() . '_form_goodnews"><span class="goodnews boxednews fullnews centerednews">' . $content . '</span></span>';

						}
					}

					//	Add the Ayoola_Application Global
					//	adding this global causes variable to be available on widgets using same variables
					//	like username
					#	Don't display user infor for signed out user
				    //	$this->_objectTemplateValues = array_merge( @Ayoola_Application::$GLOBAL['post'] ? : array(), $this->_objectTemplateValues );
                    //	$this->_objectTemplateValues = array_merge( @Ayoola_Application::$GLOBAL['profile'] ? : array(), $this->_objectTemplateValues );
                    //  only show widget if some parameters are true
                    if( $this->getParameter( 'required_template_variables' ) )
                    {
                        $mustHaves = array_map( 'trim', explode( ',', $this->getParameter( 'required_template_variables' ) ) );
                        foreach( $mustHaves as $mustHave )
                        {
                            if( empty( $this->_objectTemplateValues[$mustHave] ) )
                            {
                                return false;
                            }
                        }

                    }
                            
					//	allows me to add pagination on post listing with predefined suffix
					$template = $this->getParameter( 'markup_template_prepend' ) . $template;
					$template = $template . $this->getParameter( 'markup_template_append' );
					$template = Ayoola_Abstract_Playable::replacePlaceholders( $template, $this->_objectTemplateValues + array( 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', ) );

					//	fix case where ajax auto-loading didn't fix url prefix in posts
					$template = Ayoola_Page_Editor_Text::fixUrlPrefix( $template, $this->getParameter( 'url_prefix' ), Ayoola_Application::getUrlPrefix() );

					$html = $template;
				}
				if( $this->getParameter( 'wrapper_name' ) && $html )
				{
                //    var_export( $this );
					$html =  '<div class="'. $this->getParameter( 'wrapper_inner_class' ) .'">' . $html . '</div>';
					$html =  Ayoola_Object_Wrapper_Abstract::wrapContent( $html, $this->getParameter( 'wrapper_name' ) );
				}
				if( ( $this->getParameter( 'object_style' ) || $this->getParameter( 'object_class' ) ) && $html )
				{
					$html = '<div class="'. $this->getParameter( 'object_class' ) .'" style="'. $this->getParameter( 'object_style' ) . '">' . $html . '</div>';
					//	self::v( $template );
				}
                //	Define content to clear from the screen
                $contentToClear = $this->getParameter( 'content_to_clear' ) . ( isset( $this->_parameter['content_to_clear_internal'] ) ? $this->_parameter['content_to_clear_internal'] : "" );
                if( $contentToClear )
                {
                    $search = array_map( 'trim', explode( "\n", $contentToClear ) );
                    $html = str_replace( $search, '', $html );
                    //	self::v( $template );
                }
                try
                {        
                    //  allow view to be filtered or maninpulated by hooks
                    self::setHook( $this, __FUNCTION__, $html );
                    
                    return $html;
                }
                catch( Ayoola_Abstract_Exception $e  )
                {
                    //  now hooks can avoid execution of a class init method
                }
            
			break;
			default:

			break;
		}
	}
	// END OF CLASS
}
