<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Access_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 3.6.2012 12.55 ayoola $
 */

/**
 * @see Ayoola_Abstract_Table
 */
 
require_once 'Ayoola/Abstract/Table.php'; 


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Access_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Access_Abstract extends Ayoola_Abstract_Table
{
	
    /**
     * values for $_credentialColumn
     * e.g. array ( $field => $value )
     * e.g. array ( $field => $value )
     * @var array
     */
//	protected $_credentials = array( 'enabled' => 1, 'approved' => 1, );
	protected $_credentials = array();
	
    /**
     *	Mechanisms allowed for authentication
     *
     * @var array
     */
	protected static $_allowedAuthMechanism = array
							( 
								'UsernamePassword' => array( 'username' => null, 'password' => 'sha512' ), 
								'EmailPassword' => array( 'email' => null, 'password' => 'sha512' ), 
								'EmailSocialMediaUserForeignId' => array( 'email' => null, 'socialmediauser_foreign_id' => null ), 
							);
	
    /**
     *	
     *
     * @var array
     */
	protected static $_allowedAccessInformation = array
							( 
								'display_picture',
								'wallet_balance',
								'display_name',
								'profile_description',
								'profile_type',
							);
	
    /**
     *	Mechanism allowed for authentication
     *
     * @var string
     */
	protected $_authMechanism = 'UsernamePassword';
	
    /**
     * Credentials with the hashes
     *
     * e.g. array ( $column => $hash )
     * $hash is hashing required for the credential 
     * e.g. SHA1, MD5
     * @var array
     */
 	protected $_credentialColumns;

    /**
     * Prepare credentials used in authentication 
     * Performs Hashing Basically
     *  
     * @param array The Credentials for Authentication
     * @return array Hashed Equivalent As Specified in CredentialColumn
     */
    public function hashCredentials( Array $credentials )
    {
		if( @$credentials['auth_mechanism'] )
		{
			$this->setAuthMechanism( $credentials['auth_mechanism'] );
		}
	//	var_export( $credentials );
	//	var_export( $this->getCredentialColumns() );
		require_once 'Ayoola/Filter/Hash.php'; // For credentials that requires hashing
		$hashedCredentials = array();
		foreach( $this->getCredentialColumns() as $column => $hash )
		{
			//	Check for the required column names if not available, preset them to null
			if( ! isset( $credentials[$column] ) ){ continue; }
			$hashedCredentials[$column] = $credentials[$column];
			if( strlen( $hash ) > 2 )
			{ // Hash only the columns that specifies that
				$filter = new Ayoola_Filter_Hash( $hash );
				$hashedCredentials[$column] = $filter->filter( $credentials[$column] );
			}
		}
//		var_export( $hashedCredentials );
		return $hashedCredentials;
		return array_merge( $credentials, $hashedCredentials );
    } 
	
    /**
     * This method retrieves the credentials used in authentication
     *
     * @param void
     * @return array
     */
    public function getCredentials()
    {
        return $this->_credentials;
    } 
	
    /**
     * This method sets a value for the credentials used in authentication 
     * e.g. Array( $column => $value )
     * @param array
     * @return void
     */
    public function setCredentials( $credentials )
    {		
		$credentials = _Array( $credentials );
        $this->_credentials = array_merge( $this->getCredentials(), $credentials );		
    } 
	
    /**
     * This method retrieves the columns for credentials
     * 
     * @param void
     * @return array
     */
    public function getAuthMechanism()
    {
		if( empty( self::$_allowedAuthMechanism[$this->_authMechanism] ) || ! is_array( self::$_allowedAuthMechanism[$this->_authMechanism] ) )
		{
			throw new Ayoola_Access_Exception( 'UNAUTHORIZED AUTHENTICATION MECHANISM: ' . $this->_authMechanism );
		}
		return $this->_authMechanism;		
    } 
	
    /**
     * This method sets a value for the _authMechanism
     * e.g. Array( $column => $value )
     * @param array
     * @return void
     */
    public function setAuthMechanism( $mechanism )
    {		
		if( empty( self::$_allowedAuthMechanism[$mechanism] ) || ! is_array( self::$_allowedAuthMechanism[$mechanism] ) )
		{
			throw new Ayoola_Access_Exception( 'UNAUTHORIZED AUTHENTICATION MECHANISM: ' . $mechanism );
		}
        $this->_authMechanism = $mechanism;		
    } 
	
    /**
     * This method retrieves the _credentialColumns
     * 
     * @param void
     * @return array
     */
    public function getCredentialColumns()
    {
		if( is_null( $this->_credentialColumns ) )
		{
			$this->_credentialColumns = self::$_allowedAuthMechanism[$this->getAuthMechanism()];
		}
        return $this->_credentialColumns; 
    } 
	
    /**
     * Sets the column for credentials
     * The reason I am making this an array is so that I can have a column match the hashing needed.
     * e.g. Array( $column => $hashNeeded, etc )
     * @param array | string
     * @return 
     */
/*     public function setCredentialColumns( $columns )
    {
		$columns = _Array( $columns ); // Converts my style of laziness to array
        $this->_credentialColumns = (array) $column;
    } 
 */	
    /**
     * Calculate the value for the persistent cookie
	 *
     * @param string password
     * @return string hashed password
     */
    public static function hashPassWord( $password, $time )
    {
	//		var_export( $userInfo );
		$serverSalt = date( "M Y" ) . '32a]\.,.,-=12' . date( "M Y" ) . '&qwd1235^@-=@11' . date( "M Y" ) . $time; // Creates a new server salt every month
		
		//	Use the user password, server salt and browser info to generate a cookiepassword
		$cookiePassword = sha1( $password . $serverSalt . $_SERVER['HTTP_USER_AGENT'] . DOMAIN );
	//	var_export( $userInfo['user_id'] . ':' . $cookiePassword . ':' . time() );
		return $cookiePassword; 
		
    }  
	
    /**
	 *
     * @param string Username
     * @return array 
     */
    public static function getAccessInformation( $username = null, array $options = null )
    {
		try
		{ 
			//	allow injecting of userinformation
			$userInfo = array();
			if( is_array( $username ) )
			{
				$userInfo = $username;
				$username = $username['username'];
			}
			if( empty( $username ) )
			{
				$username = Ayoola_Application::getUserInfo( 'username' );
			}
/* 			switch( gettype( $username ) )
			{
				case 'array':
				default:
					$username = Ayoola_Application::getUserInfo( 'username' );
				break;
				
			}	
 */			$where = array( 'username' => strtolower( $username ) );
			$userInfo = $userInfo ? : $where;
 			if( ! @$options['skip_user_check'] )
			{
				//	Check the user
			//	self::v( $username );
				$class = new Application_User_Editor( $where );
			//	self::v( $username );
				$class->setIdentifier( $where );
				
				//	dont know why it is catching this data
			//	$class->setIdentifierData();
				$userInfo = $class->getIdentifierData();
				
				//	hide super users
		//		if( ! $userInfo || $userInfo['access_level'] == 99 )
				if( ! $userInfo  || ! $username )
				{
					return false;
				//	throw new Ayoola_Access_Exception( 'INVALID USER: ' . $username ); 
				}
			}
		//	self::v( $where );
		//	self::v( $userInfo );
			$table = new Ayoola_Access_AccessInformation();
			if( ! $previousInfo = $table->selectOne( null, $where ) )
			{
			
				//	Populate the Ayoola_Access_AccessInformation
			//	$table->insert( $userInfo );
			}
			$authOptions = array();		
	//		var_export( $userInfo['access_level'] );
			if( @$userInfo['access_level'] )
			{
				//	Get information about the user group
				$authOptions = new Ayoola_Access_AuthLevel();
				$authOptions = $authOptions->selectOne( null, array( 'auth_level' => $userInfo['access_level'] ) );
		//		var_export( $authOptions ); 
			}
		//	self::v( $previousInfo );
		//	$userInfo['profile_url'] = 
			
		
			$info = array_merge( $authOptions ? : array(),  $userInfo ? : array(), @$previousInfo['access_information'] ? : array() );
			@$info['display_picture'] = $info['display_picture'] ? : 'http://placehold.it/256x256&text=No Picture';
			if( $info['display_picture'] = Ayoola_Doc::uriToDedicatedUrl( $info['display_picture'] ) )  
			{

			}
			
			@$info['display_name'] = trim( $info['display_name'] ? : ( $info['firstname'] . ' ' . $info['lastname'] ) );
			@$info['profile_description'] = $info['profile_description'] ? : ( $info['display_name'] . ' (' . $info['auth_name'] . ') has an account on ' . ( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Page::getDefaultDomain() ) . '. ' . $info['auth_description'] );
			if( @$info['profile_url'] )  
			{
				$filename = Application_Profile_Abstract::getProfilePath( $info['profile_url'] );
				$data = @include $filename;
				if( is_array( $data ) )
				{
				//	var_export( $data );
					if( $data['display_picture_base64'] )
					{
						$data['display_picture'] = $data['display_picture_base64'];
					}
					$info = array_merge( $info, $data );
				}
			}
		//	self::v( $info );
/* 			if( @$options['set_canonical_url'] )
			{
				$pageInfo = array(
					'description' => @$info['profile_description'],
				//	'keywords' => @$info['article_tags'],
					'title' => trim( $info['display_name'] . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' ), '- ' )
				);
		//		Ayoola_Page::setCurrentPageInfo( $pageInfo );
			}
 */			return $info;
		}
		catch( Application_Access_Exception $e ){ return false; }
	}
	
    /**
	 *
     * @param array Access Information
     * @return boolean 
     */
    public static function setAccessInformation( $information )
    {
		try
		{ 
			if( empty( $information['username'] ) )
			{
				$information['username'] = Ayoola_Application::getUserInfo( 'username' );
			}	
			
			
			//	Check the user
			$class = new Application_User_Editor( array( 'no_init' => true ) );
			$where = array( 'username' => strtolower( $information['username'] ) );
			$class->setIdentifier( $where );
			if( ! $userInfo = $class->getIdentifierData() )
			{
				throw new Ayoola_Access_Exception( 'INVALID USER: ' . $information['username'] ); 
			}
			//	var_export( $userInfo ); 
			
			$table = new Ayoola_Access_AccessInformation();
			if( ! $previousInfo = $table->selectOne( null, $where ) )
			{
			
				//	Populate the Ayoola_Access_AccessInformation
				$table->insert( $where );
			}
		//	var_export(  );
		//	if( ! $values = $this->getForm()->getValues() )
		//	{ return false; }
		
			$information = array_merge_recursive( $previousInfo ? : array(), $information );
			unset( $information['accessinformation_id'] );
			unset( $information['username'] );
		//	var_export( $information ); 
		
			//	only save relevant data
			$userOptions = Application_Settings_Abstract::getSettings( 'UserAccount', 'allowed_access_information' ) ? : array();
			$allowedInformation = array_merge( self::$_allowedAccessInformation, $userOptions ); 
		//	self::v( $allowedInformation );
		//	self::v( $userOptions );
			$information = array_intersect_key( $information, array_combine( $allowedInformation, $allowedInformation ) );
			if( $table->update( array( 'access_information' => $information ), $where ) )
			{ 
			//	self::v( $where );
			//	self::v( $information );
				return true;
			}
			return false;
		}
		catch( Exception $e )
		{ 
		//		var_export( $information );
			//	var_export( $e->getMessage() );
		//	var_export( $e );
			return false; 
		}
    }  
	
    /**
     * Calculate the value for the persistent cookie
	 *
     * @param string hashed password
     * @return string The value for persistent cookie
     */
    public static function getPersistentCookieValue() 
    {
		$auth = new Ayoola_Access();
		$userInfo = $auth->getUserInfo();
		
	//	var_export( $userInfo['password'] );

		//	Use the user password, server salt and browser info to generate a cookiepassword
		$time = time();
		$cookiePassword = self::hashPassWord( $userInfo['password'], $time );
	//	var_export( $userInfo['user_id'] . ':' . $cookiePassword . ':' . time() );
		return base64_encode( $userInfo['user_id'] . ':' . $cookiePassword . ':' . $time ); 
		
    } 
	// END OF CLASS
}
