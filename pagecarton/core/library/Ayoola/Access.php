<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Access
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Access.php 10.12.2011 09.48pm ayoola $
 */

/**
 * @see Ayoola_Access_Abstract
 */
 
require_once 'Ayoola/Access/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Access
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Access extends Ayoola_Access_Abstract
{
	/**
	 * Singleton Instance
	 * 
	 * @var Ayoola_Access
	 */
	protected static $_instance;
	
	/**
	 * The Device Adapter for Authentication
	 * 
	 * @var Ayoola_Access_Adapter
	 */
	protected $_adapter;
	
	/**
	 * Persistent Storage
	 * 
	 * @var Ayoola_Access_Storage
	 */
	protected $_storage;
	
	/**
	 * Name of Default Storage
	 * 
	 * @var string
	 */
	protected $_defaultStorage = 'Session';
	
	/**
	 * Name of Default Adapter
	 * 
	 * @var string
	 */
	protected $_defaultAdapter = 'DbaseTable';
	
	/**
	 * The ID of The Current User
	 * 
	 * @var mixed
	 */
	protected $_id = null;
	
	/**
	 * The Info of The Last Authenticated User
	 * 
	 * @var array
	 */
	public static $userInfo;
	
	/**
	 * The Credential Used in Authentication e.g. array( $credential => $value )
	 * 
	 * @var array
	 */
	protected $_authCredentials = null;
	
	/**
	 * Constructor
	 *
	 * @param 
	 * 
	 */
	public function __construct()
	{
		self::$userInfo = $this->getStorage()->retrieve();
	}
	
	/**
	 * Authenticate the User Against Records in the Storage
	 *
	 * @param array
	 * @return boolean
	 */
	public function authenticate( $credentials = null )
	{
	//	var_export( $credentials );
		$credentials = array_merge( $this->hashCredentials( $credentials ), $this->getCredentials() );
	//	var_export( $credentials );
	//	var_export( $this->getAdapter()->authenticate( $credentials ) );
		if( ! $this->getAdapter()->authenticate( $credentials ) ){ return false; }
		$result = $this->getAdapter()->getResultRow();
	//	echo session_id(); exit();
		session_regenerate_id();
	//	echo session_id(); exit();
		$this->getStorage()->store( $result );
		return true;
	} 
	
	/**
	 * Checks if the present user has enough priviledge of access
	 *
	 * @param mixed Access Level Being Checked
	 * @return boolean
	 */
	public function checkPriviledges( $authLevel = 0 )
	{
		$userInfo = $this->getUserInfo();
		$priviledges = range( 0, @$userInfo['access_level'] ); 
		//var_export( $userInfo );
		return in_array( $authLevel, $priviledges );
	} 
	
	/**
	 * Clears the ID and User Info from Storage
	 *
	 * @param void
	 * @return void
	 */
	public function logout()
	{
		setcookie( 'accessLogin', '', time() - 1728000, '/' );
		setcookie( 'accessLogin', '', 0 );
		setcookie( 'accessLogin', false );
		$this->getStorage()->clear();
		@session_regenerate_id( true );
	} 	
	
	/**
	 * Returns true of an ID is present in the Storage
	 *
	 * @param void
	 * @return boolean
	 */
	public function isLoggedIn()
	{
		return $this->getStorage()->isLoaded();
	} 
	
	/**
	 * Restrict the page access according to access level
	 *
	 */
	public static function restrict( $pageAccessLevel = null )
	{
		$pageInfo = Ayoola_Page::getCurrentPageInfo();
		if( is_null( $pageAccessLevel ) )
		{
			require_once 'Ayoola/Page.php';
			switch( @$pageInfo['auth_level'] )
			{
				case false:
				case null:
				case '':
				//	self::v( $pageInfo['auth_level'] );
					$pageInfo['auth_level'] = isset( $pageInfo['auth_level'] ) ? $pageInfo['auth_level'] : array( 99 );
				break;
			
			}
			$pageInfo['auth_level'] = is_scalar( $pageInfo['auth_level'] ) ? array( intval( $pageInfo['auth_level'] ) ) : $pageInfo['auth_level'];
	
			$pageInfo['auth_level'][] = 98;
			$authLevel = $pageInfo['auth_level']; 
			$pageAccessLevel = $authLevel; 
		//	var_export( $pageInfo );
		//	var_export( $authLevel );
 		// 	exit();
		}
		$pageAccessLevel = is_array( $pageAccessLevel ) ? $pageAccessLevel : array( $pageAccessLevel );
 //   var_export( $pageInfo );
//	exit();
		$objectPlay = false;
		switch( $pageInfo['url'] )
		{ 
			case '/tools/classplayer':
				$objectPlay = true;
			case 'object':
				$className = @$_GET['object_name'] ? : $_GET['name']; 
				if( Ayoola_Loader::loadClass( $className ) && method_exists( $className, 'getAccessLevel' ) )
				{
				//    exit( $className );
				 //   var_export( $className::getAccessLevel() );
				//    var_export( Ayoola_Abstract_Playable::hasPriviledge( $className::getAccessLevel() ) );
				//    exit( $className );
					if( Ayoola_Abstract_Playable::hasPriviledge( $className::getAccessLevel() ) )
					{
						return true;  
					}
		//		    var_export( $className::getAccessLevel() );
		//		    exit( $className );
				} 
			break;
		} 
		if( 
			Ayoola_Abstract_Playable::hasPriviledge( $pageAccessLevel ) 
			|| @$pageInfo['url'] === '/accounts/signin'
			|| @$pageInfo['url'] === '/account/signin'
		)
		{ 
			return true; 
		} 
		$table = Ayoola_Object_Table_ViewableObject::getInstance();

		if( $methods = $table->select( null, array( 'module' => 'Auth' ) ) )
		{
		 
			foreach( $methods as $each )
			{
				$each = $each['class_name'];
				
				if( $each && Ayoola_Loader::loadClass( $each ) )
				{
		   //          var_export(  $each::viewInLine() );
			   //     echo $each::viewInLine();
					$each::viewInLine();
					$access = self::getInstance();
					if( $access->isLoggedIn() )
					{
						if( Ayoola_Abstract_Playable::hasPriviledge( $pageAccessLevel ) )
						{ 
							return true; 
						} 
					}
				}
			}
		}
		//	IF WE ARE HERE, WE ARE NOT AUTHORIZED   
		$prefix =  Ayoola_Application::getUrlPrefix();
		$multisiteTable = new PageCarton_MultiSite_Table();
		if( $response = $multisiteTable->selectOne( null, array( 'directory' => Ayoola_Application::getPathPrefix() ) ) )
		{
			$prefix = $response['parent_dir'] . Ayoola_Application::getUrlPrefixController();
		}
		$urlToGo = '' . $prefix . '/account/signin/';
		if( $objectPlay )
		{
			$urlToGo = '' . $prefix . '/tools/classplayer/get/name/Ayoola_Access_Login/';
		}
		$urlToGo = Ayoola_Page::setPreviousUrl( $urlToGo ); 
		$access = self::getInstance();
   //     var_export( $pageInfo );
    //    var_export( $urlToGo );
	//	var_export( $objectPlay );
   //    var_export( Ayoola_Application::isClassPlayer()  );
	//    exit();
	//	exit();
		if( ! $access->isLoggedIn() )   
		{ 
			//	$access->logout();
			
			//	must log out first to avoid redirct at the login page.
			
			$encodeLoginMessage = new Ayoola_Access_Login();
			$encodeLoginMessage->getObjectStorage( 'pc_coded_login_message' )->store( 'Please login to continue...' );
			
//            var_export( $urlToGo );
//            exit();

			$jsCode = 'ayoola ? ( ayoola.div.getParent( window, 5 ).location = "' . $urlToGo . '?pc_coded_login_message=1&previous_url=" + encodeURIComponent( ayoola.div.getParent( window, 5 ).location ) ) : ( window.location = "' . $urlToGo . '?pc_coded_login_message=1&previous_url=" + encodeURIComponent( window.location ) );';
			Application_Javascript::addCode( $jsCode );

	//		if(  ! Ayoola_Application::isClassPlayer() )
		//	if( ! $objectPlay )
			{
				header( 'Location: ' . $urlToGo );	
				exit();
			}
		}
		elseif( ! Ayoola_Application::isClassPlayer() )  
		{			
			$access = self::getInstance();
			$access->logout();
		//	if( ! $objectPlay )
			{
				header( 'Location: ' . $urlToGo );	
				exit();
			}
		}
		else
		{
			
		}
		
	//	echo 'You need to be signed into your account to continue. <a target="_parent" onClick="' . $jsCode . '" href="' . $urlToGo . '">Click here to sign in...</a>';
		
	//	echo Application_Javascript::getAll();
		
		return false;
		
	//	var_export( $pageAccessLevel );
	} 
	
	/**
	 * Retrieve the user info from the storage
	 *
	 * @param void
	 * @return array
	 */
	public function getUserInfo()
	{
		if( $this->isLoggedIn() )
		{
			return $this->getStorage()->retrieve();
		}
		
	} 
		
	/**
	 * Sets a value for the storage property
	 *
	 * @param Ayoola_Access_Storage
	 * @see Ayoola_Access_Storage
	 * @return void
	 */
	public function setStorage( Ayoola_Access_Storage $storage )
	{
		$this->_storage = $storage;
	} 
	
	/**
	 * Return the persistent storage object
	 *
	 * @param void
	 * @return Ayoola_Access_Storage
	 */
	public function getStorage()
	{
		if( null === $this->_storage )
		{
			//	Use Default Device
			require_once 'Ayoola/Access/Storage.php';
			$this->setStorage( new Ayoola_Access_Storage( $this->getDefaultStorage() ) );
		}
		return $this->_storage;
	} 
		
	/**
	 * Sets a value for the _adapter property
	 *
	 * @param Ayoola_Access_Adapter
	 * @see Ayoola_Access_Adapter
	 */
	public function setAdapter( Ayoola_Access_Adapter $adapter )
	{
		$this->_adapter = $adapter;
	} 
	
	/**
	 * Return the persistent storage object
	 *
	 * @param void
	 * @return Ayoola_Access_Adapter
	 */
	public function getAdapter()
	{
		if( null === $this->_adapter )
		{
			//	Use Default Device
			require_once 'Ayoola/Access/Adapter.php';
			$this->setAdapter( new Ayoola_Access_Adapter( $this->getDefaultAdapter() ) );
		}
		return $this->_adapter;
	} 
	
	/**
	 * This method sets the _id property to a value
	 *
	 * @param mixed ID for the current user
	 */
	public function setId( $id )
	{
		$this->_id = $id;
	} 	
	
	/**
	 * Returns the _id Property
	 *
	 * @return mixed ID for the current user
	 */
	public function getId()
	{
		return $this->_id;
	} 	
	
	/**
	 * This method sets the _defaultAdapter property to a value
	 *
	 * @param string Adapter Name
	 */
	public function setDefaultAdapter( $name )
	{
		$this->_defaultAdapter = $name;
	} 	
	
	/**
	 * Returns the _defaultAdapter Property
	 *
	 * @param void
	 * @return string Adapter Name
	 */
	public function getDefaultAdapter()
	{
		return (string) $this->_defaultAdapter;     
	} 	
	
	/**
	 * This method sets the _defaultStorage property to a value
	 *
	 * @param string Storage Name
	 */
	public function setDefaultStorage( $name )
	{
		$this->_defaultStorage = $name;
	} 	
	
	/**
	 * Returns the _defaultStorage Property
	 *
	 * @param void
	 * @return string Storage Name
	 */
	public function getDefaultStorage()
	{
		return (string) $this->_defaultStorage;
	} 	
	
	/**
	 * This method sets the _authCredentials property to a value
	 *
	 * @param array Credentials for Authentication
	 */
	public function setAuthCredentials(Array $values )
	{
		$this->_authCredentials = $values;
	} 	
	
	/**
	 * Returns the _authCredentials Property
	 *
	 * @param void
	 * @return array Credentials for Authentication
	 */
	public function getAuthCredentials()
	{
		return (array) $this->_authCredentials;
	} 	
	
	/**
	 * Returns the _instance Property
	 *
	 * @return Ayoola_Access Singleton Instance
	 */
	public static function getInstance()
	{
		if( null === self::$_instance ){ self::$_instance = new self(); }
		return self::$_instance;
	} 	
	
	// END OF CLASS
}
