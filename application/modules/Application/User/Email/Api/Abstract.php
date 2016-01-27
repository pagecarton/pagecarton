<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Email_Api_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_Email_Exception 
 */
 
require_once 'Application/User/Email/Exception.php';


/**
 * @user   Ayoola
 * @package    Application_User_Email_Api_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_User_Email_Api_Abstract extends Ayoola_Api
{
	
    /**
     * 
     * 
     */
	public static function checkEmailOwnership( array $data )
    {
		$values = $data['data'];
		$class = new Application_User_Email_Editor();
		$values['application_id'] = $data['options']['authentication_info']['application_id']; 
		$table = $class->getDbTable();
		
		//	Check if the user request his own e-mail
		if( ! $select = $table->fetchSQLQuery( 'SELECT * FROM `email`, `domain`, `useraccount` WHERE email.domain_id = domain.domain_id AND useraccount.application_id = "' . $values['application_id'] . '" AND email.email_id = "' . $values['email_id'] . '"', 1 ) )
		{
			throw new Ayoola_Api_Exception( 'ACCESS DENIED' );
		}		
		return true;
    } 
	
    /**
     * 
     * 
     */
	abstract public static function call( array $data );
	// END OF CLASS
}
