<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_NotificationMessage_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_User_NotificationMessage_Abstract
 */
 
require_once 'Application/User/NotificationMessage/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_NotificationMessage_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_NotificationMessage_Creator extends Application_User_NotificationMessage_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->createForm( 'Create', 'Create a new notification message' );
		$this->setViewContent( $this->getForm()->view(), true );
		if( $this->insertDb() ){ $this->setViewContent(  '' . self::__( 'Notification message created successfully' ) . '', true  ); }
    } 
	// END OF CLASS
}
