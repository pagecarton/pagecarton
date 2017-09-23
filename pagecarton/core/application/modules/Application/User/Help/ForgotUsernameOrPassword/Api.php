<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Help_ForgotUsernameOrPassword_Api
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Api.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_Help_Abstract
 */
 
require_once 'Application/User/Help/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_Help_ForgotUsernameOrPassword_Api
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Help_ForgotUsernameOrPassword_Api extends Ayoola_Api
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
	protected static $_accessLevel = array( 99, 98 );
	
    /**
     * CALL THE required api
     * 
     */
	public static function call( $data )
    {
		$class = new Application_User_Help_ForgotUsernameOrPassword();
		$class->fakeValues = $data['data'];
		$class->init();
	//	$class->view();
	//	var_export( $class->view() );
		if( ! $values = $class->getForm()->getValues() )
		{
			throw new Ayoola_Api_Exception( array_shift( $class->getForm()->getBadnews() ) );
		}
		$data['options']['server_response'] = true;
		return $data;
    } 
	// END OF CLASS
}
