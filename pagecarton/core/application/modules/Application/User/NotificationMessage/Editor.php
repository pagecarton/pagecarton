<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_NotificationMessage_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_User_NotificationMessage_Abstract
 */
 
require_once 'Application/User/NotificationMessage/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_NotificationMessage_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_NotificationMessage_Editor extends Application_User_NotificationMessage_Abstract
{
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
	//	var_export( __LINE__ );
		try
		{
			if( ! $data = self::getIdentifierData() ){ return false; }
			$this->createForm( 'Edit', $data['subject'], $data );
			$this->setViewContent( $this->getForm()->view(), true );
		//	var_export( $data );
			if( $this->updateDb() ){ $this->setViewContent( 'Notification Message Edited Successfully', true ); }
		}
		catch( Exception $e ){ return false; }
    } 
	// END OF CLASS
}
