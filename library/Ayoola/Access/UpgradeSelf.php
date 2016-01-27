<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Access_UpgradeSelf
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: UpgradeSelf.php 3.6.2012 8.36am ayoola $
 */

/**
 * @see Ayoola_Access_Abstract
 */
 
require_once 'Ayoola/Access/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Access_UpgradeSelf
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Access_UpgradeSelf extends Ayoola_Access_Abstract
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
	protected static $_accessLevel = 1;
	
    /**
     * This method performs the class' essense.
     *
     * @param void
     * @return boolean
     */
    public function init()
    {
		require_once 'Ayoola/Access.php'; 
		$auth = new Ayoola_Access();
		require_once 'Ayoola/Page.php'; 
		$userInfo = $auth->getUserInfo();
	//	var_export( $userInfo );
		
		
		//	Log
		if( ! $userInfo )
		{
			return false;
		}
		//	If this is our first login as an installer, we are the super user
	//	var_export( is_file( Ayoola_Application::$installer ) );
	//	var_export( is_writable( Ayoola_Application::$installer ) );
	
		//	The userlist must be empty
	//	$response = Ayoola_Api_UserList::send( array() );
		$response = Ayoola_Api_UserList::send( array( 'access_level' => 99 ) );
	//	var_export( $response );
		if( is_array( @$response['data'] ) && count( $response['data'] ) === 0 )  
		{
			//	That "One" user must not be an admin
		//	$oneUser = array_pop( $response['data'] );
		//	if( intval( $oneUser['access_level'] ) === 1 ){ return false; }  
			
			//	A new general install
			if( is_file( Ayoola_Application::$installer ) &&  is_writable( Ayoola_Application::$installer ) )  
			{ 
				//	SELF DESTRUCT THE INSTALLER
			//	if( ! unlink( Ayoola_Application::$installer ) ){ return false; }
			}
			elseif( Ayoola_Application::getDomainSettings( APPLICATION_DIR ) != APPLICATION_DIR )	
			{
				//	A new subdomain
				$domainDir = Ayoola_Application::getDomainSettings( APPLICATION_DIR );
				
				//	Retrieve the username of the creator of the domain name
				$config = $domainDir . DS . 'config';
				if( is_file( $config ) )
				{
					$config = include( $config );
					$config = is_array( $config ) ? : array();
				}
				if( $config['Application_Domain_Creator'] != $userInfo['username'] )
				{
				//	return false;
				}
			}
			else
			{
			//	return false;
			}
			$userInfo['access_level'] = 99;
			if( $response = Ayoola_Api_UserEditor::send( $userInfo ) )
			{
			//	var_export( $response );
				if( isset( $response['data'] ) )
				{
					//	Login with the new information
					//	SELF DESTRUCT THE INSTALLER
				//	@unlink( Ayoola_Application::$installer );
					Ayoola_Access_Login::login( $userInfo );
					return true;
				}
			}
		}
		elseif( is_null( @$response['data'] ) )
		{
			
		}
//	var_export( $_SESSION );
		//	var_export( $userInfo );
		
		return false;
		//	exit( 'wed3wd' );
    } 
	// END OF CLASS
}
