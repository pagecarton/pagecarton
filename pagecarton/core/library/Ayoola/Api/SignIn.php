<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Api_SignIn
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: SignIn.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Api_SignIn
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Api_SignIn extends Ayoola_Api implements Ayoola_Api_Interface
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
     * CALL THE required api
     * 
     */
	public static function call( $data )
    {
		$credentials = array( 'application_id' => $data['options']['authentication_info']['application_id'] );
	//	var_export( $credentials );
		$auth = new Ayoola_Access();
		if( ! empty( $data['data']['auth_mechanism'] ) )
		{
			$auth->setAuthMechanism( $data['data']['auth_mechanism'] );
		}
		$auth->setCredentials( $credentials );
		if( $auth->authenticate( $data['data'] ) )
		{
			$userInfo = $auth->getUserInfo();
			
			//	change to sha1 for public view
		//	$userInfo['password'] = sha1( $userInfo['password'] );
			$data['options']['server_response'] = $userInfo;
		//	$response = array( 'data' => $auth->getUserInfo(), 'options' => $data['options']['return_info'] );
			return $data;
		}
		return false;
    } 
	// END OF CLASS
}
