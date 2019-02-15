<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Api_SignUp
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: SignUp.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Api_SignUp
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Api_SignUp extends Ayoola_Api implements Ayoola_Api_Interface
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
	protected static $_accessLevel = 99;
	
    /**
     * CALL THE required api
     * 
     */
	public static function call( $data )
    {
		$class = new Application_User_Creator();
		$class->fakeValues = $data['data'];
		$class->init();
	//	$class->view();
	//	var_export( $class->view() );
		if( ! $class->getForm()->getValues() || $class->getForm()->getBadnews() )
		{
			throw new Ayoola_Api_Exception( array_shift( $class->getForm()->getBadnews() ) );
		}
		$values['user_id'] = $class->insertId;
		$values['application_id'] = $data['options']['authentication_info']['application_id'];
		if( is_numeric( @$data['data']['auth_level'] ) && $data['data']['auth_level'] != 99 )
		{
			$values['access_level'] = $data['data']['auth_level'];
		}
  		//	Create an application user.
		$table = new Ayoola_Application_ApplicationUserSettings; 
		if( ! $table->insert( $values ) )
		{
			throw new Ayoola_Api_Exception( 'COULD NOT SAVE APP USER INFO' );
		}
		return $data;
	//	var_export( $values );
    } 
	// END OF CLASS
}
