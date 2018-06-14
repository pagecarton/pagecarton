<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
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
 * @category   PageCarton CMS
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
     * Useful for lists
     *
     * @var Ayoola_Paginator
     */
	protected $_list;
	
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
     *
     * @var array
     */
	protected static $_wrapperOptions;
	
 
    /**
     * My User Agent Name
     * 
     * @var string
     */
	public static $userAgent = 'Mozilla/5.0 ( compatible; ayoolabot/0.1; +http://ayoo.la/bot/ )';
 
	/**
     * constructor
     * 
     */
	public function __construct( $parameter = null )
    {
		foreach( self::getHooks() as $class )
		{
			$class::hook( $this, __FUNCTION__, func_get_args() );
		}
		if( ! $parameter )
		{
			if( Ayoola_Application::isXmlHttpRequest() || Ayoola_Application::isClassPlayer() ){ return null; }
		}
		if( is_array( $parameter ) ){ $this->setParameter( $parameter ); }
		//	var_export( self::getHooks() );
		$this->initOnce();
		static::$_counter++;
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
		if( $all = PageCarton_Hook::getInstance()->select( null, array( 'class_name' => $class ) ) )
		{
			foreach( $all as $each )
			{
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
					Share on | 
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
		$device = null;
		if( is_string( $storageInfo ) )
		{
			$id = $storageInfo;
		}
		elseif( is_array( $storageInfo ) )
		{
			$id = $storageInfo['id'];
			$device = @$storageInfo['device'];
			$timeOut = @$storageInfo['time_out'];  
		}
		
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
     * Sends email
     * 
     */
	public static function sendMail( array $mailInfo )
    {
//		var_export( $mailInfo );
//		var_export( empty( $mailInfo['body'] ) );
		if( empty( $mailInfo['body'] ) )
		{ 
			return false;
		//	throw new Ayoola_Abstract_Exception( 'E-mail cannot be sent without a body' ); 
		}
		if( empty( $mailInfo['to'] ) )
		{ 
			return false;
		//	throw new Ayoola_Abstract_Exception( 'E-mail destination was not specified' ); 
		}
		if( empty( $mailInfo['from'] ) )
		{ 
		//	$mailInfo['from'] = 'no-reply@' . Ayoola_Page::getDefaultDomain(); 
			$mailInfo['from'] = '' . ( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Page::getDefaultDomain() ) . ' <no-reply@' . Ayoola_Page::getDefaultDomain() . '>' . "\r\n";
		}
//		var_export( $mailInfo );
		if( empty( $mailInfo['subject'] ) ){ $mailInfo['subject'] = 'Account Notice'; }
		$header = 'From: ' . $mailInfo['from'] . "";
		if( ! empty( $mailInfo['bcc'] ) )
		{ 
			$header .= "bcc: {$mailInfo['bcc']}\r\n";
		//	var_export( $header );
		}
		if( ! empty( $mailInfo['html'] ) )
		{ 
			$header .= "MIME-Version: 1.0\r\n";
			$header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		}
		$sent = mail( $mailInfo['to'], $mailInfo['subject'], $mailInfo['body'], $header );
	//	exit( var_export( $mailInfo ) );
	//	if( ! $sent ){ throw new Ayoola_Abstract_Exception( 'Error encountered while sending e-mail' ); }
		return true;
    } 
  	
    /** 
     * Fetches a remote link
     *
     * @param string Link to fetch
     * @param array Settings  
     */
    public static function fetchLink( $link, array $settings = null )
    {	
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
		curl_setopt($request, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, 0);   

		curl_setopt( $request, CURLOPT_USERAGENT, @$settings['user_agent'] ? : self::$userAgent );
		curl_setopt( $request, CURLOPT_AUTOREFERER, true );
		curl_setopt( $request, CURLOPT_REFERER, @$settings['referer'] ? : $link );
		if( @$settings['destination_file'] )
		{
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
		curl_setopt( $request, CURLOPT_CONNECTTIMEOUT, @$settings['connect_time_out'] ? : 1000 );	//	Max of 1 Secs on a single request
		curl_setopt( $request, CURLOPT_TIMEOUT, @$settings['time_out'] ? : 1000 );	//	Max of 1 Secs on a single request
		if( @$settings['post_fields'] )
		{
			curl_setopt( $request, CURLOPT_POST, true );
			curl_setopt( $request, CURLOPT_POSTFIELDS, $settings['post_fields'] );
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
 		if( $responseOptions['http_code'] != 200 ){ return false; }
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
		|| ( in_array( 98, $allowedLevels ) && $username && $username === strtolower( @Ayoola_Application::$GLOBAL['username'] ) ) //	Profile owner means he is authorized
		|| ( in_array( 97, $allowedLevels ) && $username && ( in_array( strtolower( Ayoola_Application::getUserInfo( 'email' ) ), array_map( 'strtolower', @Ayoola_Application::$GLOBAL['whitelist_email_address'] ? : array() ) ) || $username === strtolower( @Ayoola_Application::$GLOBAL['username'] ) ) ) //	We were invited to view a post/article
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
		  
		if( in_array( 98, $allowedLevels ) && ! empty( Ayoola_Application::$GLOBAL['profile_url'] ) && is_array( $userInfo['profiles'] ) && in_array( Ayoola_Application::$GLOBAL['profile_url'], $userInfo['profiles'] ) ) //	profile owner
		{
			return true;
		}
		  
		if( in_array( 1, $allowedLevels ) && intval( $myLevel ) > 1 ) //	Deleted user levels should at least have the level of a standard user
		{
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
		if( self::hasPriviledge() )
		{ 
			var_export( $variable );
		}
		elseif( $_REQUEST['pc_show_error'] )
		{
			var_export( $variable );
		}
		
		
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
			if( ! is_scalar( $value ) )
			{
				continue;
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
     * @param void
     * @return array
     */
    protected static function getParameterKeys( & $parameters )
    {
		$thisClass = get_called_class();
//		var_export( $thisClass );
		$thisObjectID = $thisClass . $parameters['object_unique_id'];
		if( ! empty( static::$_parameterKeys[$thisObjectID] ) )
		{
			return static::$_parameterKeys[$thisObjectID];
		}
		$classes = array( $thisClass );
	//	var_export( static::getParameterKeysFromTheseOtherClasses( $parameters ) );
		if( is_array( static::getParameterKeysFromTheseOtherClasses( $parameters ) ) )
		{
			$classes = array_merge( $classes, static::getParameterKeysFromTheseOtherClasses( $parameters ) );
		}
	//	var_export( $classes );
		$content = file_get_contents( __FILE__ ) ;
		$filter = new Ayoola_Filter_ClassToFilename();
		foreach( $classes as $class )
		{
			if( ! Ayoola_Loader::loadClass( $class ) )
			{
				continue;
			}
			$classFile = $filter->filter( $class );
			$classFile = Ayoola_Loader::getFullPath( $classFile );
		//	var_export( $classFile );
			$content .= file_get_contents( $classFile ) ;
		}
//		$class = get_called_class();


		preg_match_all( "/getParameter\( '([a-z_-]*)' \)/", $content, $results );
	//	var_export( $class );
		sort( $results[1] );
		static::$_parameterKeys[$thisObjectID] = $results[1];   
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
		$html = null;
		$object['object_name'] = $object['object_name'] ? : $object['class_name'];
		$object['object_unique_id'] = @$object['object_unique_id'] ? : ( md5( $object['object_name'] ) . rand( 100, 1000 ) );
		$advancedName = 'advanced_parameters_' . $object['object_unique_id'] . '';
		$html .= "<div data-class_name='{$object['class_name']}' name='over_all_object_container' class='DragBox' id='" . $object['object_unique_id'] . "' title='Move this object by dragging it around - " . $object['view_parameters'] . "' data-object_name='{$object['object_name']}' >";
		$title = ( ( $object['view_parameters'] ? : $object['object_name']::getObjectTitle() ) ? : $object['object_name'] );
		//	title bar
		$html .= '<div draggable=\'true\' ondragstart=\'ayoola.dragNDrop.dragMyParent(event);\' style="cursor: move; cursor: -moz-grab;cursor: -webkit-grab;" title="' . $title . '" class="title_bar pc_page_object_specific_item" data-parameter_name="parent">'; 
		
		
		//	Delete button
		$html .= '<span class="title_button close_button"  name="" href="javascript:;" class="" title="Delete this object" onclick="this.parentNode.parentNode.parentNode.removeChild( this.parentNode.parentNode );"> x </span>';
		
		//	Maximize
		$html .= '<a class="title_button" name="' . $advancedName . '" href="javascript:;" title="Click to show or hide advanced settings" onclick="  var b = this.parentNode.parentNode.getElementsByClassName( \'advanced_options\' );for( var a = 0; a < b.length; a++ ){  b[a].style.display = ( b[a].style.display == \'none\' ) ? \'\' : \'none\'; this.style.display = \'\'; } "> &square; </a>'; 
		
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
		$html .= '<div style="border: #ccc 1px solid;padding:0.5em;padding:0 0.5em 0 0.5em;' . $openAdvancedOption . '" title="" class="status_bar advanced_options pc_page_object_specific_item " data-parameter_name="parent">'; 
//		$html .= '<div style="clear:both;" name="' . $advancedName . '" class=""><label>Inject some parameters to this object...</label></div>';		

			$form = new Ayoola_Form( array( 'name' => $advancedName, 'data-parameter_name' => 'advanced_parameters', 'class' => '' ) );
			parse_str( @$object['advanced_parameters'], $advanceParameters );
		//	$advanceParameters['advanced_parameter_value'][] = 'tested' . time();
		//	$advanceParameters['advanced_parameter_name'][] = 'test' . time();
		//	var_export( $advanceParameters );
			self::sanitizeParameters( $object );


			$object = array_merge( $advanceParameters, $object );
			if( method_exists( $object['class_name'], 'getHTMLForLayoutEditorAdvancedSettings' ) )
			{
				$fieldset = new Ayoola_Form_Element();
				$fieldset->addElement( array( 'name' => 'x', 'type' => 'html', ), array( 'html' => $object['class_name']::getHTMLForLayoutEditorAdvancedSettings( $object ) ) );
				$form->addFieldset( $fieldset );
			//	var_export( $object['class_name']::getHTMLForLayoutEditorAdvancedSettings( $object ) );
			//	$html .= ;
			}
			$form->setParameter( array( 'no_required_fieldset' => true ) );
			$i = 0;
		//	$object['advanced_parameter_name'] = html_entity_decode( @$object['advanced_parameter_name'] );
		//	var_export( parse_str( $object['advanced_parameter_name'] ) );
		//	var_export( @$object['advanced_parameter_name'] );
		//	var_export( $advanceParameters );
			do
			{
				
			//	$each = array_pop( $object['advanced_parameter_name'] );
				
				$fieldset = new Ayoola_Form_Element; 
				$fieldset->hashElementName = false;
				$fieldset->container = 'div';
			//	$fieldset->addLegend( 'Inject some parameters to this object...' );
			//	$fieldset->addLegend( ' ' );
			//	$form->submitValue = $submitValue ;
			//	$form->oneFieldSetAtATime = true;
				$form->wrapForm = false;
				$parameterOptions = array( '' => 'Parameter Name' ) + ( array_combine( static::getParameterKeys( $object ), static::getParameterKeys( $object ) ) ? : array() );
		//		if( ! array_key_exists( @$advanceParameters['advanced_parameter_value'][$i], $parameterOptions ) )
				{
				//	$parameterOptions[@$advanceParameters['advanced_parameter_value'][$i]] = @$advanceParameters['advanced_parameter_value'][$i];
				}
				if( ! array_key_exists( @$advanceParameters['advanced_parameter_name'][$i], $parameterOptions ) )
				{
				//	var_export( $advanceParameters['advanced_parameter_name'][$i] );
				//	var_export( $advanceParameters['advanced_parameter_value'][$i] );
					$parameterOptions[$advanceParameters['advanced_parameter_name'][$i]] = $advanceParameters['advanced_parameter_name'][$i];
				//	var_export( $parameterOptions[$advanceParameters['advanced_parameter_name'][$i]] );
				}
				switch( @$advanceParameters['advanced_parameter_name'][$i] )
				{
					case 'markup_template':
					case 'markup_template_no_data':
					case 'markup_template_prepend':
					case 'markup_template_append':
					case 'markup_template_prefix':
					case 'markup_template_suffix':
					case 'call_to_action':
					case 'content_to_clear':
					case 'body':
					//	if( $advanceParameters['advanced_parameter_value'][$i] )       
						{
					//		$fieldset->addElement( array( 'name' => 'advanced_parameter_name[]', 'label' => 'HTML Markup', 'placeholder' => 'Parameter Name', 'type' => 'Hidden', 'value' => @$advanceParameters['advanced_parameter_name'][$i] ) );
							if( ! array_key_exists( @$advanceParameters['advanced_parameter_name'][$i], $parameterOptions ) )
							{
								$parameterOptions[@$advanceParameters['advanced_parameter_name'][$i]] = @$advanceParameters['advanced_parameter_name'][$i];
							}
							$fieldset->addElement( array( 'name' => 'advanced_parameter_name[]', 'label' => '', 'placeholder' => 'Parameter Name', 'type' => 'Select', 'value' => @$advanceParameters['advanced_parameter_name'][$i] ), $parameterOptions );
							$fieldset->addElement( array( 'name' => 'advanced_parameter_value[]', 'label' => '', 'placeholder' => 'Parameter Value', 'type' => 'TextArea', 'style' => 'width:100%;', 'value' => @$advanceParameters['advanced_parameter_value'][$i] ) );
							$fieldset->allowDuplication = true;  
							$fieldset->placeholderInPlaceOfLabel = true;
						}
					break;    
					default:
						if( static::getParameterKeys( $object ) )
						{
					//		var_export( $advanceParameters['advanced_parameter_name'][$i] );
					//		var_export( $parameterOptions );  
							$fieldset->addElement( array( 'name' => 'advanced_parameter_name[]', 'label' => '', 'placeholder' => 'Parameter Name', 'type' => 'Select', 'value' => @$advanceParameters['advanced_parameter_name'][$i] ), $parameterOptions );
							$fieldset->addElement( array( 'name' => 'advanced_parameter_value[]', 'label' => '', 'placeholder' => 'Parameter Value', 'type' => 'InputText', 'value' => @$advanceParameters['advanced_parameter_value'][$i] ) );
							$fieldset->allowDuplication = true;  
							$fieldset->placeholderInPlaceOfLabel = true;
						}
					break;
				}
				$form->addFieldset( $fieldset );
				$i++;
			}
			while( ! empty( $advanceParameters['advanced_parameter_name'][$i] ) );
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
		//	$options[97] = 'Page Owners Only';  
			$fieldset = new Ayoola_Form_Element; 
			$fieldset->hashElementName = false;
		//	$fieldset->addLegend( 'Select user groups that would be able to view this object...' );
			$fieldset->addElement( array( 'name' => 'xx', 'type' => 'Html' ), array( 'html' => '<label>Widget Privacy</label>' ) );
			$fieldset->addElement( array( 'name' => 'object_access_level', 'id' => $object['object_unique_id'] . '_object_access_level', 'label' => ' ', 'placeholder' => '', 'type' => 'Checkbox', 'value' => @$advanceParameters['object_access_level'] ), self::$_authLevelOptions );

			$inlineWrapperChange = false;
	//		var_export( is_a( $object['class_name'], Ayoola_Page_Editor_Text ) );
	//		var_export( $object['class_name'] );
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
		//		$options .= '<option ' . $selected . ' value="' . $eachWrapper['wrapper_name'] . '">' . $eachWrapper['wrapper_label'] . '</option>';
				if( ! $inlineWrapperChange )
				{
					$eachWrapper['wrapper_suffix'] = null;
					$eachWrapper['wrapper_prefix'] = null;
				}
				$options .= '<option ' . $selected . ' value="' . $eachWrapper['wrapper_name'] . '">' . $eachWrapper['wrapper_label'] . '</option>';
//				$options .= '<option ' . $selected . ' data-wrapper_prefix="' . htmlentities( $eachWrapper['wrapper_prefix'] ). '" data-wrapper_suffix="' .  htmlentities( $eachWrapper['wrapper_suffix'] ) . '" value="' . $eachWrapper['wrapper_name'] . '">' . $eachWrapper['wrapper_label'] . '</option>';
			}
			$options .= '</select>';
			$fieldset->addElement( array( 'name' => 'wrapper_label', 'type' => 'Html' ), array( 'html' => '<p><label>Wrapper</label>' . $options . '</p>', 'fields' => 'wrapper_name' ) );
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
		if( @$object['markup_template'] )
		{
			$html .= '<textarea name="' . $advancedName . '" placeholder="Enter HTML template to use" data-parameter_name="markup_template" style="width:100%;" onclick="">' . @$object['markup_template'] . '</textarea>';
		}

		//	just for padding.
		$html .= '<div class="pc_page_object_specific_item" style="padding-top:0.5em; padding-bottom:0.5em;"></div>'; 
		
		$html .= '</div>';	//	 interior
		$html .= @$currentWrapper['wrapper_suffix'];	//	 wrapper
		$html .= '</div>';	//	 exterior
		$html .= "<textarea onclick='this.focus();this.select()' style='display:none; width:100%;' class='import_export_content' title='Copy contents and paste where you want to export.'> </textarea>";		
		
		//	status bar
		$html .= '<div name="' . $advancedName . '_interior" style="' . $openOrNot . '" title="' . $object['view_parameters'] . '" class="status_bar pc_page_object_specific_item">'; 
				
		//	Help
		$html .= '<a class="title_button" title="Seek help on how to use this page editor" name="" href="http://pagecarton.org/docs" onclick="this.target=\'_new\'">?</a>'; 

		//	Export
		$html .= '<a class="title_button" title="Import or export object" name="" href="javascript:;" onclick="var b = this.parentNode.parentNode.getElementsByClassName( \'import_export_content\' ); b = b[0];  if( b.style.display == \'none\' ){  b.value = this.parentNode.parentNode.outerHTML; b.style.display = \'block\'; b.focuc();  var c = this.parentNode.parentNode.getElementsByClassName( \'object_exterior\' )[0]; c.style.display = \'none\'; this.innerHTML = \'&#8635; Import\' } else {  b.style.display = \'none\'; b.value ? ( this.parentNode.parentNode.outerHTML = b.value ) : null; this.innerHTML = \'&#8635;\'; } pc_makeInnerSettingsAutoRefresh(); ">&#8635;</a>'; 
//		$html .= '<a class="title_button" title="Import or export object" name="" href="javascript:;" onclick="var a = window.prompt( \'Copy to clipboard: Ctrl+C, Enter\', this.parentNode.parentNode.outerHTML ); if( a ){ this.parentNode.parentNode.outerHTML = a; }">&#8635;</a>'; 
				
		$html .= method_exists( $object['class_name'], 'getStatusBarLinks' ) ? $object['class_name']::getStatusBarLinks( $object ) : null; 
		
		$html .= '<div style="clear:both;"></div>';
		$html .= '</div>';	//	 status bar
		

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
		foreach( self::getHooks() as $class )
		{
			$class::hook( $this, __FUNCTION__, func_get_args() );
		}
		self::sanitizeParameters( $parameters );
		if( isset( $parameters['view'] ) ){ $this->setViewParameter( $parameters['view'] ); }
		if( isset( $parameters['editable'] ) ){ $this->setViewParameter( $parameters['editable'] ); }
		if( isset( $parameters['option'] ) ){ $this->setViewOption( $parameters['option'] ); }
		$this->_parameter = array_merge( $this->_parameter, $parameters );
	}

    /**
	 * 
	 * 		
     * @param void
     * @return void
     */
    public function clearParametersThatMayBeDuplicated()
	{
		unset( $this->_parameter['object_class'] );
		unset( $this->_parameter['object_style'] );
		unset( $this->_parameter['wrapper_name'] );
		unset( $this->_parameter['markup_template_no_data'] );
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
     * Returns _objectTitle will become the form name or id
     * 
     */
	 public static function getObjectTitle()
	 {
		if( static::$_objectTitle )
		{
			return static::$_objectTitle;
		}
		$title = str_ireplace( array( 'Ayoola_', 'PageCarton_', 'Application_', 'Article_', 'Object_', 'Classplayer_', ), '', get_called_class() );  
		$title = ucwords( implode( ' ', explode( '_', $title ) ) );
		$title = ucwords( implode( ' ', explode( '-', $title ) ) );
		
		self::$_objectTitle = $title;;
		return self::$_objectTitle;
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
/*		$objectName = new Ayoola_Object_Table_ViewableObject();
	//	var_export( $className );
		$objectName = $objectName->selectOne( null, array( 'class_name' => $className ) );
	//	var_export( $objectName );
		@$objectName = $objectName['object_name'] ? : $className;
	//	var_export( $objectName );
*/		$this->objectName = $objectName;
		return $objectName;
	 }
 
    /**
	 * Sets the _viewContent
	 *
     */
    public function setViewContent( $content = null, $refresh = false )
	{
	//	if( is_object( $content ) ) var_export( $content );
		if( is_object( $content ) ){ $content = $content->view(); }
		if( ! trim( $content ) )
		{
		//	var_export( $content );
			//	don't return empty tags
			return false;
		}
		foreach( self::getHooks() as $class )
		{
			$class::hook( $this, __FUNCTION__, func_get_args() );
		}
		if( null === $this->_viewContent || true === $refresh )
		{ 
			$this->_viewContent = new Ayoola_Xml();
			if( $this->wrapViewContent && ! $this->getParameter( 'no_view_content_wrap' ) )
			{
				$documentElement = $this->_viewContent->createElement( $this->getParameter( 'object_container_element' ) ? : static::$_viewContentElementContainer );  
				$documentElement->setAttribute( 'data-object-name', $this->getObjectName() );
				$documentElement->setAttribute( 'name', $this->getObjectName() . '_container' );
			//	var_export( $this->getParameter( 'object_container_element' )  );
			//	$this->getParameter( 'object_class' ) ? $documentElement->setAttribute( 'class', $this->getParameter( 'object_class' ) ) : null;
		//		$this->getParameter( 'object_style' ) ? $documentElement->setAttribute( 'style', $this->getParameter( 'object_style' ) ) : null;   
				$this->_viewContent->appendChild( $documentElement );
				
				//	Use Named Anchor to reference this content
				$a = $this->_viewContent->createElement( 'div' );
				$a->setAttribute( 'name', $this->getObjectName() );   
				$documentElement->appendChild( $a );
			}
		}
		$contentData = $this->_viewContent->createCDATASection( $content );
		if( $this->wrapViewContent && ! $this->getParameter( 'no_view_content_wrap' ) )
		{
			$contentTag = $this->_viewContent->createElement( static::$_viewContentElementContainer ); 
			$contentTag->appendChild( $contentData );
			$this->_viewContent->documentElement->appendChild( $contentTag );
		}
		else
		{
			$this->_viewContent->appendChild( $contentData );  
		}
	//	$this->_viewContent->view(); exit();
	//	var_export( $content );
		
	}

    /**
	 * Gets the _viewContent
	 *
     */
    public function getViewContent()     
	{
		if( null === $this->_viewContent ){ return; } 	//	don't return empty tags
		return $this->_viewContent->saveHTML();
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
	//	self::v( get_class( $this ) );
		
		if( ! is_null( $this->_markupTemplate ) && ! $options['refresh'] )
		{
		//	self::v( $this->_markupTemplate );
			return $this->_markupTemplate;
		}
/* 	//	if( ! $this->getParameter( 'markup_template' ) )
		{
			//	Turn me to false so we dont have to come here again for the same request.
			$this->_markupTemplate = false;
			return $this->_markupTemplate;
		}
 */		$storageNamespace = 'markup_template_c' . $this->getParameter( 'markup_template_namespace' ) . '_' . Ayoola_Application::getUserInfo( 'access_level' );
		$markup = $this->getParameter( 'markup_template_prefix' );
		$markup .= $this->getParameter( 'markup_template' );
		$markup .= $this->getParameter( 'markup_template_suffix' );
		
		//	Site Wide Storage of this value
		$storage = $this->getObjectStorage( array( 'id' => $storageNamespace, 'device' => 'File', 'time_out' => 100, ) );
	//	$this->_markupTemplate = $this->_markupTemplate ? : $this->getObjectStorage( $storageNamespace )->retrieve();
	//	$this->_markupTemplate =  $this->getParameter( 'markup_template' ) ? $markup : $storage->retrieve();
		if( $this->getParameter( 'markup_template' ) )
		{
			$this->_markupTemplate = $markup;
		//	self::v( $this->getParameter( 'markup_template' ) );

			// prevent multiple disk writes
		//	$storage->store( $this->_markupTemplate );  
		//	$storage->retrieve() != $this->_markupTemplate && ! $this->getParameter( 'markup_template_no_cache' ) ? $storage->store( $this->_markupTemplate ) : null;
			$storage->retrieve() != $this->_markupTemplate && $this->getParameter( 'markup_template_cache' ) ? $storage->store( $this->_markupTemplate ) : null;
		}
		elseif( $storage->retrieve() && ( $this->getParameter( 'markup_template_namespace' ) || Ayoola_Application::getRuntimeSettings( 'real_url' ) == '/tools/classplayer' ) ) 
		{
			$this->_markupTemplate =  $storage->retrieve();
			null;
		}
		elseif( $this->getParameter( 'markup_template_no_data' ) )
		{
		//	var_export( $this->getParameter( 'markup_template_no_data' ) );
		//	var_export( $this->getParameter( 'markup_template' ) );
			$this->_markupTemplate = $this->getParameter( 'markup_template_no_data' );
		}
		else
		{
			//	Turn me to false so we dont have to come here again for the same request.
			$this->_markupTemplate = false;
		}
		if( $this->getParameter( 'markup_template_no_data' ) )
		{
		//	var_export( $this->getParameter( 'markup_template_no_data' ) );
		//	var_export( $this->getParameter( 'markup_template' ) );
	//		$this->_markupTemplate = $this->getParameter( 'markup_template_no_data' );
		}
	//	$storage->clear(  );  
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
	//	Ayoola_Form::v( ( get_class( $this ) ) . '<br />' );
	//	Ayoola_Form::v( ( $this->timeStart ) . '<br />' );
	//	Ayoola_Form::v( ( microtime( true ) - $this->timeStart ) . '<br />' );
	//	Ayoola_Form::v( ( microtime( true ) - Ayoola_Application::getRuntimeSettings( 'start_time' ) ) . '<br />' );
		foreach( self::getHooks() as $class )
		{
			$class::hook( $this, __FUNCTION__ );
		}

		$this->_playMode = $this->getParameter( 'play_mode' ) ? : $this->_playMode;
	//	var_export( get_class( $this ) );
	//	var_export( $this->_playMode );
/* 		if( @$_POST['a'] == 'test' )
		{
		//	var_export( $_SERVER );
		}
 */	
		switch( $this->_playMode )
		{
			case static::PLAY_MODE_MUTE:
				exit();
			break;
			case static::PLAY_MODE_JSON:
				error_reporting( E_ALL & ~E_STRICT & ~E_NOTICE & ~E_USER_NOTICE );
				ini_set( 'display_errors', "0" ); 
			//	var_export( $this->_objectData );
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
				header( 'Content-Length: ' . strlen( $dataToSend ) );
				echo $dataToSend;
				
				//	Log early before we exit
				Ayoola_Application::log();
			//	if( ! self::hasPriviledge() )
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
				header( 'Content-Length: ' . strlen( $dataToSend ) );
				
				echo $dataToSend;
				//	Log early before we exit
				Ayoola_Application::log();
			//	if( ! self::hasPriviledge() )
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
			//	var_export( $_SERVER['HTTP_PAGECARTON_RESPONSE_ENCRYPTION'] );
				$encrypted = OpenSSL::encrypt( $dataToSend, $_SERVER['HTTP_PAGECARTON_RESPONSE_ENCRYPTION'] );
			//	var_export( $encrypted );
			//	echo $dataToSend;
			//	echo base64_encode( $encrypted );
				header( 'Content-Length: ' . strlen( $encrypted ) );
				echo $encrypted;
				//	Log early before we exit
				Ayoola_Application::log();
			//	if( ! self::hasPriviledge() )
				{
					exit();
				}
			break;
			case static::PLAY_MODE_PHP:
				$dataToSend = serialize( $this->_objectData );
				header( 'Content-Length: ' . strlen( $dataToSend ) );
				echo $dataToSend;
				//	Log early before we exit
				Ayoola_Application::log();
			//	if( ! self::hasPriviledge() )
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
					//	return $content;
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
					//	var_export( $this->getParameter() );
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
				
	//				self::v( $this->_objectTemplateValues );
					//	Add the Ayoola_Application Global
					$this->_objectTemplateValues = array_merge( Ayoola_Application::$GLOBAL ? : array(), $this->_objectTemplateValues );
		
					//	allows me to add pagination on post listing with predefined suffix
					$template = $this->getParameter( 'markup_template_prepend' ) . $template;
					$template = $template . $this->getParameter( 'markup_template_append' );
			//		self::v( $template );  
			// 		self::v( $this->_objectTemplateValues );  
					$template = Ayoola_Abstract_Playable::replacePlaceholders( $template, $this->_objectTemplateValues + array( 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', ) );
				//	self::v( $this->getParameter() );  
					$html = $template; 
				}   
			//		var_export( $this->getParameter( 'wrapper_name' ) );
				if( $this->getParameter( 'wrapper_name' ) && $html )       
				{
					$html =  '<div class="'. $this->getParameter( 'wrapper_inner_class' ) .'">' . $html . '</div>';
					$html =  Ayoola_Object_Wrapper_Abstract::wrapContent( $html, $this->getParameter( 'wrapper_name' ) );
				}
				if( ( $this->getParameter( 'object_style' ) || $this->getParameter( 'object_class' ) ) && $html )       
				{
			//		var_export( $this->getParameter( 'object_style' ) );
			//		var_export( $html );
					$html = '<div class="'. $this->getParameter( 'object_class' ) .'" style="'. $this->getParameter( 'object_style' ) . '">' . $html . '</div>';
					//	self::v( $template );   
				}
			//		var_export( $this->getParameter( 'content_to_clear' ) );
				//	Define content to clear from the screen
					$contentToClear = $this->getParameter( 'content_to_clear' ) . @$this->_parameter['content_to_clear_internal'];
				if( $contentToClear )       
				{
					$search = array_map( 'trim', explode( "\n", $contentToClear ) );
			//		var_export( $search );
					$html = str_replace( $search, '', $html );
					//	self::v( $template );   
				}
				
				return $html;
			break;  
			default:
				
			break;
		}
	}
	// END OF CLASS
}
