<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Api
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Api.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Api
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Api extends Ayoola_Abstract_Table
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * Put the view content in container
     *
     * @var boolean
     */
	protected $_useViewContentContainer = false;
	
    /**
     * The content recieved by API
     *
     * @var string
     */
	protected static $_input;
	
    /**
     * Where to store the url settings
     *
     * @var array
     */
	protected static $_settings = array();
	
    /**
     * Deprecating this class. Only classes that is still usable should switch to on.
     *
     * @var bool
     */
	protected static $_inUse = false;  
	
    /**
     * URL to send an api request to. 
     *
     * @var string
     */
	protected static $_defaultUrlHistory = array(
		'http://account.ayoo.la/tools/classplayer/get/object_name/Ayoola_Api/',
		'https://accounty.comeriver.com/tools/classplayer/get/object_name/Ayoola_Api/',
		'https://account.comeriver.com/tools/classplayer/get/object_name/Ayoola_Api/',
	);
//	protected static $_url = 'http://account.ayoo.la/tools/classplayer/get/object_name/Ayoola_Api/';
	protected static $_url = 'https://accounty.comeriver.com/tools/classplayer/get/object_name/Ayoola_Api/';
	
    /**
     * Plays the class
     * 
     */
	public function init()
    {
		if( static::getInput() && ! count( $_POST ) )
		{
 	//		var_export( $HTTP_RAW_POST_DATA );
			echo sha1( '0' );
			echo static::recieve();
		//	var_export( unserialize( static::recieve() ) );
			echo sha1( '0' );
			return true;
		}
		else
		{
			//	Debug
/* 			$data['data'] = array ( 'x' => '', 'country_id' => '1770', 'dial_code' => '234 (NIGERIA)', 'phonenumber' => '8054449535', 'phonenumber_id' => '20', '' => '', 'method' => 'insert', 'table' => 'Application_User_UserPhoneNumber', );
			echo self::check( $data );
			echo self::call( $data );
 */			$this->setViewContent( 'The API connection on this application is powered by Ayoola Content Management Framework (Ayoola CMF). To learn about how to connect securely to this application, visit <a href="http://ayoo.la/cmf/">Ayoola CMF website.</a>' );
		}
		
    } 
	
	
    /**
     * Returns a raw php input stream
     * 
     */
	protected function getInput()
    {
		if( is_null( static::$_input ) )
		{
			static::$_input = file_get_contents( 'php://input' );
		}
		return static::$_input;
    } 
	
    /**
     * Lookup app in whitelist
     * 
     */
	protected static function checkInWhiteList( array $data )
    {
		//	Only allow my buddies in the whitelist
		$table = new Ayoola_Api_Whitelist();
		$where = array( 'api_label' => $data['options']['authentication_info']['domain_name'] );
	//	var_export( $where );
		if( ! $appInfo = $table->selectOne( null, $where ) )
		{
			throw new Ayoola_Api_Exception( 'APP NOT FOUND IN THE WHITELIST.' );
		}
	//	var_export( $appInfo );
	//	$keys = self::getKeyArray( $appInfo );
		return self::authenticate( $data, array( 'public_key' => $appInfo['public_key'], 'hash' => $appInfo['hash'], ) );
    } 
	
    /**
     * authenticate app
     * 
     */
	protected static function authenticate( array $data, array $correctKeys )
    {
		if
		( 
			$data['options']['authentication_info']['public_key'] != $correctKeys['public_key'] 
			|| $data['options']['authentication_info']['hash'] != $correctKeys['hash']
		)
		{
			throw new Ayoola_Api_Exception( 'KEY EXCHANGE FAILURE' );
		}
		return true;
    } 
	
    /**
     */
	public static function generatekeys()
    {
		$keys = array( 'public_key', 'private_key', 'application_salt' );
		foreach( $keys as $key => $value )
		{
			$keys[$value] = sha1( uniqid( rand(), true ) );
			unset( $keys[$key] );
		}
		return $keys;
	}
	
    /**
     */
	public static function getKeyHash( $keys )
    {
		return sha1( $keys['application_salt'] . $keys['private_key'] . $keys['public_key'] );
	}
	
    /**
     *
     */
	public static function getKeyArray( array $keys )
    {
		return @array( 'private_key' => $keys['private_key'], 'public_key' => $keys['public_key'], 'application_salt' => $keys['application_salt'], );
	}
	
    /**
     *
     */
	public static function getSettings( $url )
    {
	//	var_export( $url );
		if( $url == self::$_url )
		{
			$url = self::$_defaultUrlHistory; 
		}

		//	needed to remove this because of multiple api request within a single request.
	//	if( empty( self::$_settings[$url] ) )
		{
			$table = new Ayoola_Api_Api();
			$urlInfo = $table->selectOne( null, array( 'api_url' => $url ) );
			self::$_url = $urlInfo['api_url'];

			self::$_settings[self::$_url] = $urlInfo;
		//	var_export( $urlInfo );
		}
		return self::$_settings[self::$_url];
	}
	
    /**
     *
     */
	public static function saveKeys( array $data )
    {
		$table = new Ayoola_Api_Api();
	//	$adapter = $table->getDatabase()->getAdapter();
	//	$adapter->cache = false;
		$where = array( 'api_url' => $data['api_url'] );
		if( $settings = $table->selectOne( null, $where ) )
		{
			$settings = array_merge( $settings, $data );
			$update = $table->update( $settings, $where );
		}
		else
		{
			$table->insert( $data );
		}
	//	exit();
	}
	
    /**
     * send an api request
     * 
     * param mixed Data to send
     */
	public static function send( $data ) 
    {
		//	This method is no longer in use. Makes application run very slow.
		if( static::$_inUse !== true )
		{
			return false;   
		}
		
		//	check if there is a catched result.
		$class = new static();
 		$storage = $class->getObjectStorage( array( 'id' => 'a' . md5( serialize( $data ) ) . '1', 'device' => 'File', 'time_out' => 86400, ) );
	//	if( $info = $storage->retrieve() )
		{
		//	var_export( get_class( $class ) );
	//		return $info;
		}
	//	var_export( $info );
 	
		//	var_export( $data );
		if( ! isset( $data['data'] ) )
		{
			$data = array( 'data' => $data );
		}
		
/* 		//	Debug in localhost
		if( ! strpos( $_SERVER['HTTP_HOST'], '.' ) ) 
		{
			static::$_url = 'http://account/tools/classplayer/get/object_name/Ayoola_Api/';
	//		break; 
		}
 */		//	Debug in localhost
		if( $_SERVER['HTTP_HOST'] === 'localhost' ) 
		{
			static::$_url = 'http://account/tools/classplayer/get/object_name/Ayoola_Api/';
	//		break; 
		}
		
		@$data['options'] = $data['options'] ? : array(); 
		@$data['options']['url'] = $data['options']['url'] ? : static::$_url;
		
		//	pass inn the public and private keys
		$keys = self::getSettings( $data['options']['url'] );
	//		var_export( $keys );
		@$data['options']['authentication_info']['application_id'] = $keys['application_id'];
		$keys = self::getKeyArray( $keys );
		$data['options']['authentication_info']['public_key'] = $keys['public_key'];
		$data['options']['authentication_info']['hash'] = self::getKeyHash( $keys );
		$data['options']['authentication_info']['server_ip'] = $_SERVER['REMOTE_ADDR'];
		$data['options']['authentication_info']['domain_name'] = Ayoola_Page::getDefaultDomain();
		static::check( $data );
	//	var_export( $data );
		$settings = array();
	//	var_export( $settings['post_fields'] );
		$settings['post_fields'] = serialize( $data );
		$settings['time_out'] = 50; 
		$settings['connect_time_out'] = 50;
		$headers = array();
		$headers[] = "Content-Type: application/json; charset=UTF-8";
		$headers[] = "Accept: application/json; charset=UTF-8";
		$settings['http_header'] = $headers;
	//	var_export( file_get_contents( $data['options']['url'] ) );

		$response = Ayoola_Abstract_Viewable::fetchLink( $data['options']['url'], $settings );
//		var_export( $settings );
	//	var_export( $data );
//		var_export( $data['options']['url'] );
	//	var_export( $response );
	//	echo $response;
	//	exit( var_export( $settings ) );
		$response = explode( sha1( '0' ), $response );
		@$response = $response[1];
	//	return $response;
	//	var_export( $response );
				//	var_export( $data['options']['callbacks'] );
		if( $data = @unserialize( trim( $response ) ) )
		{
			if( ! empty( $data['options']['callbacks'] ) )
			{
				foreach( $data['options']['callbacks'] as $callback => $argument )
				{
				//	var_export( $callback );
					call_user_func( $callback, $argument );
				}
			}
			$storage->store( $data );
		//	var_export( $response );
			return $data;
		}
	//	echo $response;
	//	var_export( $response );
		return $response;
	//	return static::recieve( $data );
    } 
	
    /**
     * receive an api request
     * 
     */
	public static function recieve( $data = null )
    {
	//	var_export( static::getInput() );
	//	exit();
		
		if( ! $data = unserialize( static::getInput() ) )
		{
			throw new Ayoola_Api_Exception( 'INVALID DATA SENT.' );
		}
		$data = $data ? : array();
		
		//	We use this to return some information to the calling server
		$data['options']['return_info'] = array();
	//	$options = array_shift( $data );
	//	$data = array_shift( $data );
		static::check( $data );
		
		//	Check for recieving compliance.
		$apiOptions = Application_Settings_Abstract::getSettings( 'Security', 'options' );
	//	var_export( $apiOptions );
		if( ! is_array( $apiOptions ) || ! in_array( 'allow', $apiOptions ) )
		{
			throw new Ayoola_Api_Exception( 'RECIEVING APPLICATION DOES NOT ALLOW API CONNECTIONS' );
		}
		do
		{
			$table = 'Ayoola_Application_Application';
			if( Ayoola_Loader::loadClass( $table ) )
			{ 
				$table = new $table();
			//	var_export();
				$where = array( 'application_id' => $data['options']['authentication_info']['application_id'] );
			//	var_export( $where );
				try
				{
					$appInfo = $table->selectOne( null, null, $where );
				}
				catch( Exception $e )
				{
			//		var_export( $e->getMessage() );
				}
				if( ! $appInfo )
				{
			//	var_export( $where );
					if( ! is_array( $apiOptions ) || in_array( 'pre-register', $apiOptions ) )
					{
						//	If pre-registeration is required, it could have been made in the whitelist
						try
						{
							if( self::checkInWhiteList( $data ) )
							{
								break;
							}
						}
						catch( Exception $e ){ null; }
			//		var_export( $insertValues );
						throw new Ayoola_Api_Exception( 'PRE-REGISTERATION REQUIRED FOR THIS API' );
					}
					//	REGISTER ON DEMAND
					$keys = self::generateKeys();
					@$keys['application_salt'] = $data['data']['application_salt'] ? : $keys['application_salt'];
					
					// Save server info
					$table = new Ayoola_Application_Server;
			//		$insertValues = array( 'server_ip' => $data['options']['authentication_info']['server_ip'] );
					$insertValues = array( 'server_ip' => $_SERVER['REMOTE_ADDR'] );
			//		var_export( $insertValues );
			//		var_export( $_SERVER );
					$serverId = self::getPrimaryId( $table, $insertValues );
					
					// Save app info
					$table = new Ayoola_Application_Application;
					$insertValues = $keys + array( 'server_id' => $serverId );
					$applicationId = self::getPrimaryId( $table, $insertValues );
					
					// Save domain info
					$table = new Ayoola_Application_Domain;
					$insertValues = array( 'domain_name' => $data['options']['authentication_info']['domain_name'] );
					$domainId = self::getPrimaryId( $table, $insertValues );
					
					// Save domain info
					$table = new Ayoola_Application_ApplicationDomain;
					$insertValues = array( 'domain_id' => $domainId, 'application_id' => $applicationId );
					$applicationDomain = self::getPrimaryId( $table, $insertValues );
					
					$appInfo = array( 'application_id' => $applicationId ) + $keys;
					
					//	update the key data
				//	$data['options']['authentication_info'] = array();
					$data['options']['authentication_info']['application_id'] = $applicationId;
					$data['options']['authentication_info'] = array_merge( $data['options']['authentication_info'], self::getKeyArray( $appInfo ) );
					$data['options']['authentication_info']['hash'] = self::getKeyHash( $appInfo );
					
					//	We want the calling server to be able to save the following keys for future requests.
					$appInfo['api_url'] = $data['options']['url'];
					$data['options']['return_info']['callbacks']['self::saveKeys'] = $appInfo;
				}
			//	var_export( $appInfo );
			//	self::authenticate( $data, self::getKeyArray( $appInfo ) );
				$correctKeys = array( 'public_key' => $appInfo['public_key'], 'hash' => self::getKeyHash( $appInfo ) );
				self::authenticate( $data, $correctKeys );
				break;
			}
			
			//	Only allow my buddies in the whitelist
			self::checkInWhiteList( $data );
			break;
		}
		while( false );
		$class = $data['options']['request_type'];
		
	//	var_export( $data );
//		exit;
	//	var_export( $class::call( $data ) );
		$data = $class::call( $data );
		$response = array( 'data' => @$data['options']['server_response'], 'options' => @$data['options']['return_info'] );
		$response = serialize( $response );
		return $response;
    } 
	
    /**
     * Checks request
     * 
     */
	public static function check( & $data )
    {
		do
		{
			if( empty( $data['options']['request_type'] ) )
			{
				$data['options']['request_type'] = get_class( new static );
			//	throw new Ayoola_Api_Exception( 'REQUEST TYPE IS REQUIRED IN API REQUEST' );
			}
			if( ! Ayoola_Loader::loadClass( $data['options']['request_type'] ) )
			{ 
			//	var_export( new static );
				throw new Ayoola_Api_Exception( 'INVALID API REQUEST TYPE: ' . $data['options']['request_type'] );
			}
		}
		while( false );
    } 
	// END OF CLASS   
}
