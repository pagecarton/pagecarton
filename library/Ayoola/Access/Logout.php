<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Access_Logout
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Logout.php 3.6.2012 8.36am ayoola $
 */

/**
 * @see Ayoola_Access_Abstract
 */
 
require_once 'Ayoola/Access/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Access_Logout
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Access_Logout extends Ayoola_Access_Abstract
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
		$urlToGo = Ayoola_Page::getPreviousUrl();
		$userInfo = $auth->getUserInfo();
		$auth->logout();
		
		
		//	Log
		if( $userInfo )
		{
			Application_Log_View_SignOut::log( $userInfo );
		}
		header( 'Location: ' . $urlToGo );
	//	exit( 'wed3wd' );
    } 
	// END OF CLASS
}
