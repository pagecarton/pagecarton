<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Email_My_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_Email_My_Exception 
 */
 
require_once 'Application/User/Email/Exception.php';


/**
 * @user   Ayoola
 * @package    Application_User_Email_My_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_User_Email_My_Abstract extends Application_User_Email_Abstract
{
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 1;		
	
    /**
     * This method secures the application from injection of user_id by a standard user.
     *
     * @param 
     */
    public function getIdentifierUserIdQuery()
    {		
		$access = new Ayoola_Access();
		if( ! $userInfo = $access->getUserInfo() ){ return false; }
		return $userInfo['user_id'];
    } 
	
	// END OF CLASS
}
