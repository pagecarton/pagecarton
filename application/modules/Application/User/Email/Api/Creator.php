<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Email_Api_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_Email_Exception 
 */
 
require_once 'Application/User/Email/Exception.php';


/**
 * @user   Ayoola
 * @package    Application_User_Email_Api_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Email_Api_Creator extends Application_User_Email_Api_Abstract
{
	
    /**
     * 
     * 
     */
	public static function call( array $data )
    {
		$values = $data['data'];
		$class = new Application_User_Email_Creator();
		$values['application_id'] = $data['options']['authentication_info']['application_id'];
		$class->fakeValues = $values;
		$class->init();
	//	$class->view();
	//	var_export( $values );
		if( $values = $class->getForm()->getBadnews() )
		{
			throw new Ayoola_Api_Exception( array_shift( $class->getForm()->getBadnews() ) ); 
		}
		$data['options']['server_response'] = true;
		return $data;
    } 
	// END OF CLASS
}
