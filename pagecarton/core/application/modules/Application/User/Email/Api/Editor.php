<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Email_Api_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_Email_Exception 
 */
 
require_once 'Application/User/Email/Exception.php';


/**
 * @user   Ayoola
 * @package    Application_User_Email_Api_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Email_Api_Editor extends Application_User_Email_Api_Abstract
{
	
    /**
     * 
     * 
     */
	public static function call( array $data )
    {
		$values = $data['data'];
		$class = new Application_User_Email_Editor();
		$values['application_id'] = $data['options']['authentication_info']['application_id'];
		$table = $class->getDbTable();
		
		//	Check if the user request his own e-mail
	//	self::checkEmailOwnership( $data );
		
	//	var_export( $values['user_id'] );
		if( ! $table->update( $values, array( 'email_id' => $values['email_id'], 'user_id' => $values['old_user_id'] ) ) ) 
	//	if( ! $table->update( $values, array( 'email_id' => $values['email_id'] ) ) )
		{
			throw new Ayoola_Api_Exception( 'COULD NOT SAVE E-MAIL INFO' );
		}
		
		$data['options']['server_response'] = true;
		return $data;
    } 
	// END OF CLASS
}
