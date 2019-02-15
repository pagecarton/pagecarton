<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_NotificationMessage_Send_Interface
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Interface.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see 
 */
 


/**
 * @user   Ayoola
 * @package    Application_User_NotificationMessage_Send_Interface
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

interface Application_User_NotificationMessage_Send_Interface
{
	
    /**
     * Returns the required dbtables for rerieving notification requirements
     * 
     * @return array $column => $tableClass
     */
	public function getRequiredTables();
	
    /**
     * Sends the notification message
     * 
     * @param string Recipients of the notification
     * @param array The parameters peculiar to the notification mode
     */
	public function sendMessage( array $messageInfo );
	// END OF CLASS 
}
