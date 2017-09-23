<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Verify_Email_Api
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Api.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_Verify_Abstract
 */
 
require_once 'Application/User/Verify/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_Verify_Email_Api
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Verify_Email_Api extends Ayoola_Api
{
	
	
    /**
     * CALL THE required api
     * 
     */
	public static function call( $data )
    {
		$identifier = $data['data'];
		//	verify the hash
		$hash = Application_User_Verify_Email::getHash( $identifier['email'] . ':' . $identifier['email_verification_code'] );
	//	var_export( $hash );
	//	var_export( $identifier );
		if( $hash !== $identifier['hash'] )
		{
			throw new Ayoola_Api_Exception( 'HASH ERROR.' );
		}
		//	Request for the userInfo
		$data = Ayoola_Api_UserList::call( $data );
	//	var_export( $data );
		$userInfo = $data['options']['server_response'];
	//	var_export( $userInfo );
		if( empty( $userInfo ) )
		{
			throw new Ayoola_Api_Exception( 'USER NOT FOUND' );
		}
		if( $userInfo['email_verification_status'] )
		{
			throw new Ayoola_Api_Exception( 'Thank you! E-mail address has been previously verified.' );
		}
		if( $userInfo['email_verification_code'] != $identifier['email_verification_code'] )
		{
			//	Refresh the code for security
			Application_User_Verify_Email::resetVerificationCode( $userInfo );
			throw new Ayoola_Api_Exception( 'INVALID VERIFICATION INFO. INFORMATION RESET COMPLETED.' );
		}
		$table = new Application_User_UserEmail();
		$table->update( array( 'email_verification_status' => 1 ), array( 'email' => $userInfo['email'] ) );
		
/* 		$table = new Application_User_UserSettings();
		$table->update( array( 'verified' => 1 ), array( 'user_id' => $data['user_id'] ) );
 */		$table = new Ayoola_Application_ApplicationUserSettings; 
		if( ! $table->update( array( 'verified' => 1 ), array( 'user_id' => $userInfo['user_id'] ) ) )
		{
			throw new Ayoola_Api_Exception( 'COULD NOT UPDATE APP USER INFO' );
		}
	//	$data['options']['server_response'] = true;
		return $data;
	//	var_export( $values );
    } 
	// END OF CLASS
}
