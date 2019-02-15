<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Email_Api_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_Email_Exception 
 */
 
require_once 'Application/User/Email/Exception.php';


/**
 * @user   Ayoola
 * @package    Application_User_Email_Api_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Email_Api_List extends Application_User_Email_Api_Abstract
{
	
    /**
     * 
     * 
     */
	public static function call( array $data )
    {
		$values = $data['data'];
		$class = new Application_User_Email_List();
		$values['application_id'] = $data['options']['authentication_info']['application_id'];
		$table = $class->getDbTable();
//		var_export( $table );
//		var_export( $values );
	//	var_export(  $data['data'] );
		$query = 'SELECT * FROM `email`, `domain`, `useraccount` WHERE email.domain_id = domain.domain_id AND useraccount.useraccount_id = domain.useraccount_id AND useraccount.application_id = "' . $values['application_id'] . '"';

		//	This was meant for single user email list but seem not to be working for pc.com users using their domain name
	//	if( ! empty( $values['email_id'] ) ){ $query .= ' AND email.email_id = "' . $values['email_id'] . '"'; }
	//	if( ! empty( $values['user_id'] ) ){ $query .= ' AND email.user_id = "' . $values['user_id'] . '"'; }
	//	var_export( $query );
//		var_export( $values );
		$select = $table->fetchSQLQuery( $query );
		$data['options']['server_response'] = $select;
		return $data;
    } 
	// END OF CLASS
}
