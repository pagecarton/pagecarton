<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
     * Calculate the value for the persistent cookie
	 *
     * @param string password
     * @return string hashed password
     */
    public static function hashPassWord( $password, $time )
    {
		$serverSalt = date( "M Y" ) . '32a]\.,.,-=12' . date( "M Y" ) . '&qwd1235^@-=@11' . date( "M Y" ) . $time; // Creates a new server salt every month
		
		//	Use the user password, server salt and browser info to generate a cookiepassword
		$cookiePassword = sha1( $password . $serverSalt . $_SERVER['HTTP_USER_AGENT'] . DOMAIN );
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
			if( empty( $username ) )
			{
				return false;
			}
			$where = array( 'username' => strtolower( $username ) );
			$userInfo = $userInfo ? : $where;
 			if( ! @$options['skip_user_check'] )
			{
				//	Check the user
				$userInfo = Application_User_Abstract::getUserInfo( $where );

                //	hide super users
				if( ! $userInfo  || ! $username )
				{
					return false;
				}
			}
			$table = Ayoola_Access_AccessInformation::getInstance();

			$authOptions = array();		
 			if( @$userInfo['access_level'] )
			{
				//	Get information about the user group
				$authOptions = new Ayoola_Access_AuthLevel();
				$authOptions = $authOptions->selectOne( null, array( 'auth_level' => $userInfo['access_level'] ) );
			}
			$info = array_merge( $authOptions ? : array(),  $userInfo ? : array(), @$previousInfo ? : array() );
		
            if( empty( $info['display_picture'] ) )  
            { 
                @$info['display_picture'] = Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Profile_PhotoViewer/profile_url/' . @$info['profile_url'] . '/document_time/' . filemtime( Application_Profile_Abstract::getProfilePath( @$info['profile_url'] ) ) . '?max_width=300&max_height=300&extension=png';
            }
            else
            {
                @$info['display_picture'] = Ayoola_Application::getUrlPrefix() . $info['display_picture'];
            }		
			@$info['display_name'] = trim( $info['display_name'] ? : ( $info['firstname'] . ' ' . $info['lastname'] ) );
			@$info['profile_description'] = $info['profile_description'] ? : ( @$info['display_name'] . ' has an account on ' . ( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Page::getDefaultDomain() ) . ', but have not updated their description yet.' );
 			return $info;
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
				return false;
			}	

			//	Check the user
			$class = new Application_User_Editor( array( 'no_init' => true ) );
			$where = array( 'username' => strtolower( $information['username'] ) );
			$class->setIdentifier( $where );
			if( ! $userInfo = $class->getIdentifierData() )
			{
				return false;
            }
            //  var_export( $userInfo );
            //  return false;
/* 			if( ! $previousInfo = @include $filename )           
			{
				//	compatibility - we used to save some info here
				$table = Ayoola_Access_AccessInformation::getInstance();
				if( ! $previousInfo = $table->selectOne( null, $where ) )
				{			
					//	Populate the Ayoola_Access_AccessInformation
				}
				else
				{
					//	We can safely remove from old db here because we will soon save in the profile path
					$table->delete( $where );
				}
			}
 */			return false;
		}
		catch( Exception $e )
		{ 
			return false; 
		}
    }  
	
    /**
     * Calculate the value for the persistent cookie
	 *
     * @param string hashed password
     * @return string The value for persistent cookie
     */
    public static function getPersistentCookieValue( $username, $password, $time = null ) 
    {
		$password = $username . $password;

		//	Use the user password, server salt and browser info to generate a cookiepassword
		$time = $time ? : time();
		$cookiePassword = self::hashPassWord( $password, $time );
		$strictCookiePassword = self::hashPassWord( $password . ( $_SERVER['REMOTE_ADDR'] ? :  $_SERVER['REMOTE_HOST'] ), $time );
		return base64_encode( $username . ':' . $cookiePassword . ':' . $time . ':' . $strictCookiePassword ); 
		
    } 
	// END OF CLASS
}
